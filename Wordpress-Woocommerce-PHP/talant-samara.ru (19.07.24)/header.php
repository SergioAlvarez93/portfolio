<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset') ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="apple-touch-icon" sizes="76x76" href=<?= get_template_directory_uri() . "/favicons/apple-touch-icon.png" ?>>
    <link rel="icon" type="image/png" sizes="32x32" href=<?= get_template_directory_uri() . "/favicons/favicon-32x32.png" ?>>
    <link rel="icon" type="image/png" sizes="16x16" href=<?= get_template_directory_uri() . "/favicons/favicon-16x16.png" ?>>
    <link rel="manifest" href=<?= get_template_directory_uri() . "/favicons/site.webmanifest" ?>>
    <link rel="mask-icon" href=<?= get_template_directory_uri() . "/favicons/safari-pinned-tab.svg" ?> color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <?php wp_head(); ?>
    <title>Талант — детский Монтессори центр в Самаре</title>
</head>

<body>
    <div class="general-wrapper">
        <header class="<?php if (!is_front_page()) {
                            echo "header " . "not-a-front-page";
                        } else {
                            echo "header";
                        }  ?>">
            <div class="header__container">
                <div class="header-flexbox">
                    <a href=<?= get_home_url(); ?> class="header-flexbox__img-wrap">
                        <img src=<?= get_template_directory_uri() . "/img/logo/logo.svg" ?> class="header-flexbox__img" alt="logo" />
                    </a>
                    <div class="header__burger">
                        <span></span>
                    </div>
                    <nav class="navbar">
                        <?php wp_nav_menu([
                            'theme_location' => 'Navigation',
                            'container' => 'ul',
                            'menu_class' => 'navbar-flexbox',
                        ]); ?>
                    </nav>
                </div>
            </div>
        </header>