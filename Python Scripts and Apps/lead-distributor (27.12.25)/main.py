import os
import logging
import re
import time

import requests
from dotenv import load_dotenv
from flask import Flask, request, jsonify
from openai import OpenAI

load_dotenv()


def env_int(name, default):
    value = os.getenv(name)
    return int(value) if value not in (None, "") else default


def env_int_list(name, default):
    value = os.getenv(name, "")
    if not value.strip():
        return default
    return [int(item.strip()) for item in value.split(",") if item.strip()]


# Logging: do not write lead payloads, contact details, API responses or AI output to logs.
logging.basicConfig(
    filename=os.getenv("LOG_FILE", "lead_distributor.log"),
    level=os.getenv("LOG_LEVEL", "INFO").upper(),
    format="[%(asctime)s] %(levelname)s: %(message)s",
    datefmt="%Y-%m-%d %H:%M:%S",
    encoding="utf-8",
)

OPENROUTER_API_KEY = os.getenv("OPENROUTER_API_KEY")
YANDEXGPT_API_KEY = os.getenv("YANDEXGPT_API_KEY")
YANDEXGPT_CATALOG_ID = os.getenv("YANDEXGPT_CATALOG_ID")

BITRIX_WEBHOOK_GET = os.getenv("BITRIX_WEBHOOK_GET")
BITRIX_WEBHOOK_LIST = os.getenv("BITRIX_WEBHOOK_LIST")
BITRIX_WEBHOOK_UPDATE = os.getenv("BITRIX_WEBHOOK_UPDATE")
BITRIX_WEBHOOK_USER = os.getenv("BITRIX_WEBHOOK_USER")

YANDEXGPT_BASE_URL = os.getenv(
    "YANDEXGPT_BASE_URL",
    "https://rest-assistant.api.cloud.yandex.net/v1",
)
YANDEXGPT_MODEL = os.getenv("YANDEXGPT_MODEL", "aliceai-llm/latest")
OPENROUTER_URL = os.getenv(
    "OPENROUTER_URL",
    "https://openrouter.ai/api/v1/chat/completions",
)
OPENROUTER_MODEL = os.getenv(
    "OPENROUTER_MODEL",
    "deepseek/deepseek-chat-v3-0324:free",
)

MAIN_MANAGERS = env_int_list("MAIN_MANAGERS", [12, 286, 1074])
SPECIAL_MANAGER = env_int("SPECIAL_MANAGER", 1016)
DEFAULT_MANAGER = env_int("DEFAULT_MANAGER", 1850)
JUNK_STATUS_ID = os.getenv("JUNK_STATUS_ID", "JUNK")

RETRY_ATTEMPTS = env_int("RETRY_ATTEMPTS", 3)
RETRY_DELAY = env_int("RETRY_DELAY", 5)
BITRIX_TIMEOUT = env_int("BITRIX_TIMEOUT", 10)
OPENROUTER_TIMEOUT = env_int("OPENROUTER_TIMEOUT", 15)

FLASK_HOST = os.getenv("FLASK_HOST", "127.0.0.1")
FLASK_PORT = env_int("FLASK_PORT", 5000)
FLASK_DEBUG = os.getenv("FLASK_DEBUG", "false").lower() in ("1", "true", "yes")

required_settings = {
    "YANDEXGPT_API_KEY": YANDEXGPT_API_KEY,
    "YANDEXGPT_CATALOG_ID": YANDEXGPT_CATALOG_ID,
    "BITRIX_WEBHOOK_GET": BITRIX_WEBHOOK_GET,
    "BITRIX_WEBHOOK_LIST": BITRIX_WEBHOOK_LIST,
    "BITRIX_WEBHOOK_UPDATE": BITRIX_WEBHOOK_UPDATE,
    "BITRIX_WEBHOOK_USER": BITRIX_WEBHOOK_USER,
}
missing_settings = [name for name, value in required_settings.items() if not value]
if missing_settings:
    raise RuntimeError(
        "Missing required environment variables: " + ", ".join(missing_settings)
    )

client = OpenAI(
    api_key=YANDEXGPT_API_KEY,
    base_url=YANDEXGPT_BASE_URL,
)

logging.info(
    "Application configuration loaded. "
    "OpenRouter fallback: %s",
    "enabled" if OPENROUTER_API_KEY else "disabled",
)

app = Flask(__name__)


