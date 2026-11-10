<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset') ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
    <title>HOKO</title>
</head>

<body>
    <div class="general-wrapper">
        <header class="header">
            <div class="container">
                <div class="header-grid">
                    <?php if ($value = get_field('logo', 2)) { ?>
                        <a href="<?= get_home_url(); ?>" class="logo-wrap">
                            <img src="<?= $value['url']; ?>" class="logo" alt="<?= $value['alt'] ?>">
                        </a>
                    <?php } ?>
                    <nav class="navigation">
                        <?php wp_nav_menu([
                            'theme_location' => 'Navigation',
                            'container' => null,
                            'menu_class' => 'header-menu',
                            'menu_id' => 'nav-ul'
                        ]); ?>
                    </nav>
                    <a href="<?= get_home_url(); ?>/#request" class="classic-btn header-btn">Обратная связь</a>
                    <div class="burger-btn">
                        <span class="burger-btn__dash"></span>
                    </div>
                </div>
            </div>
        </header>