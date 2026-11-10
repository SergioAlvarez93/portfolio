# Font Converter Automation

Desktop-приложение на Python для автоматизации конвертации локальных файлов шрифтов через Transfonter и подготовки CSS для подключения на сайте.

## Возможности

- Выбор папки с исходными файлами `.ttf` и `.otf`.
- Автоматизация веб-интерфейса Transfonter с помощью Selenium WebDriver.
- Конвертация в TTF, EOT, WOFF, WOFF2 и SVG.
- Скачивание и распаковка ZIP-архива.
- Определение `font-weight` по суффиксу имени файла (100–900).
- Генерация `stylesheet.css` с правилами `@font-face`.
- GUI с индикатором прогресса и возможностью отмены.
- Логирование в `font_converter.log`.

## Стек

Python, Tkinter/ttk, Selenium WebDriver, Chrome, threading, queue, zipfile, регулярные выражения, CSS.

## Требования

- Python 3.9+
- Google Chrome
- Совместимый ChromeDriver (если Selenium не может автоматически подобрать драйвер)

## Установка

```bash
python -m venv .venv
```

Windows:
```bash
.venv\Scripts\activate
```

macOS/Linux:
```bash
source .venv/bin/activate
```

Установка зависимостей и запуск:

```bash
pip install -r requirements.txt
python font_converter.py
```

## Использование

1. Выберите папку с исходными `.ttf` и `.otf`.
2. Выберите папку для сохранения результата.
3. Приложение запустит Chrome в headless-режиме и загрузит шрифты в Transfonter.
4. После конвертации ZIP-архив будет скачан и распакован.
5. В выходной папке будет создан `stylesheet.css`.

Для определения веса программа ищет числовой суффикс `100`–`900` перед расширением. Если вес не найден или не распознан, используется `400`.

## Примечания

- Требуется интернет-соединение и доступность Transfonter.
- Исходные файлы загружаются на сторонний сервис. Не отправляйте туда конфиденциальные или клиентские материалы без разрешения.
- Изменения интерфейса Transfonter или его HTML-селекторов могут потребовать обновления Selenium-сценария.
- CSS-пути формируются на основе выбранной выходной папки; после размещения файлов на сайте проверьте их соответствие реальным URL.

## Структура

```text
font-converter-automation/
├── font_converter.py
├── requirements.txt
├── .gitignore
└── README.md
```
