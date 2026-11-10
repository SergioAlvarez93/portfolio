import sys
import requests
import base64
import os
from datetime import datetime
import threading

from dotenv import load_dotenv
from PyQt6.QtWidgets import (
    QApplication, QMainWindow, QTabWidget, QWidget,
    QVBoxLayout, QHBoxLayout, QPushButton, QTextEdit, QLabel,
    QDateEdit, QFrame, QComboBox
)
from PyQt6.QtWebEngineWidgets import QWebEngineView
from PyQt6.QtCore import QUrl, pyqtSignal, QObject, QDate
from PyQt6.QtGui import QIcon


load_dotenv()

BASE_URL = os.getenv("ONE_C_BASE_URL", "").rstrip("/")
WAREHOUSE_URL = f"{BASE_URL}/api-warehouses/get-warehouse-stock-json/"
ORDERS_URL = f"{BASE_URL}/api-orders/get-client-orders-json/all"

LOGIN = os.getenv("ONE_C_LOGIN", "")
PASSWORD = os.getenv("ONE_C_PASSWORD", "")

DEFAULT_WAREHOUSES = [
    warehouse.strip()
    for warehouse in os.getenv("DEFAULT_WAREHOUSES", "").split(",")
    if warehouse.strip()
]

DEEPSEEK_URL = os.getenv("DEEPSEEK_URL", "https://chat.deepseek.com/")


def resource_path(relative_path):
    try:
        base_path = sys._MEIPASS
    except Exception:
        base_path = os.path.abspath(".")
    return os.path.join(base_path, relative_path)


class WorkerSignals(QObject):
    status = pyqtSignal(str)
    report_ready = pyqtSignal(str, str)
    warehouses_loaded = pyqtSignal(list)
    error = pyqtSignal(str)
    finished = pyqtSignal()


