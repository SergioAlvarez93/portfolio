<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset') ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
    <title>Land</title>
</head>

<body>
    <div class="general-wrapper">
        <header class="header">
            <div class="header__container">
                <div class="header-flexbox">
                    <?php if ($value = get_field('logo_desktop')) { ?>
                        <div class="logo">
                            <a href="<?php echo home_url(); ?>">
                                <picture>
                                    <source srcset="<?= $value; ?>" media="(min-width: 992px)">
                                    <?php if ($value = get_field('logo_mobile')) { ?>
                                        <img src="<?= $value; ?>" alt="logo">
                                    <?php } ?>
                                </picture>
                            </a>
                        </div>
                    <?php } ?>
                    <nav class="main-nav">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'Navigation',
                            'container' => false,
                            'menu_class' => 'menu'
                        ));
                        ?>
                    </nav>
                    <?php
                    if (have_rows('header_links_repeater')) {
                    ?>
                        <div class="extra-links">
                            <?php while (have_rows('header_links_repeater')) {
                                the_row();
                            ?>
                                <?php if ($title = get_sub_field('title')) { ?>
                                    <a href="<?php if ($link = get_sub_field('link')) { ?><?= $link; ?><?php } else { ?>#<?php } ?>" class="header-link"><?= $title; ?></a>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <a href="#" class="burger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </a>
                </div>
                <div class="overlay"></div>
            </div>
        </header>