def bitrix_api_request(webhook, params=None):
    """Запрос к Bitrix24 API."""
    if not webhook:
        logging.error("Bitrix24 webhook URL is not configured")
        return None

    try:
        response = requests.post(
            webhook,
            json=params or {},
            timeout=BITRIX_TIMEOUT,
        )
        response.raise_for_status()
        result = response.json()

        if "result" in result:
            return result["result"]

        logging.error("Bitrix24 API response does not contain a result field")
        return None
    except (requests.exceptions.RequestException, ValueError) as error:
        logging.error("Bitrix24 API request failed: %s", error)
        return None


def is_manager_on_vacation(manager_id):
    """Проверка отпуска менеджера с повторными попытками."""
    for attempt in range(RETRY_ATTEMPTS):
        try:
            response = bitrix_api_request(
                BITRIX_WEBHOOK_USER,
                {"ID": str(manager_id)},
            )
            if response and isinstance(response, list) and len(response) > 0:
                user_data = response[0]
                vacation_status = user_data.get("UF_USR_1753768634320", "0")
                is_vacation = vacation_status == "1"
                logging.info(
                    "Vacation status checked for manager %s: %s",
                    manager_id,
                    is_vacation,
                )
                return is_vacation

            logging.error(
                "Could not fetch manager data (manager ID %s), attempt %s/%s",
                manager_id,
                attempt + 1,
                RETRY_ATTEMPTS,
            )
            if attempt < RETRY_ATTEMPTS - 1:
                time.sleep(RETRY_DELAY)
        except Exception as error:
            logging.error(
                "Vacation check failed for manager %s, attempt %s/%s: %s",
                manager_id,
                attempt + 1,
                RETRY_ATTEMPTS,
                error,
            )
            if attempt < RETRY_ATTEMPTS - 1:
                time.sleep(RETRY_DELAY)

    logging.error("All vacation-check attempts failed for manager %s", manager_id)
    return False


def determine_manager(current_lead_id):
    """Ротация менеджеров категории 1 с исключением находящихся в отпуске."""
    active_managers = [
        manager for manager in MAIN_MANAGERS
        if not is_manager_on_vacation(manager)
    ]

    if not active_managers:
        logging.warning(
            "All main managers appear unavailable; falling back to the first configured manager"
        )
        return MAIN_MANAGERS[0]

    leads = bitrix_api_request(
        BITRIX_WEBHOOK_LIST,
        {
            "filter": {"!ID": current_lead_id},
            "order": {"DATE_CREATE": "DESC"},
            "select": ["ASSIGNED_BY_ID", "STATUS_ID", "ID"],
            "start": 0,
            "limit": 50,
        },
    )

    if not leads:
        logging.info("No previous leads found; assigning first active manager")
        return active_managers[0]

    for lead in leads:
        assigned_by_id = lead.get("ASSIGNED_BY_ID")
        if assigned_by_id in [str(manager) for manager in MAIN_MANAGERS]:
            last_main_manager = int(assigned_by_id)

            if (
                lead.get("STATUS_ID") == JUNK_STATUS_ID
                and last_main_manager in active_managers
            ):
                logging.info("JUNK lead found; retaining the corresponding manager")
                return last_main_manager

            if last_main_manager not in active_managers:
                continue

            try:
                index = active_managers.index(last_main_manager)
                next_index = (index + 1) % len(active_managers)
                next_manager = active_managers[next_index]
                logging.info("Manager rotation selected manager %s", next_manager)
                return next_manager
            except ValueError:
                logging.error("Previous manager is not in the active manager list")
                return active_managers[0]

    logging.info("No leads assigned to the main manager group were found")
    return active_managers[0]


def extract_category(content):
    match = re.match(r"(\d)", content.strip())
    if match and match.group(1) in ("1", "2", "3"):
        return int(match.group(1))
    return 3


