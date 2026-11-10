import os
import shutil
import zipfile
import tkinter as tk
from tkinter import ttk, filedialog, messagebox
import subprocess
import time
import random
import string
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import Select
from selenium.webdriver.chrome.service import Service as ChromeService
from webdriver_manager.chrome import ChromeDriverManager
from selenium.common.exceptions import NoSuchElementException
from selenium.webdriver.chrome.options import Options
import logging
import wget

                   
logging.basicConfig(
    filename='wordpress_installer.log',
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s'
)

class WordPressInstaller:
    def __init__(self):
        self.root = tk.Tk()
        self.root.title("WordPress Installer")
        self.root.geometry("600x400")
        
                   
        self.project_name = tk.StringVar()
        self.ospanel_path = tk.StringVar()
        self.project_path = tk.StringVar()
        self.template_path = tk.StringVar()
        self.plugins_path = tk.StringVar()
        self.email = tk.StringVar()
        self.email_password = tk.StringVar()
        
                      
        self.progress = ttk.Progressbar(self.root, orient="horizontal", length=400, mode="determinate")
        
                     
        self.status_var = tk.StringVar()
        self.status_var.set("Готов к работе")
        
             
        self.create_widgets()
        
                             
        self.root.columnconfigure(0, weight=1)
        self.root.rowconfigure(0, weight=1)
    
    def create_widgets(self):
        frame = tk.Frame(self.root, padx=10, pady=10)
        frame.grid(row=0, column=0, sticky="nsew")
        
                      
        tk.Label(frame, text="Название проекта (латиницей):").grid(row=0, column=0, sticky="w", pady=2)
        tk.Entry(frame, textvariable=self.project_name).grid(row=0, column=1, sticky="ew", pady=2)
        
                      
        tk.Label(frame, text="Путь к OSPanel:").grid(row=1, column=0, sticky="w", pady=2)
        tk.Entry(frame, textvariable=self.ospanel_path).grid(row=1, column=1, sticky="ew", pady=2)
        tk.Button(frame, text="Обзор", command=lambda: self.browse_path(self.ospanel_path)).grid(row=1, column=2, pady=2)
        
                      
        tk.Label(frame, text="Путь для проекта:").grid(row=2, column=0, sticky="w", pady=2)
        tk.Entry(frame, textvariable=self.project_path).grid(row=2, column=1, sticky="ew", pady=2)
        tk.Button(frame, text="Обзор", command=lambda: self.browse_path(self.project_path)).grid(row=2, column=2, pady=2)
        
                       
        tk.Label(frame, text="Путь к шаблону:").grid(row=3, column=0, sticky="w", pady=2)
        tk.Entry(frame, textvariable=self.template_path).grid(row=3, column=1, sticky="ew", pady=2)
        tk.Button(frame, text="Обзор", command=lambda: self.browse_path(self.template_path)).grid(row=3, column=2, pady=2)
        
                      
        tk.Label(frame, text="Путь к плагинам:").grid(row=4, column=0, sticky="w", pady=2)
        tk.Entry(frame, textvariable=self.plugins_path).grid(row=4, column=1, sticky="ew", pady=2)
        tk.Button(frame, text="Обзор", command=lambda: self.browse_path(self.plugins_path)).grid(row=4, column=2, pady=2)
        
               
        tk.Label(frame, text="Почта:").grid(row=5, column=0, sticky="w", pady=2)
        tk.Entry(frame, textvariable=self.email).grid(row=5, column=1, sticky="ew", pady=2)
        
                        
        tk.Label(frame, text="Пароль от почты:").grid(row=6, column=0, sticky="w", pady=2)
        tk.Entry(frame, textvariable=self.email_password, show="*").grid(row=6, column=1, sticky="ew", pady=2)
        
                      
        tk.Label(frame, textvariable=self.status_var).grid(row=7, column=0, columnspan=3, pady=5)
        
                      
        self.progress.grid(row=8, column=0, columnspan=3, pady=5, sticky="ew")
        
                        
        tk.Button(frame, text="Установить", command=self.install).grid(row=9, column=1, pady=10)
        
                                
        frame.columnconfigure(1, weight=1)
    
    def browse_path(self, path_var):
        path = filedialog.askdirectory()
        if path:
            path_var.set(path)
    
    def update_status(self, message, progress=None):
        self.status_var.set(message)
        if progress is not None:
            self.progress["value"] = progress
        self.root.update_idletasks()
    
    def check_ospanel(self):
        self.update_status("Проверка и перезапуск OSPanel...", 5)
        ospanel_exe = os.path.join(self.ospanel_path.get(), "OSPanel.exe")
        if not os.path.exists(ospanel_exe):
            logging.error("OSPanel.exe не найден")
            messagebox.showerror("Ошибка", "OSPanel.exe не найден по указанному пути!")
            return False
        
                                          
        processes = ["OSPanel.exe", "httpd.exe", "mysqld.exe"]
        for process in processes:
            try:
                subprocess.call(f'taskkill /F /IM {process}', shell=True)
                self.update_status(f"Процесс {process} завершен", 6)
            except subprocess.CalledProcessError:
                self.update_status(f"Процесс {process} не найден, продолжаем...", 6)
        
                                            
        time.sleep(3)
        
                           
        try:
            self.update_status("Запуск OSPanel...", 10)
            subprocess.Popen(ospanel_exe)
            time.sleep(5)                                          
            self.update_status("OSPanel запущен", 12)
            return True
        except Exception as e:
            logging.error(f"Не удалось запустить OSPanel: {str(e)}")
            messagebox.showerror("Ошибка", f"Не удалось запустить OSPanel: {e}")
            return False
    
    def check_server_status(self):
        self.update_status("Проверка статуса сервера...", 93)
        chrome_options = Options()
        chrome_options.add_argument("--no-sandbox")
        chrome_options.add_argument("--disable-extensions")
        chrome_options.add_argument("--disable-gpu")
        chrome_options.add_argument("--headless")
        
        try:
            driver_path = ChromeDriverManager().install()
            driver = webdriver.Chrome(service=ChromeService(driver_path), options=chrome_options)
            driver.get(f"http://{self.project_name.get()}.local")
            time.sleep(2)                                   
            if "WordPress" in driver.title:
                self.update_status("Сервер работает", 94)
                logging.info("Сервер успешно отвечает")
                return True
            else:
                logging.error("Сервер не отвечает корректно")
                return False
        except Exception as e:
            logging.error(f"Ошибка при проверке сервера: {str(e)}")
            return False
        finally:
            driver.quit()
    
    def create_project_folder(self):
        self.update_status("Создание папки проекта...", 15)
        project_folder = os.path.join(self.project_path.get(), f"{self.project_name.get()}.local")
        if not os.path.exists(project_folder):
            os.makedirs(project_folder)
            logging.info(f"Папка проекта создана: {project_folder}")
        else:
            logging.info(f"Папка проекта уже существует: {project_folder}")
        return project_folder
    
    def download_wordpress(self):
        wp_url = "https://ru.wordpress.org/latest-ru_RU.zip"
        wp_zip_path = os.path.join(os.getcwd(), "latest-ru_RU.zip")
        logging.info(f"Путь для скачивания WordPress: {wp_zip_path}")
        
        if os.path.exists(wp_zip_path):
            os.remove(wp_zip_path)
            logging.info("Существующий ZIP-файл удалён")
        
        retries = 3
        for attempt in range(retries):
            try:
                self.update_status(f"Скачивание WordPress (попытка {attempt + 1}/{retries})...", 20 + (attempt * 10))
                logging.info(f"Скачивание WordPress с помощью wget: {wp_url}")
                
                                               
                wget.download(wp_url, out=wp_zip_path)
                
                                            
                if not os.path.exists(wp_zip_path):
                    raise Exception("Файл не был скачан")
                
                file_size = os.path.getsize(wp_zip_path)
                logging.info(f"Скачивание завершено. Размер файла: {file_size} байт")
                
                                               
                if file_size < 1024:                                      
                    raise Exception("Скачанный файл слишком мал. Возможно, скачивание не удалось.")
                
                self.update_status("WordPress успешно скачан", 50)
                return wp_zip_path
            except Exception as e:
                logging.error(f"Ошибка при скачивании WordPress (попытка {attempt + 1}/{retries}): {str(e)}")
                if attempt < retries - 1:
                    logging.info("Повторная попытка через 3 секунды...")
                    time.sleep(3)
                else:
                    raise Exception(f"Не удалось скачать WordPress после {retries} попыток: {str(e)}")
    
    def extract_wordpress(self, zip_path, project_folder):
        self.update_status("Распаковка WordPress...", 50)
        
        try:
            with zipfile.ZipFile(zip_path, 'r') as zip_ref:
                total_files = len(zip_ref.infolist())
                extracted_files = 0
                
                for file in zip_ref.infolist():
                    if file.filename.startswith('wordpress/'):
                        zip_ref.extract(file, os.path.join(project_folder, "temp"))
                        extracted_files += 1
                        progress = 50 + (extracted_files / total_files) * 20
                        self.progress["value"] = progress
                        self.root.update_idletasks()
        except zipfile.BadZipFile as e:
            logging.error(f"Ошибка при распаковке ZIP-файла: {str(e)}")
            raise Exception(f"Скачанный ZIP-файл повреждён или пустой: {str(e)}")
        
                                                          
        wordpress_folder = os.path.join(project_folder, "temp", "wordpress")
        for item in os.listdir(wordpress_folder):
            src = os.path.join(wordpress_folder, item)
            dst = os.path.join(project_folder, item)
            if os.path.isdir(src):
                shutil.copytree(src, dst)
            else:
                shutil.copy2(src, dst)
        
                 
        shutil.rmtree(os.path.join(project_folder, "temp"))
        os.remove(zip_path)
        self.update_status("WordPress распакован", 70)
    
    def create_osp_folder(self, project_folder):
        self.update_status("Создание служебных папок...", 75)
        osp_folder = os.path.join(project_folder, ".osp")
        public_folder = os.path.join(project_folder, "public")
        
        os.makedirs(osp_folder, exist_ok=True)
        os.makedirs(public_folder, exist_ok=True)
        logging.info(f"Служебные папки созданы: {osp_folder}, {public_folder}")
        
        project_ini_path = os.path.join(osp_folder, "project.ini")
        logging.info(f"Создание файла project.ini по пути: {project_ini_path}")
        
        try:
            with open(project_ini_path, "w", encoding="utf-8") as f:
                if f is None:
                    logging.error("Не удалось открыть файл project.ini для записи")
                    raise Exception("Не удалось открыть файл project.ini для записи")
                f.write(f"[{self.project_name.get()}.local]\nphp_engine = PHP-7.4")
            logging.info("Файл project.ini успешно создан")
            time.sleep(0.1)                        
        except Exception as e:
            logging.error(f"Ошибка при создании project.ini: {str(e)}")
            raise Exception(f"Ошибка при создании project.ini: {str(e)}")
    
    def create_database(self):
        self.update_status("Создание базы данных...", 80)
        chrome_options = Options()
        chrome_options.add_argument("--no-sandbox")
        chrome_options.add_argument("--disable-extensions")
        chrome_options.add_argument("--disable-gpu")
        
        try:
            driver_path = ChromeDriverManager().install()
            logging.info(f"Используется ChromeDriver: {driver_path}")
            driver = webdriver.Chrome(service=ChromeService(driver_path), options=chrome_options)
            
            chrome_version = driver.capabilities['browserVersion']
            logging.info(f"Версия Chrome: {chrome_version}")
            
            driver.get("https://phpmyadmin.local/")
            time.sleep(2)                              
            
            username = driver.find_element(By.NAME, "pma_username")
            time.sleep(2)                               
            username.send_keys("root")
            time.sleep(2)                            
            
            go_button = driver.find_element(By.ID, "input_go")
            time.sleep(2)                             
            go_button.click()
            time.sleep(2)                                   
            
            try:
                create_db_link = driver.find_element(By.CSS_SELECTOR, 'a[href="index.php?route=/server/databases"]')
                time.sleep(2)                               
                create_db_link.click()
                time.sleep(2)                     
            except NoSuchElementException:
                logging.error("Ссылка для создания БД не найдена по href")
                try:
                    hover_link = driver.find_element(By.CSS_SELECTOR, 'a.hover_show_full')
                    time.sleep(2)                               
                    hover_link.click()
                    time.sleep(2)                     
                except NoSuchElementException:
                    logging.error("Запасная ссылка hover_show_full также не найдена")
                    raise Exception("Не удалось найти ссылку для создания базы данных")
            
            db_name = f"{self.project_name.get()}_db"
            db_input = driver.find_element(By.ID, "text_create_db")
            time.sleep(2)                               
            db_input.send_keys(db_name)
            time.sleep(2)                            
            
            collation_select = Select(driver.find_element(By.NAME, "db_collation"))
            time.sleep(2)                               
            collation_select.select_by_value("utf8mb4_general_ci")
            time.sleep(2)                               
            
            button_go = driver.find_element(By.ID, "buttonGo")
            time.sleep(2)                             
            button_go.click()
            time.sleep(2)                                   
            
            self.update_status("База данных создана", 85)
            return db_name
        except Exception as e:
            driver.save_screenshot("phpmyadmin_error.png")
            with open("phpmyadmin_page_source.html", "w", encoding="utf-8") as f:
                f.write(driver.page_source)
            logging.error(f"Ошибка при создании БД: {str(e)}")
            messagebox.showerror("Ошибка", f"Ошибка при создании БД: {str(e)}")
            return None
        finally:
            driver.quit()
    
    def restart_ospanel(self):
        self.update_status("Перезапуск OSPanel...", 90)
        try:
            processes = ["OSPanel.exe", "httpd.exe", "mysqld.exe"]
            for process in processes:
                try:
                    subprocess.call(f'taskkill /F /IM {process}', shell=True)
                except subprocess.CalledProcessError:
                    self.update_status(f"Процесс {process} не найден, продолжаем...", 90)
            
            time.sleep(3)
            ospanel_exe = os.path.join(self.ospanel_path.get(), "OSPanel.exe")
            subprocess.Popen(ospanel_exe)
            time.sleep(5)                                          
            self.update_status("OSPanel перезапущен", 92)
            
            if not self.check_server_status():
                raise Exception("Сервер не отвечает после перезапуска")
        except Exception as e:
            logging.error(f"Ошибка при перезапуске OSPanel: {str(e)}")
            messagebox.showerror("Ошибка", f"Не удалось перезапустить OSPanel: {e}")
    
    def install_wordpress(self, db_name):
        self.update_status("Установка WordPress...", 95)
        chrome_options = Options()
        chrome_options.add_argument("--no-sandbox")
        chrome_options.add_argument("--disable-extensions")
        chrome_options.add_argument("--disable-gpu")
        chrome_options.add_argument("--headless")
        
        try:
            driver_path = ChromeDriverManager().install()
            logging.info(f"Используется ChromeDriver: {driver_path}")
            driver = webdriver.Chrome(service=ChromeService(driver_path), options=chrome_options)
            
            chrome_version = driver.capabilities['browserVersion']
            logging.info(f"Версия Chrome: {chrome_version}")
            
            self.update_status("Загрузка страницы установки...", 95)
            logging.info(f"Загрузка страницы: http://{self.project_name.get()}.local")
            driver.get(f"http://{self.project_name.get()}.local")
            time.sleep(2)                                   
            
            button_large = driver.find_element(By.CLASS_NAME, "button-large")
            time.sleep(1)                                  
            button_large.click()
            time.sleep(2)                        
            
            self.update_status("Заполнение формы базы данных...", 96)
            dbname_field = driver.find_element(By.NAME, "dbname")
            time.sleep(1)                                  
            dbname_field.send_keys(db_name)
            time.sleep(2)                               
            
            uname_field = driver.find_element(By.NAME, "uname")
            time.sleep(1)                                  
            uname_field.send_keys("root")
            time.sleep(2)                               
            
            dbhost_field = driver.find_element(By.NAME, "dbhost")
            time.sleep(1)                                  
            dbhost_field.clear()
            time.sleep(1)                               
            dbhost_field.send_keys("MySQL-8.0")
            time.sleep(2)                               
            
            submit_button = driver.find_element(By.NAME, "submit")
            time.sleep(1)                                  
            submit_button.click()
            time.sleep(3)                                 
            
            self.update_status("Запуск установки WordPress...", 97)
            button_large = driver.find_element(By.CLASS_NAME, "button-large")
            time.sleep(1)                                  
            button_large.click()
            time.sleep(2)                        
            
            self.update_status("Заполнение формы настроек...", 98)
            title_field = driver.find_element(By.NAME, "weblog_title")
            time.sleep(1)                                  
            title_field.send_keys(self.project_name.get())
            time.sleep(2)                               
            
            username_field = driver.find_element(By.NAME, "user_name")
            time.sleep(1)                                  
            username_field.send_keys(f"{self.project_name.get()}Admin")
            time.sleep(2)                               
            
            chars = string.ascii_letters + string.digits + "!@#$%^&*()"
            admin_password = ''.join(random.choice(chars) for _ in range(16))
            password_field = driver.find_element(By.NAME, "admin_password")
            time.sleep(1)                                  
            password_field.clear()
            time.sleep(1)                               
            password_field.send_keys(admin_password)
            time.sleep(2)                               
            
            email_field = driver.find_element(By.NAME, "admin_email")
            time.sleep(1)                                  
            email_field.send_keys(self.email.get())
            time.sleep(2)                               
            
            submit_button = driver.find_element(By.NAME, "Submit")
            time.sleep(1)                                  
            submit_button.click()
            time.sleep(3)                                 
            
            self.update_status("WordPress установлен", 100)
            logging.info(f"Установка WordPress завершена. Пароль администратора: {admin_password}")
            return admin_password
        except Exception as e:
            driver.save_screenshot("wordpress_install_error.png")
            with open("wordpress_install_page_source.html", "w", encoding="utf-8") as f:
                f.write(driver.page_source)
            logging.error(f"Ошибка при установке WordPress: {str(e)}\nURL: {driver.current_url}")
            messagebox.showerror("Ошибка", f"Ошибка при установке WordPress: {str(e)}\nПроверьте wordpress_install_error.png и wordpress_install_page_source.html")
            return None
        finally:
            driver.quit()
    
    def setup_theme(self, project_folder):
        self.update_status("Настройка темы...", 100)
        themes_path = os.path.join(project_folder, "wp-content", "themes")
        
        for item in os.listdir(themes_path):
            item_path = os.path.join(themes_path, item)
            if item != "index.php":
                if os.path.isdir(item_path):
                    shutil.rmtree(item_path)
                else:
                    os.remove(item_path)
        
        theme_folder = os.path.join(themes_path, self.project_name.get())
        os.makedirs(theme_folder, exist_ok=True)
        logging.info(f"Папка темы создана: {theme_folder}")
        
        if os.path.exists(self.template_path.get()):
            for item in os.listdir(self.template_path.get()):
                src = os.path.join(self.template_path.get(), item)
                dst = os.path.join(theme_folder, item)
                if os.path.isdir(src):
                    shutil.copytree(src, dst)
                else:
                    shutil.copy2(src, dst)
            logging.info(f"Шаблон скопирован в: {theme_folder}")
            time.sleep(0.1)                        
        
        style_css = os.path.join(theme_folder, "style.css")
        if os.path.exists(style_css):
            logging.info(f"Редактирование style.css по пути: {style_css}")
            with open(style_css, "r", encoding="utf-8") as f:
                content = f.read()
            content = content.replace("YourTheme", self.project_name.get())
            try:
                with open(style_css, "w", encoding="utf-8") as f:
                    if f is None:
                        logging.error("Не удалось открыть style.css для записи")
                        raise Exception("Не удалось открыть style.css для записи")
                    f.write(content)
                logging.info("style.css успешно отредактирован")
                time.sleep(0.1)                        
            except Exception as e:
                logging.error(f"Ошибка при записи в style.css: {str(e)}")
                raise Exception(f"Ошибка при записи в style.css: {str(e)}")
        
        header_php = os.path.join(theme_folder, "header.php")
        if os.path.exists(header_php):
            logging.info(f"Редактирование header.php по пути: {header_php}")
            with open(header_php, "r", encoding="utf-8") as f:
                content = f.read()
            content = content.replace("YourTheme", self.project_name.get())
            try:
                with open(header_php, "w", encoding="utf-8") as f:
                    if f is None:
                        logging.error("Не удалось открыть header.php для записи")
                        raise Exception("Не удалось открыть header.php для записи")
                    f.write(content)
                logging.info("header.php успешно отредактирован")
                time.sleep(0.1)                        
            except Exception as e:
                logging.error(f"Ошибка при записи в header.php: {str(e)}")
                raise Exception(f"Ошибка при записи в header.php: {str(e)}")
    
    def setup_plugins(self, project_folder):
        self.update_status("Установка плагинов...", 100)
        plugins_path = os.path.join(project_folder, "wp-content", "plugins")
        
        for item in os.listdir(plugins_path):
            item_path = os.path.join(plugins_path, item)
            if item != "index.php":
                if os.path.isdir(item_path):
                    shutil.rmtree(item_path)
                else:
                    os.remove(item_path)
        
        if os.path.exists(self.plugins_path.get()):
            for item in os.listdir(self.plugins_path.get()):
                src = os.path.join(self.plugins_path.get(), item)
                dst = os.path.join(plugins_path, item)
                if os.path.isdir(src):
                    shutil.copytree(src, dst)
                else:
                    shutil.copy2(src, dst)
            logging.info(f"Плагины скопированы в: {plugins_path}")
            time.sleep(0.1)                        
    
    def create_access_file(self, project_folder, db_name, admin_password):
        self.update_status("Создание файла с доступом...", 100)
        theme_folder = os.path.join(project_folder, "wp-content", "themes", self.project_name.get())
        
        if not os.path.exists(theme_folder):
            logging.error(f"Папка темы не существует: {theme_folder}")
            raise Exception(f"Папка темы не существует: {theme_folder}")
        
        access_file = os.path.join(theme_folder, "access.txt")
        logging.info(f"Создание файла access.txt по пути: {access_file}")
        logging.info(f"admin_password: {admin_password}")
        
        if admin_password is None:
            logging.error("admin_password is None перед созданием access.txt")
            raise Exception("admin_password не был получен. Установка WordPress не завершена.")
        
        try:
            with open(access_file, "w", encoding="utf-8") as f:
                if f is None:
                    logging.error("Не удалось открыть access.txt для записи")
                    raise Exception("Не удалось открыть access.txt для записи")
                f.write(f"Email: {self.email.get()}\n")
                f.write(f"Email Password: {self.email_password.get()}\n")
                f.write(f"Admin Login: {self.project_name.get()}Admin\n")
                f.write(f"Admin Password: {admin_password}\n")
                f.write(f"Database Name: {db_name}\n")
            logging.info("access.txt успешно создан")
            time.sleep(0.1)                        
        except Exception as e:
            logging.error(f"Ошибка при создании access.txt: {str(e)}")
            raise Exception(f"Ошибка при создании access.txt: {str(e)}")
    
    def install(self):
        try:
            if not all([self.project_name.get(), self.ospanel_path.get(), self.project_path.get(), 
                        self.template_path.get(), self.plugins_path.get(), self.email.get()]):
                messagebox.showerror("Ошибка", "Заполните все обязательные поля!")
                return
            
            if not self.check_ospanel():
                return
            
            project_folder = self.create_project_folder()
            
            wp_zip = self.download_wordpress()
            
            self.extract_wordpress(wp_zip, project_folder)
            
            self.create_osp_folder(project_folder)
            
            db_name = self.create_database()
            if not db_name:
                return
            
            self.restart_ospanel()
            
            admin_password = self.install_wordpress(db_name)
            if not admin_password:
                logging.error("admin_password is None после install_wordpress")
                raise Exception("Не удалось установить WordPress: admin_password не был получен")
            
            self.setup_theme(project_folder)
            
            self.setup_plugins(project_folder)
            
            self.create_access_file(project_folder, db_name, admin_password)
            
            messagebox.showinfo("Успех", "WordPress успешно установлен и настроен!")
        except Exception as e:
            logging.error(f"Ошибка установки: {str(e)}")
            messagebox.showerror("Ошибка", f"Произошла ошибка: {str(e)}")

if __name__ == "__main__":
    app = WordPressInstaller()
    app.root.mainloop()