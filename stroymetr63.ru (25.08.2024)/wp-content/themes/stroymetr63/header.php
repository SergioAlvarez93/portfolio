<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset') ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="apple-touch-icon" sizes="180x180" href=<?= get_template_directory_uri() . "/favicons/apple-touch-icon.png" ?>>
    <link rel="icon" type="image/png" sizes="32x32" href=<?= get_template_directory_uri() . "/favicons/favicon-32x32.png" ?>>
    <link rel="icon" type="image/png" sizes="16x16" href=<?= get_template_directory_uri() . "/favicons/favicon-16x16.png" ?>>
    <link rel="manifest" href=<?= get_template_directory_uri() . "/favicons/site.webmanifest" ?>>
    <link rel="mask-icon" href=<?= get_template_directory_uri() . "/favicons/safari-pinned-tab.svg" ?> color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#b6b6b6">
    <?php wp_head(); ?>
    <title>СтройМетр63</title>
</head>

<body>
    <div class="general-wrap">
        <header class="header-container sticky-header">
            <div class="header">
                <a href=<?= get_home_url(); ?> class="header__logo">
                    <img src=<?= get_template_directory_uri() . "/img/logo.svg" ?> alt="Logo" class="logo__image" />
                </a>
                <button class="burger-menu">&#9776;</button>
                <nav class="header__navigation">
                    <?php if ($contact = get_field('tel', 'options')) { ?>
                        <a class="navigation__item navigation__item--big" href="tel:<?php echo preg_replace('/[^0-9\+]/', '', $contact); ?>"><?php echo $contact; ?></a>
                    <?php } ?>
                    <?php wp_nav_menu([
                        'theme_location' => 'Navigation',
                        'container' => null,
                        'menu_class' => null,
                        'menu_id' => 'nav-ul'
                    ]); ?>
                </nav>
            </div>
        </header>