<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset') ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
    <title>biocordsport</title>
</head>

<body>
    <div class="general-wrapper">
        <header class="header">
            <div class="container">
                <div class="header-grid">
                    <?php if ($value = get_field('call_icon', 'option')) { ?>
                        <?php
                        if (have_rows('tel', 'option')) { ?>
                            <?php while (have_rows('tel', 'option')) {
                                the_row(); ?>
                                <?php if (get_row_index() === 1) { ?>
                                    <a href="tel:<?= preg_replace('/[^0-9\+]/', '', get_sub_field('number', 'option')); ?>" class="call__wrap" target="_blank">
                                        <img src="<?= $value['url']; ?>" class="call" alt="<?= $value['alt'] ?>">
                                    </a>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                    <nav class="navigation">
                        <?php wp_nav_menu([
                            'theme_location' => 'Navigation',
                            'container' => null,
                            'menu_class' => 'header-menu',
                            'menu_id' => 'nav-ul'
                        ]); ?>
                    </nav>
                    <div class="burger-btn">
                        <span class="burger-btn__dash"></span>
                    </div>
                </div>
            </div>
        </header>