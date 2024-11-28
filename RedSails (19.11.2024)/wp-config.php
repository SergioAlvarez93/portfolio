<?php
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
define( 'DB_NAME', 'land' );

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
define( 'AUTH_KEY',         '87iq~cB/pFA~1_C&AFl*Qxq[6~~-!g?Q#**2FS=yJ3OQb.8o)G9EX=%ovRU$w61<' );
define( 'SECURE_AUTH_KEY',  '&$,`Et8+]{M@p-u0CimY@SX(cVh7ENRFlQp`&BR$g0:s|@+L`Q%A|,(y.!?/VqRP' );
define( 'LOGGED_IN_KEY',    'vtP|l_5t6@7@T6_}7Jb+!MyEZuHSK!BDhf6og,Ha2%w%W;OOIv!Igy>o?SkON3<u' );
define( 'NONCE_KEY',        '&>r=Zb]|oO3n6DqQ;c?xLogeJ*>`<nO|C>vF.#WLt%<;bCae-9XUeK/romS%Q|v=' );
define( 'AUTH_SALT',        '.0slvXrE<<b hH{V&>pUfyOM?)llKBM=vTIjenR{?wnD%%.jM$Y&?c@d[!D2Og_E' );
define( 'SECURE_AUTH_SALT', '6%elDDom[@_I54v>^#BBEGk$2j_vICOyHiT09qeUEJlT^O* 56@7PXqrT]aPYp8x' );
define( 'LOGGED_IN_SALT',   'vaDG6XdF%F8a2l PMKf&hS-Z7<#Nimgt{(y`Se WWt(uRil%_b9k+TH[JDFI}xXH' );
define( 'NONCE_SALT',       'p.J`q4Gm_twk_FW3#Fu)%-$QGQRediIkB_)CgR:SP~u=B:u/nPR/0lS^fI+22eej' );

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
