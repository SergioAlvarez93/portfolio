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
define( 'DB_NAME', 'talant' );

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
define( 'AUTH_KEY',         'Pe+<$N.1DT&O~,`jP.Ss{`D&#HLN{Hl^~6nGcUu* %~I3RsI^:kXnHzIY77!E/fP' );
define( 'SECURE_AUTH_KEY',  '`YHI5o+]D,|r(RO1$VX{()4v8`.^6S `P[5e=c~|78X>hO8<KvzHV3f,f268HEDL' );
define( 'LOGGED_IN_KEY',    '1`K+I.})A(^MI8ERN9>Ch6nipGu6tfxq:W=U LS_8i&0{X0w#JXZH/[p7|WB>!t_' );
define( 'NONCE_KEY',        '}Kp;Bg)Wx9o4QJ7?GNv.hYg-T~>/a)b=/*bVaL(!W?a7(}9fPp5L>5K$B>+(g>I$' );
define( 'AUTH_SALT',        'GX`^CF>3$T#G{-LXd~;n^PVJKAK,peJ]i;Y1H#0=3&ze?SlIhyy1Z}R(b(6og:0X' );
define( 'SECURE_AUTH_SALT', '?PuS }qQBT>vWediZe)n?pw}MQ:Y,>qeJ=6#(X8I{Q<TrAWNj}n%|zOUf{-gb[+M' );
define( 'LOGGED_IN_SALT',   '+T*_0Hd;pA`O`S!w&y<2# Z+rDEpw%*!zW)45Pe(M.L+.5@=;D|cOi!ElW>Ve#g,' );
define( 'NONCE_SALT',       '~$*8par!]6^UK@`SWu~_f+{2emA,xdg14wWcWNft5r#6+ @m;J;@yIIV=VaCZ-d?' );

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
