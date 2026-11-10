<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset') ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="keywords" content="парифарм, paripharm, липосомальное сырье, сырье для бадов, сырье для бад купить, липосомальные витамины купить, липосомальные витамины купить официальный сайт, липосомальные витамины производство, липосомальные витамины россия, липосомальные витамины что это простыми, что значит липосомальная форма витамина, липосомальный витамин это какой, лучшие липосомальные витамины,  липосомальный витамин компания, липосомальные витамины преимущества, липосомальные витамины фирма" />
    <?php wp_head(); ?>
    <title>Paripharm</title>
</head>

<body>
    <div class="general-wrapper">
        <header class="header">
            <div class="container">
                <div class="header-grid">
                    <?php if ($value = get_field('logo')) { ?>
                        <a href="<?= get_home_url(); ?>" class="logo-wrap">
                            <img src="<?= $value['url']; ?>" class="logo" alt="<?= $value['alt']; ?>">
                        </a>
                    <?php } ?>
                    <nav class="navigation">
                        <?php wp_nav_menu([
                            'theme_location' => 'Navigation',
                            'container' => null,
                            'menu_class' => null,
                            'menu_id' => 'nav-ul'
                        ]); ?>
                    </nav>
                    <a href="#" class="classic-btn header-btn">Обратный звонок <span class="tel-icon"></span></a>
                    <div class="burger-btn">
                        <span class="burger-btn__dash"></span>
                    </div>
                </div>
            </div>
        </header>