class MainWindow(QMainWindow):
    def __init__(self):
        super().__init__()
        self.setWindowTitle("1C - DeepSeek | Анализ остатков и заказов")
        self.resize(1450, 850)

        self.all_warehouses = []
        self.warehouse_combos = []
        self.last_data = None
        self.last_filename = None

        tabs = QTabWidget()
        self.setCentralWidget(tabs)

        tab_data = QWidget()
        layout_data = QVBoxLayout(tab_data)

        control_frame = QFrame()
        control_frame.setFrameStyle(QFrame.Shape.StyledPanel)
        control_layout = QVBoxLayout(control_frame)

        date_layout = QHBoxLayout()
        date_layout.addWidget(QLabel("Период с:"))
        self.date_from = QDateEdit()
        self.date_from.setCalendarPopup(True)
        self.date_from.setDate(QDate.currentDate().addMonths(-6))
        self.date_from.setDisplayFormat("dd.MM.yyyy")
        date_layout.addWidget(self.date_from)

        date_layout.addWidget(QLabel("по:"))
        self.date_to = QDateEdit()
        self.date_to.setCalendarPopup(True)
        self.date_to.setDate(QDate.currentDate())
        self.date_to.setDisplayFormat("dd.MM.yyyy")
        date_layout.addWidget(self.date_to)
        control_layout.addLayout(date_layout)

        warehouse_label = QLabel("Склады:")
        control_layout.addWidget(warehouse_label)

        self.warehouse_container = QWidget()
        self.warehouse_layout = QVBoxLayout(self.warehouse_container)
        self.warehouse_layout.setContentsMargins(0, 0, 0, 0)

        self.add_warehouse_row(DEFAULT_WAREHOUSES[0] if DEFAULT_WAREHOUSES else "")
        self.add_warehouse_row(DEFAULT_WAREHOUSES[1] if len(DEFAULT_WAREHOUSES) > 1 else "")

        control_layout.addWidget(self.warehouse_container)

        add_btn_layout = QHBoxLayout()
        add_warehouse_btn = QPushButton("Добавить склад")
        add_warehouse_btn.clicked.connect(lambda: self.add_warehouse_row(""))
        add_warehouse_btn.setMaximumWidth(150)
        add_btn_layout.addWidget(add_warehouse_btn)
        add_btn_layout.addStretch()
        control_layout.addLayout(add_btn_layout)

        btn_layout = QVBoxLayout()

        self.fetch_btn = QPushButton("Получить данные из 1С")
        self.fetch_btn.clicked.connect(self.start_fetch)

        self.copy_btn = QPushButton("Скопировать данные в буфер")
        self.copy_btn.clicked.connect(self.copy_to_clipboard)

        self.open_folder_btn = QPushButton("Открыть папку с файлом")
        self.open_folder_btn.clicked.connect(self.open_file_folder)
        self.open_folder_btn.setEnabled(False)

        self.status_label = QLabel("Готов к работе | Загрузка списка складов...")

        btn_layout.addWidget(self.fetch_btn)
        btn_layout.addWidget(self.copy_btn)
        btn_layout.addWidget(self.open_folder_btn)
        btn_layout.addWidget(self.status_label)
        control_layout.addLayout(btn_layout)

        layout_data.addWidget(control_frame)

        self.data_text = QTextEdit()
        self.data_text.setReadOnly(True)
        self.data_text.setFontFamily("Consolas")
        layout_data.addWidget(self.data_text)

        tabs.addTab(tab_data, "Данные из 1С")

        tab_web = QWidget()
        layout_web = QVBoxLayout(tab_web)
        self.webview = QWebEngineView()
        self.webview.setUrl(QUrl(DEEPSEEK_URL))
        layout_web.addWidget(self.webview)
        tabs.addTab(tab_web, "DeepSeek Chat")

        self.signals = WorkerSignals()
        self.signals.status.connect(self.update_status)
        self.signals.report_ready.connect(self.on_report_ready)
        self.signals.warehouses_loaded.connect(self.on_warehouses_loaded)
        self.signals.error.connect(self.on_error)
        self.signals.finished.connect(self.on_finished)

        self.load_warehouses()

    def load_warehouses(self):
        thread = threading.Thread(target=self.fetch_warehouses_worker, daemon=True)
        thread.start()

    def fetch_warehouses_worker(self):
        try:
            stock_data = self.fetch_json(WAREHOUSE_URL)
            warehouse_names = [wh.get("warehouse", "") for wh in stock_data.get("warehouses", [])]
            self.signals.warehouses_loaded.emit(warehouse_names)
        except Exception as e:
            self.signals.error.emit(f"Ошибка загрузки складов: {e}")

    def on_warehouses_loaded(self, warehouse_names):
        self.all_warehouses = warehouse_names
        self.status_label.setText(f"Готов к работе | Загружено складов: {len(warehouse_names)}")

        for row in self.warehouse_combos:
            combo = row["combo"]
            current = combo.currentText()
            combo.clear()
            combo.addItems(warehouse_names)
            if current in warehouse_names:
                combo.setCurrentText(current)

    def add_warehouse_row(self, selected=""):
        if selected:
            for item in self.warehouse_combos:
                if item["combo"].currentText().strip() == selected.strip():
                    self.status_label.setText(f"Склад «{selected}» уже добавлен")
                    return

        row_widget = QWidget()
        row_layout = QHBoxLayout(row_widget)
        row_layout.setContentsMargins(0, 0, 0, 0)

        combo = QComboBox()
        combo.setEditable(True)
        combo.setMinimumWidth(300)
        if self.all_warehouses:
            combo.addItems(self.all_warehouses)
        if selected:
            combo.setCurrentText(selected)

        combo.currentTextChanged.connect(lambda: self.check_duplicate_warehouse(combo))
        row_layout.addWidget(combo)

        remove_btn = QPushButton("Удалить")
        remove_btn.setMaximumWidth(70)
        remove_btn.clicked.connect(lambda: self.remove_warehouse_row(row_widget))
        row_layout.addWidget(remove_btn)

        self.warehouse_combos.append({"widget": row_widget, "combo": combo})
        self.warehouse_layout.addWidget(row_widget)

    def check_duplicate_warehouse(self, changed_combo):
        selected_text = changed_combo.currentText().strip()
        if not selected_text:
            return

        count = 0
        for item in self.warehouse_combos:
            if item["combo"].currentText().strip() == selected_text:
                count += 1

        if count > 1:
            self.status_label.setText(f"Склад «{selected_text}» выбран несколько раз")
            changed_combo.setStyleSheet("QComboBox { border: 2px solid red; }")
        else:
            changed_combo.setStyleSheet("")
            current_warning = self.status_label.text()
            if current_warning.startswith("Склад «"):
                self.status_label.setText("Готов к работе")

    def remove_warehouse_row(self, row_widget):
        for i, item in enumerate(self.warehouse_combos):
            if item["widget"] == row_widget:
                self.warehouse_combos.pop(i)
                break
        self.warehouse_layout.removeWidget(row_widget)
        row_widget.deleteLater()

    def get_selected_warehouses(self):
        warehouses = []
        for item in self.warehouse_combos:
            text = item["combo"].currentText().strip()
            if text:
                warehouses.append(text)
        return warehouses if warehouses else DEFAULT_WAREHOUSES

    def get_date_from(self):
        return self.date_from.date().toString("dd.MM.yyyy")

    def get_date_to(self):
        return self.date_to.date().toString("dd.MM.yyyy")

    def update_status(self, msg):
        self.status_label.setText(msg)

    def on_report_ready(self, text, filename):
        self.last_data = text
        self.last_filename = filename
        self.data_text.setPlainText(text)
        self.open_folder_btn.setEnabled(True)

    def on_error(self, err):
        self.status_label.setText(f"Ошибка: {err}")

    def on_finished(self):
        self.fetch_btn.setEnabled(True)

    def has_duplicate_warehouses(self):
        selected = []
        for item in self.warehouse_combos:
            text = item["combo"].currentText().strip()
            if text:
                selected.append(text)
        return len(selected) != len(set(selected))

    def start_fetch(self):
        if self.has_duplicate_warehouses():
            self.status_label.setText("Устраните дубликаты складов перед загрузкой")
            return

        self.fetch_btn.setEnabled(False)
        self.open_folder_btn.setEnabled(False)
        self.data_text.clear()
        self.status_label.setText("Загрузка...")

        thread = threading.Thread(target=self.fetch_worker, daemon=True)
        thread.start()

    def fetch_worker(self):
        try:
            from_date = self.get_date_from()
            to_date = self.get_date_to()
            target_warehouses = self.get_selected_warehouses()

            self.signals.status.emit(f"Получение остатков ({', '.join(target_warehouses)})...")
            stock = self.fetch_json(WAREHOUSE_URL)

            self.signals.status.emit("Получение заказов...")
            orders = self.fetch_json(ORDERS_URL, {"from": from_date, "to": to_date})

            self.signals.status.emit("Формирование отчёта...")
            report = self.generate_report_text(stock, orders, from_date, to_date, target_warehouses)

            filename = f"analysis_data_{datetime.now().strftime('%Y%m%d_%H%M%S')}.txt"
            with open(filename, "w", encoding="utf-8") as f:
                f.write(report)

            self.signals.report_ready.emit(report, filename)
            self.signals.status.emit(f"Данные загружены. Файл: {filename}")

        except Exception as e:
            self.signals.error.emit(str(e))
        finally:
            self.signals.finished.emit()

    def fetch_json(self, url, params=None):
        auth = base64.b64encode(f"{LOGIN}:{PASSWORD}".encode()).decode()
        resp = requests.get(
            url,
            params=params,
            headers={"Authorization": f"Basic {auth}"},
            timeout=300
        )
        resp.raise_for_status()
        return resp.json()

    def generate_report_text(self, stock_data, orders_data, from_date, to_date, target_warehouses):
        lines = []
        lines.append("=" * 60)
        lines.append(f"ОСТАТКИ НА СКЛАДАХ: {', '.join(target_warehouses)}")
        lines.append("=" * 60 + "\n")

        stock_dict = {}
        total_stock = 0

        for wh in stock_data.get("warehouses", []):
            wh_name = wh.get("warehouse", "")
            if wh_name not in target_warehouses:
                continue

            lines.append(wh_name)
            lines.append("-" * 40)

            for item in wh.get("items", []):
                name = item.get("name", "")
                qty = item.get("total", 0)
                unit = item.get("unit", "шт")
                lines.append(f"  {name}: {qty} {unit}")

                if name not in stock_dict:
                    stock_dict[name] = {"qty": 0, "unit": unit}
                stock_dict[name]["qty"] += qty
                total_stock += 1

            lines.append("")

        lines.append("=" * 60)
        lines.append("СВОДКА ПО ОСТАТКАМ")
        lines.append("=" * 60)
        lines.append(f"Всего позиций: {total_stock}")
        lines.append(f"Уникальных материалов: {len(stock_dict)}\n")

        lines.append("ТОП-20 ПО КОЛИЧЕСТВУ:")
        top_items = sorted(stock_dict.items(), key=lambda x: abs(x[1]["qty"]), reverse=True)[:20]
        for name, data in top_items:
            lines.append(f"  {name[:60]}: {data['qty']} {data['unit']}")

        lines.append("\n" + "=" * 60)
        lines.append(f"ЗАКАЗЫ (период {from_date} - {to_date})")
        lines.append("=" * 60)

        orders_list = orders_data.get("orders", [])
        lines.append(f"Всего заказов: {len(orders_list)}\n")

        for order in orders_list:
            order_num = order.get("order_number", "???")
            order_date = order.get("order_date", "???")

            lines.append("-" * 50)
            lines.append(f"ЗАКАЗ №{order_num} от {order_date}")
            lines.append("-" * 50)

            for product in order.get("products", []):
                pname = product.get("product_name", "???")
                pqty = product.get("quantity_ordered", 0)
                punit = product.get("unit", "шт")
                product_status = product.get("product_status", "???")

                status_label = "[ТРЕБУЕТ ПРОИЗВОДСТВА]" if product_status == "Нужно произвести" else "[ГОТОВ]"
                lines.append(f"\n  {status_label} {pname}: {pqty} {punit} [{product_status}]")

                def write_components(comps, level=2):
                    for comp in comps:
                        name = comp.get("component_name", "")
                        if not name:
                            continue
                        qty = comp.get("required_quantity", 0)
                        unit = comp.get("unit", "шт")
                        indent = "    " * level
                        lines.append(f"{indent}|-- {name}: {qty} {unit}")
                        sub = comp.get("subcomponents", [])
                        if sub:
                            write_components(sub, level + 1)

                write_components(product.get("components", []))

        lines.append("\n" + "=" * 60)
        lines.append("ЛЕГЕНДА")
        lines.append("=" * 60)
        lines.append("[ПРОДУКТ] - товар")
        lines.append("|-- - компонент")
        lines.append("    |-- - субкомпонент")
        lines.append("[ТРЕБУЕТ ПРОИЗВОДСТВА] - товар требует обеспечения")
        lines.append("[ГОТОВ] - товар готов к отгрузке")

        return "\n".join(lines)

    def copy_to_clipboard(self):
        if self.last_data:
            QApplication.clipboard().setText(self.last_data)
            self.status_label.setText("Данные скопированы в буфер обмена")
        else:
            self.status_label.setText("Нет данных для копирования")

    def open_file_folder(self):
        if self.last_filename and os.path.exists(self.last_filename):
            os.startfile(os.path.dirname(os.path.abspath(self.last_filename)))


if __name__ == "__main__":
    app = QApplication(sys.argv)
    app.setWindowIcon(QIcon(resource_path("icon.ico")))
    window = MainWindow()
    window.show()
    sys.exit(app.exec())
