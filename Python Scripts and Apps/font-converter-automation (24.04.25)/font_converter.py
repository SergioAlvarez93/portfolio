import os
import re
import time
import zipfile
import threading
import platform
import logging
import queue

from tkinter import Tk, filedialog, messagebox
from tkinter import ttk

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException, NoSuchElementException, WebDriverException


logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    filename='font_converter.log',
    filemode='w'
)


class FontConverter:
    def __init__(self):
        self.driver = None
        self.root = Tk()
        self.root.withdraw()
        self.progress_window = None
        self.progress_bar = None
        self.progress_label = None
        self.message_queue = queue.Queue()
        self.stop_flag = threading.Event()
        self.conversion_success = False

    def update_progress(self, value, message=None):
        """Обновляет прогрессбар и сообщение"""
        if self.progress_window and self.progress_bar and not self.stop_flag.is_set():
            self.progress_window.after(0, lambda: (
                self.progress_bar.__setitem__('value', value),
                self.progress_label.config(text=message) if message and self.progress_label else None,
                self.progress_window.update_idletasks()
            ))

    def process_messages(self):
        """Обрабатывает сообщения из очереди"""
        if self.stop_flag.is_set() or not self.progress_window:
            return
        try:
            msg_type, message = self.message_queue.get_nowait()
            if msg_type == "error":
                messagebox.showerror("Ошибка", message)
            elif msg_type == "info":
                messagebox.showinfo("Информация", message)
                self.conversion_success = True
            self.progress_window.destroy()
        except queue.Empty:
            self.progress_window.after(100, self.process_messages)

    def show_error(self, message):
        """Отображает сообщение об ошибке"""
        logging.error(message, exc_info=True)
        self.message_queue.put(("error", message))
        self.stop_flag.set()

    def show_info(self, message):
        """Отображает информационное сообщение"""
        logging.info(message)
        self.message_queue.put(("info", message))
        self.conversion_success = True

    def select_folder(self, title):
        """Выбирает папку через диалоговое окно"""
        folder = filedialog.askdirectory(title=title)
        if not folder:
            self.show_error(f"Папка не выбрана для: {title}")
        return folder

    def extract_font_weight(self, font_name):
        """Извлекает толщину шрифта из имени файла (100-900)"""
        match = re.search(r'_(\d{3})(?=\.[^.]+$)', font_name)
        if match:
            weight = match.group(1)
            if weight in ['100', '200', '300', '400', '500', '600', '700', '800', '900']:
                return weight
            else:
                logging.warning(f"Некорректное значение веса шрифта: {weight} в файле {font_name}")
        return "400"

    def get_downloads_folder(self):
        """Возвращает путь к папке загрузок для разных ОС"""
        if platform.system() == "Windows":
            return os.path.join(os.environ['USERPROFILE'], 'Downloads')
        return os.path.join(os.path.expanduser('~'), 'Downloads')

    def wait_for_download_complete(self, timeout=300):
        """Ожидает завершения загрузки файла с проверкой размера"""
        downloads_folder = self.get_downloads_folder()
        start_time = time.time()
        last_size = -1
        stable_count = 0

        while (time.time() - start_time) < timeout and not self.stop_flag.is_set():
            time.sleep(1)
            files = [f for f in os.listdir(downloads_folder)
                     if f.endswith('.zip') or f.endswith('.crdownload')]

            if not files:
                continue

            newest_file = max(
                [os.path.join(downloads_folder, f) for f in files],
                key=os.path.getctime
            )

            if newest_file.endswith('.crdownload'):
                continue

            current_size = os.path.getsize(newest_file)
            if current_size == last_size:
                stable_count += 1
                if stable_count >= 3:
                    return newest_file
            else:
                last_size = current_size
                stable_count = 0

        raise TimeoutError("Время ожидания загрузки истекло")

    def normalize_font_family_name(self, font_name):
        """Нормализует имя шрифта для сопоставления в CSS"""
        base_name = os.path.splitext(font_name)[0]
        base_name = re.sub(r'_\d{3}$', '', base_name)
        base_name = base_name.replace('-', ' ')
        base_name = base_name.replace('_', ' ')
        base_name = ' '.join(base_name.split())
        return base_name.strip("'\" ")

    def generate_custom_css(self, font_weights, output_folder):
        """Генерирует CSS файл с @font-face правилами"""
        try:
            css_content = []
            wp_content_index = output_folder.lower().find('/wp-content')
            base_path = output_folder[wp_content_index:] if wp_content_index != -1 else output_folder
            base_path = base_path.replace('\\', '/')

            for font_file, weight in font_weights.items():
                font_family = self.normalize_font_family_name(font_file)
                font_name = os.path.splitext(font_file)[0]
                logging.info(f"Генерация @font-face для '{font_family}' с весом {weight}")

                font_face = f"""@font-face {{
    font-family: "{font_family}";
    src: url("{base_path}/{font_name}.eot");
    src: url("{base_path}/{font_name}.eot?#iefix") format("embedded-opentype"),
         url("{base_path}/{font_name}.woff2") format("woff2"),
         url("{base_path}/{font_name}.woff") format("woff"),
         url("{base_path}/{font_name}.ttf") format("truetype"),
         url("{base_path}/{font_name}.svg#{font_name}") format("svg");
    font-weight: {weight};
    font-style: normal;
    font-display: swap;
}}

"""
                css_content.append(font_face)

            css_path = os.path.join(output_folder, "stylesheet.css")
            with open(css_path, 'w', encoding='utf-8') as f:
                f.write('\n'.join(css_content))

            logging.info("CSS файл успешно сгенерирован")
            return True

        except Exception as e:
            logging.error(f"Ошибка при генерации CSS файла: {str(e)}", exc_info=True)
            self.show_error(f"Ошибка при генерации CSS файла: {str(e)}")
            return False

    def convert_fonts(self, source_folder, output_folder):
        """Конвертирует шрифты через transfonter.org"""
        try:
            if not os.path.exists(source_folder):
                self.show_error("Исходная папка не существует")
                return False

            os.makedirs(output_folder, exist_ok=True)

            font_weights = {}
            font_files = []

            for file in os.listdir(source_folder):
                if file.lower().endswith(('.ttf', '.otf')):
                    weight = self.extract_font_weight(file)
                    font_weights[file] = weight
                    font_files.append(os.path.join(source_folder, file))
                    logging.info(f"Найден шрифт: {file} (font-weight: {weight})")

            if not font_files:
                self.show_error("Не найдено подходящих файлов шрифтов (.ttf или .otf)")
                return False

            self.update_progress(5, "Подготовка к конвертации...")

            options = webdriver.ChromeOptions()
            options.add_argument('--no-sandbox')
            options.add_argument('--disable-dev-shm-usage')
            options.add_argument('--headless=new')
            options.add_argument('--window-size=1920,1080')
            options.add_argument('user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36')
            options.add_experimental_option('prefs', {
                'download.default_directory': self.get_downloads_folder(),
                'download.prompt_for_download': False,
                'download.directory_upgrade': True,
                'safebrowsing.enabled': True
            })

            try:
                self.driver = webdriver.Chrome(options=options)
                logging.info("WebDriver успешно запущен в headless режиме")
                self.update_progress(10, "Запуск браузера...")
            except WebDriverException as e:
                self.show_error(f"Не удалось запустить WebDriver: {str(e)}\nУбедитесь, что ChromeDriver установлен")
                return False

            try:
                self.driver.get("https://transfonter.org/")
                logging.info("Открыта страница transfonter.org")
                self.update_progress(15, "Загрузка страницы конвертера...")

                add_font_input = WebDriverWait(self.driver, 20).until(
                    EC.presence_of_element_located((By.ID, "add-font"))
                )

                if add_font_input.get_attribute("multiple"):
                    add_font_input.send_keys("\n".join(font_files))
                    logging.info("Отправлены все файлы одновременно")
                    self.update_progress(25, "Загрузка шрифтов на сервер...")
                else:
                    for i, font in enumerate(font_files):
                        if self.stop_flag.is_set():
                            return False
                        add_font_input.send_keys(font)
                        time.sleep(1)
                        progress = 25 + int((i + 1) / len(font_files) * 15)
                        self.update_progress(progress, f"Загрузка шрифтов ({i+1}/{len(font_files)})...")

                WebDriverWait(self.driver, 30).until(
                    EC.presence_of_all_elements_located((By.CSS_SELECTOR, ".font-item"))
                )
                uploaded_count = len(self.driver.find_elements(By.CSS_SELECTOR, ".font-item"))
                if uploaded_count != len(font_files):
                    self.show_error(f"Загружено {uploaded_count} из {len(font_files)} файлов")
                    return False

                self.update_progress(45, "Настройка параметров конвертации...")

                formats = ['ttf', 'eot', 'woff', 'woff2', 'svg']
                for i, fmt in enumerate(formats):
                    if self.stop_flag.is_set():
                        return False
                    checkbox = WebDriverWait(self.driver, 10).until(
                        EC.presence_of_element_located((By.XPATH, f"//input[@type='checkbox' and @value='{fmt}']"))
                    )
                    if not checkbox.is_selected():
                        checkbox.click()
                    progress = 45 + int((i + 1) / len(formats) * 10)
                    self.update_progress(progress, f"Выбор форматов ({i+1}/{len(formats)})...")

                convert_btn = WebDriverWait(self.driver, 10).until(
                    EC.element_to_be_clickable((By.ID, "btn-convert"))
                )
                convert_btn.click()
                logging.info("Запущена конвертация шрифтов")
                self.update_progress(60, "Выполняется конвертация шрифтов...")

                download_btn = WebDriverWait(self.driver, 300).until(
                    EC.element_to_be_clickable((By.ID, "btn-download"))
                )
                self.update_progress(80, "Конвертация завершена, подготовка к скачиванию...")

                download_url = download_btn.get_attribute("href")
                self.driver.get(download_url)
                zip_path = self.wait_for_download_complete()
                self.update_progress(85, "Скачивание архива...")

                with zipfile.ZipFile(zip_path, 'r') as zip_ref:
                    zip_ref.extractall(output_folder)
                self.update_progress(90, "Распаковка архива...")

                try:
                    os.remove(zip_path)
                except OSError:
                    pass

                old_css_path = os.path.join(output_folder, "stylesheet.css")
                if os.path.exists(old_css_path):
                    try:
                        os.remove(old_css_path)
                        logging.info("Удален старый stylesheet.css")
                    except OSError as e:
                        logging.warning(f"Не удалось удалить старый stylesheet.css: {str(e)}")

                demo_html_path = os.path.join(output_folder, "demo.html")
                if os.path.exists(demo_html_path):
                    try:
                        os.remove(demo_html_path)
                        logging.info("Удален demo.html")
                    except OSError as e:
                        logging.warning(f"Не удалось удалить demo.html: {str(e)}")

                self.update_progress(95, "Генерация CSS файла...")
                if not self.generate_custom_css(font_weights, output_folder):
                    return False

                self.update_progress(100, "Конвертация завершена!")
                self.show_info(f"Конвертация успешно завершена!\nРезультаты сохранены в: {output_folder}")
                return True

            except TimeoutException as e:
                self.show_error(f"Превышено время ожидания: {str(e)}")
                return False
            except Exception as e:
                self.show_error(f"Ошибка при работе с сайтом: {str(e)}")
                return False

        except Exception as e:
            self.show_error(f"Неожиданная ошибка: {str(e)}")
            return False
        finally:
            if self.driver:
                try:
                    self.driver.quit()
                    self.driver = None
                except:
                    pass

    def run(self):
        """Запускает процесс конвертации"""
        try:
            source_folder = self.select_folder("Выберите папку с исходными шрифтами")
            if not source_folder:
                return

            output_folder = self.select_folder("Выберите папку для сохранения конвертированных шрифтов")
            if not output_folder:
                return

            self.progress_window = Tk()
            self.progress_window.title("Конвертация шрифтов")
            self.progress_window.geometry("400x150")

            def on_closing():
                if messagebox.askokcancel("Выход", "Прервать процесс конвертации?"):
                    self.stop_flag.set()
                    if self.driver:
                        try:
                            self.driver.quit()
                            self.driver = None
                        except:
                            pass
                    self.progress_window.destroy()

            self.progress_window.protocol("WM_DELETE_WINDOW", on_closing)

            self.progress_label = ttk.Label(
                self.progress_window,
                text="Подготовка к конвертации...",
                font=('Arial', 10)
            )
            self.progress_label.pack(pady=10)

            self.progress_bar = ttk.Progressbar(
                self.progress_window,
                orient="horizontal",
                length=300,
                mode="determinate",
                maximum=100
            )
            self.progress_bar.pack()

            ttk.Button(
                self.progress_window,
                text="Отмена",
                command=on_closing
            ).pack(pady=10)

            self.stop_flag.clear()
            self.progress_window.after(100, self.process_messages)

            conversion_thread = threading.Thread(
                target=self.convert_fonts,
                args=(source_folder, output_folder),
                daemon=True
            )
            conversion_thread.start()

            self.progress_window.mainloop()

        except Exception as e:
            self.show_error(f"Ошибка в основном потоке: {str(e)}")
        finally:
            self.stop_flag.set()
            if self.driver:
                try:
                    self.driver.quit()
                    self.driver = None
                except:
                    pass
            if self.progress_window:
                try:
                    self.progress_window.destroy()
                except:
                    pass
            try:
                self.root.destroy()
            except:
                pass


if __name__ == "__main__":
    try:
        converter = FontConverter()
        converter.run()
    except KeyboardInterrupt:
        logging.info("Программа прервана пользователем")
    finally:
        converter = None