def analyze_text(text):
    """Классификация лида через YandexGPT с резервным OpenRouter."""
    prompt = (
        "Ты — эксперт по классификации лидов. Твоя задача — внимательно прочитать весь текст лида и определить категорию строго по правилам ниже. "
        "Приоритет категорий: 1 > 2 > 3. Если есть признаки категории 1 — всегда выбирай 1, даже если есть признаки категории 2.\n\n"
        "Категория 1 (СТМ / контрактное производство / общие запросы):\n"
        "- Упоминания СТМ, собственная торговая марка, собственный бренд, собственная марка\n"
        "- Контрактное производство, контракт, производство, OEM\n"
        "- Запрос прайса, цен (кроме явно оптовых для аптек)\n"
        "- Выпуск под собственной маркой, под своим брендом, под своей торговой маркой\n"
        "- Общие запросы на связь без уточнения: 'перезвоните', 'напишите', 'свяжитесь', 'заказать звонок', 'обсудить', 'интересует сотрудничество' и подобные\n"
        "- Любые намёки на интерес к производству или собственной марке\n\n"
        "Категория 2 (аптеки и крупные закупки):\n"
        "- Только если НЕТ признаков категории 1\n"
        "- Упоминания аптеки, аптек, аптечной сети\n"
        "- Хочу продавать в аптеках, размещение в аптеках, поставка в аптеки, реализация в аптеках, продажа через аптеки\n"
        "- Опт, оптовые закупки, крупные партии, дистрибуция (если явно связано с аптеками или без признаков 1)\n\n"
        "Категория 3:\n"
        "- Всё остальное: розничные покупки, вопросы по продукту, жалобы, неопределённые сообщения без признаков 1 или 2\n\n"
        f"Текст лида: '{text}'\n\n"
        "Правила:\n"
        "- Читай весь текст внимательно\n"
        "- Категория 1 имеет высший приоритет\n"
        "- Если есть хоть малейший намёк на СТМ, производство или общий запрос на связь — выбирай 1\n"
        "- Категорию 2 выбирай только если нет ничего из категории 1 и есть явные упоминания аптек\n"
        "- Ответ ТОЛЬКО одной цифрой: 1, 2 или 3. Ничего больше не пиши.\n\n"
        "Примеры:\n"
        "- 'Хочу продавать ваши БАДы в аптеках' → 2\n"
        "- 'Интересует размещение в аптечной сети' → 2\n"
        "- 'Поставка в аптеки крупной партией' → 2\n"
        "- 'Хочу продавать в аптеках и производство под своей маркой' → 1\n"
        "- 'Хочу свою торговую марку' → 1\n"
        "- 'Прайс на опт, перезвоните' → 1\n"
        "- 'Заказать звонок' → 1\n"
        "- 'Вопрос по составу продукта' → 3\n"
    )

    for attempt in range(RETRY_ATTEMPTS):
        try:
            response = client.responses.create(
                model=f"gpt://{YANDEXGPT_CATALOG_ID}/{YANDEXGPT_MODEL}",
                temperature=0.1,
                max_output_tokens=50,
                input=prompt,
            )
            category = extract_category(response.output_text)
            logging.info("Lead text classified by YandexGPT: category %s", category)
            return category
        except Exception as error:
            logging.error("YandexGPT classification attempt %s failed: %s", attempt + 1, error)
            if attempt < RETRY_ATTEMPTS - 1:
                time.sleep(RETRY_DELAY)

    logging.error("All YandexGPT classification attempts failed")

    if not OPENROUTER_API_KEY:
        logging.error("OpenRouter fallback is unavailable: API key is not configured")
        return 3

    headers_openrouter = {
        "Authorization": f"Bearer {OPENROUTER_API_KEY}",
        "Content-Type": "application/json",
        "HTTP-Referer": "http://localhost:5000",
        "X-Title": "Lead Distributor",
    }
    data_openrouter = {
        "model": OPENROUTER_MODEL,
        "messages": [{"role": "user", "content": prompt}],
        "temperature": 0.1,
    }

    for attempt in range(RETRY_ATTEMPTS):
        try:
            response = requests.post(
                OPENROUTER_URL,
                headers=headers_openrouter,
                json=data_openrouter,
                timeout=OPENROUTER_TIMEOUT,
            )
            response.raise_for_status()
            result = response.json()
            content = result["choices"][0]["message"]["content"].strip()
            category = extract_category(content)
            logging.info("Lead text classified by OpenRouter: category %s", category)
            return category
        except (requests.exceptions.RequestException, ValueError, KeyError, IndexError) as error:
            logging.error("OpenRouter classification attempt %s failed: %s", attempt + 1, error)
            if attempt < RETRY_ATTEMPTS - 1:
                time.sleep(RETRY_DELAY)

    return 3


