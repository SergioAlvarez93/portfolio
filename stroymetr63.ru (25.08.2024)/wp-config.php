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
define( 'DB_NAME', 'stroymetr63' );

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
define( 'AUTH_KEY',         '>O})!mG)IEw`#>0QM?PKqj~# L50<Ys${(Z(fss<80?mmhEsv1tt{)uoYn-RVr[6' );
define( 'SECURE_AUTH_KEY',  'v9}DS@EGOx$Sfh8}}|L^M.jZ,^!|M]=s`#X<-pxjdFla$nTXTCEl)K@Ri/RC;L2?' );
define( 'LOGGED_IN_KEY',    'N;YXUt&5+s#rJCO(F |XT|^p%,T9!^tQD5-HT7jL-EHOxcXXUu:rVw+zVpM|3` j' );
define( 'NONCE_KEY',        'dr!AF&gYB$7{zEBN;<]MUeY<*X!7~ATq_52yWpyTF)wt.Jl*F`c2.>,C41RyjqSp' );
define( 'AUTH_SALT',        'tFG!8DW_o&>l|CV9tvS~=pMSKB<sAeGO7{?k,];M3K.$ow0ah!CkOO~|G@wjYLMZ' );
define( 'SECURE_AUTH_SALT', 'gd7W=ul~aoHI<3dhR|>!>v^nF0n3QAk8:x1;<k4*w,ur9.eX6FPUD$yY13>S|hfN' );
define( 'LOGGED_IN_SALT',   'm)D<9kmxNvvG~FZ?XaHDK]`lIp@c%EL`H5D:WW<f#oG&m.N{[}n|aHKi[f]r)v&D' );
define( 'NONCE_SALT',       'mtmX%Sy?Ld5aihY[SYuEj%rZgLFwsbf.9{zau8I(gJ|WCu >FG8<f@ife6^UD1++' );

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
