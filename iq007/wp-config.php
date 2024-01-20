<?php

// BEGIN iThemes Security - Не меняйте и не удаляйте эту строку
// iThemes Security Config Details: 2
define( 'DISALLOW_FILE_EDIT', true ); // Отключить редактор файлов - Безопасность > Настройки > Подстройки WordPress > Редактор файлов
// END iThemes Security - Не меняйте и не удаляйте эту строку

define( 'ITSEC_ENCRYPTION_KEY', 'aztST2okWiU3MXVrN008WGNpL2RILk1uK3h1enBbKi5HME0uYXxfRXguWl5sMWlPbyR3SE46NyZNMXZ3MThWfg==' );

/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе установки.
 * Необязательно использовать веб-интерфейс, можно скопировать файл в "wp-config.php"
 * и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки базы данных
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://ru.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Параметры базы данных: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'testing-design_bd' );

/** Имя пользователя базы данных */
define( 'DB_USER', 'root' );

/** Пароль к базе данных */
define( 'DB_PASSWORD', '' );

/** Имя сервера базы данных */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу. Можно сгенерировать их с помощью
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}.
 *
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными.
 * Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '=t&pbi`fh[hW303xxAZHDOyHIxy-]g-TgI3Urb4QlpOS 0@jeAb]tW.x,?-s:&r7' );
define( 'SECURE_AUTH_KEY',  '($PcY-*c(y%4vfwSaHr^[F#,w0_o/kK~4}gTsSuy$%=k_Oz?J2G ?|iu@S|(1)A(' );
define( 'LOGGED_IN_KEY',    'e,J/i{7XXUI^~-P}ZyOk.OD(DN[*@e-/B*M_WBD<{slH~H#doN+B94vembtLvZAZ' );
define( 'NONCE_KEY',        'CZ{%[goNB}UA+olPU[%:gvSx+(}:.S;*v<r-WS+DH>Q~`ch~N,_6l)yWx>TqG:vO' );
define( 'AUTH_SALT',        '^i~ftE w[b$-|!? @@$+n2WTN/R1wK~9npotPe)>6V^@jl{D%lQg34FtNr2>rLj}' );
define( 'SECURE_AUTH_SALT', '@#Qi@20fm%!sd3[@p7`2~&h9jc%Ek@.;5{2lO^FR=IF1(BO|IN;ftrq/!XvqmTU>' );
define( 'LOGGED_IN_SALT',   '=!HwF0rc$G.zKuu8C_6H{.!]LEun4YCkC+uRfn&lpt^-k>7D#f%}rPFw0yfDc3V^' );
define( 'NONCE_SALT',       'w:kY]SbQ)C y`V)F;|m> uN+mi,_77[21Zqq(vx_*O1JAc#ru}#=`<lQbt8v/n{E' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Произвольные значения добавляйте между этой строкой и надписью "дальше не редактируем". */



/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';