def enrich_source_description(lead_data, category):
    """Подготовка аналитической справки о компании для карточки лида."""
    phones = [item["VALUE"] for item in lead_data.get("PHONE", [])]
    emails = [item["VALUE"] for item in lead_data.get("EMAIL", [])]
    name = lead_data.get("NAME", "").strip()
    title = lead_data.get("TITLE", "").strip()
    comments = lead_data.get("COMMENTS", "").strip()

    company_name_hint = name or title or comments

    context = (
        f"Имя контакта: {name}\n"
        f"Телефоны: {', '.join(phones)}\n"
        f"Email: {', '.join(emails)}\n"
        f"Заголовок лида: {title}\n"
        f"Комментарий: {comments}\n"
        f"Возможное название компании: {company_name_hint}"
    )

    prompt = (
        "Ты — профессиональный аналитик по компаниям. У тебя есть данные о лиде:\n"
        f"{context}\n\n"
        "Задача:\n"
        "1. Найди информацию о компании из контекста потом через свою базу и выдай ключевые данные о компании (Сайт, юридическую информацию), дополнительные сведения и помоги менеджеру рекомендацией (рекомендация должна быть максимально подробной). Все очень подробно и детально\n"
    )

    for attempt in range(2):
        try:
            response = client.responses.create(
                model=f"gpt://{YANDEXGPT_CATALOG_ID}/{YANDEXGPT_MODEL}",
                temperature=0.3,
                max_output_tokens=800,
                input=prompt,
            )
            content = response.output_text.strip()
            logging.info("Company analysis generated successfully")
            return content
        except Exception as error:
            logging.error("Company enrichment attempt %s failed: %s", attempt + 1, error)
            if attempt < 1:
                time.sleep(5)

    return "Не удалось получить анализ компании"


def process_lead(lead_id, title, comments):
    logging.info("Processing lead %s", lead_id)
    lead_data = bitrix_api_request(BITRIX_WEBHOOK_GET, {"id": lead_id})
    if not lead_data:
        logging.error("Failed to fetch lead %s", lead_id)
        return False

    text = f"{title} {comments}".strip()
    has_contact_info = bool(
        lead_data.get("NAME", "").strip()
        or lead_data.get("HAS_PHONE") == "Y"
        or lead_data.get("HAS_EMAIL") == "Y"
    )

    if not comments and has_contact_info:
        category = 1
        logging.info("Lead has contact information but no comments; category 1 selected")
    else:
        category = analyze_text(text) if text else 3

    if category == 1:
        manager_id = determine_manager(lead_id)
    elif category == 2:
        manager_id = SPECIAL_MANAGER
    else:
        manager_id = DEFAULT_MANAGER

    update_response = bitrix_api_request(
        BITRIX_WEBHOOK_UPDATE,
        {
            "id": lead_id,
            "fields": {"ASSIGNED_BY_ID": manager_id},
        },
    )
    if not update_response:
        logging.error("Failed to assign manager to lead %s", lead_id)
        return False

    source_description = enrich_source_description(lead_data, category)
    update_desc = bitrix_api_request(
        BITRIX_WEBHOOK_UPDATE,
        {
            "id": lead_id,
            "fields": {"SOURCE_DESCRIPTION": source_description},
        },
    )
    if update_desc:
        logging.info("Lead %s source description updated", lead_id)
    else:
        logging.error("Failed to update source description for lead %s", lead_id)

    return True


@app.route("/webhook", methods=["POST"])
@app.route("/", methods=["POST"])
def webhook_handler():
    logging.info("Webhook endpoint accessed")
    try:
        if "application/json" in request.headers.get("Content-Type", ""):
            data = request.get_json(force=True)
        else:
            form_data = request.form.to_dict()
            if "data[FIELDS][ID]" in form_data:
                data = {
                    "event": form_data.get("event"),
                    "data": {
                        "FIELDS": {
                            "ID": form_data.get("data[FIELDS][ID]"),
                            "TITLE": form_data.get("data[FIELDS][TITLE]", ""),
                            "COMMENTS": form_data.get("data[FIELDS][COMMENTS]", ""),
                        }
                    },
                }
            else:
                data = form_data

        if not isinstance(data, dict):
            return jsonify({"status": "error", "message": "Invalid request"}), 400

        if data.get("event") == "ONCRMLEADADD":
            lead_fields = data.get("data", {}).get("FIELDS", {})
            lead_id = lead_fields.get("ID")
            title = lead_fields.get("TITLE", "")
            comments = lead_fields.get("COMMENTS", "")

            if lead_id:
                success = process_lead(lead_id, title, comments)
                return jsonify({"status": "success" if success else "error"})

        return jsonify({"status": "error", "message": "Invalid request"}), 400
    except Exception as error:
        logging.exception("Webhook processing failed: %s", error)
        return jsonify({"status": "error", "message": "Internal server error"}), 500


if __name__ == "__main__":
    app.run(
        debug=FLASK_DEBUG,
        host=FLASK_HOST,
        port=FLASK_PORT,
    )
