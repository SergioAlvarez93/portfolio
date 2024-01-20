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
define( 'DB_NAME', 'awax-dv_bd' );

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
define( 'AUTH_KEY',         'f[.XKJ`.D8%RNFV.^8Y^zq^r:UZ>zl LI7[Xo^g~KQ0`Ff>t`Qn*RYn%fYw%176&' );
define( 'SECURE_AUTH_KEY',  '@xET~WP[Z+pip6Md*AGrwsy6Nopi-XlkWbiEDEf4(tiR(wNiAR# F2#>i!3R{|<M' );
define( 'LOGGED_IN_KEY',    'g*PP=7XaPqTAn4W^y{_Q~-ZWh^[@+|re<0!bf?#*5Y.-0V+XP#nd!-2qq!pRe1E4' );
define( 'NONCE_KEY',        'M><J|Cxlo(cH{[&O5@S.KB4:0eB1+^q86%*6nL/_%uN5HR-GEZ2QBQd3Sfr}p#WF' );
define( 'AUTH_SALT',        'jQK^H/~q,?v$00L&wW[1Q<#v]-!u4rAhyw|$a+1_`;aFe#oCd}5amyTA}b]6d}+~' );
define( 'SECURE_AUTH_SALT', 'xVs_pv85}!Gth-*Vs-K3Z(Ejmp!@ALrm# W1hg|D<&,.WMSIb[7ms]*WrbQ]UK_c' );
define( 'LOGGED_IN_SALT',   'vBF5x[v*1@wy.)1$p!&VZ%Z3-F(W5<y3bLn3{d9$^qkZI/3=L<?LSztNi.(X^2V3' );
define( 'NONCE_SALT',       'Q:tDATX<2c G2;X|V[23z!N,(jh1`v3AwJv#JkI `FT^l,&bWNH&b1gFvG){X2!Q' );

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
