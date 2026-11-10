<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset') ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
    <title>mishido</title>
</head>

<body>
    <div class="general-wrapper">
        <header class="header">
            <div class="container">
                <div class="header-grid">
                    <?php if ($value = get_field('logo', 8)) { ?>
                        <a href="<?= get_home_url(); ?>" class="logo-wrap">
                            <img src="<?= $value['url']; ?>" class="logo" alt="<?= $value['alt'] ?>">
                        </a>
                    <?php } ?>
                    <nav class="navigation">
                        <?php $navLoc = null; 
                        if (SITE_LANG === 'en') {  $navLoc = 'Navigation EN'; } else {
                            $navLoc = 'Navigation';
                        }?>
                        <?php wp_nav_menu([
                            'theme_location' => $navLoc,
                            'container' => null,
                            'menu_class' => 'header-menu',
                            'menu_id' => 'nav-ul'
                        ]); ?>
                    </nav>
                    <?php
                    if ($value = get_field('tel', 'option')) { ?>
                        <?php $phoneCallTxt = SITE_LANG === 'en' ? "Request a Callback" : "Заказать обратный звонок" ?>
                        <a href="#message" class="header-btn" target="_blank"><?= $phoneCallTxt; ?><br><?= $value; ?></a>
                    <?php } ?>
                    <div class="burger-btn">
                        <span class="burger-btn__dash"></span>
                    </div>
                </div>
            </div>
        </header>