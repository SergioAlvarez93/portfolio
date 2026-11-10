<footer id="footer" class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-grid-logo-item">
                <?php if ($value = get_field('logo', 8)) { ?>
                    <a href="<?= get_home_url(); ?>" class="logo-wrap footer-grid-logo-item__logo-wrap">
                        <img src="<?= $value['url']; ?>" class="logo footer-grid-logo-item__logo" alt="<?= $value['alt'] ?>">
                    </a>
                <?php } ?>
            </div>
            <nav class="navigation footer-grid-navigation">
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
            <div class="footer-grid-mail">
                <?php if (SITE_LANG !== 'en') { ?>
                    <?php if ($value = get_field('email', 'option')) { ?>
                        <a href="mailto:<?= $value; ?>" class="footer-grid-mail__item" target="_blank">Написать нам <br><?= $value; ?></a>
                    <?php } ?>
                <?php } else { ?>
                    <?php if ($value = get_field('email', 'option')) { ?>
                        <a href="mailto:<?= $value; ?>" class="footer-grid-mail__item" target="_blank">Write to us <br><?= $value; ?></a>
                    <?php } ?>
                <?php } ?>               
            </div>
        </div>
        <div class="footer-grid-social">
            <?php if (SITE_LANG !== 'en') { ?>
                <div class="copyright">Copyright © <?php echo date("Y"); ?> все права защищены</div>
            <?php } else { ?>
                <div class="copyright">Copyright © <?php echo date("Y"); ?> all rights reserved</div>
            <?php } ?>
            <div class="privacy-offer-box">
                <?php if (SITE_LANG !== 'en') { ?>
                    <a href="#" class="privacy-offer-box__item">Конфиденциальность</a>
                <?php } else { ?>
                    <a href="#" class="privacy-offer-box__item">Privacy Policy</a>
                <?php } ?>
                <?php if (SITE_LANG !== 'en') { ?>
                    <a href="#" class="privacy-offer-box__item">Оферта</a>
                <?php } else { ?>
                    <a href="#" class="privacy-offer-box__item">Public Offer</a>
                <?php } ?>                
            </div>
            <?php if (have_rows('social', "option")) { ?> 
                <div class="social">
                     <?php while (have_rows('social', "option")) {
                        the_row(); ?>
                            <?php if ($value = get_sub_field('link', "option")) { ?>                               
                                <a href="<?= $value; ?>" class="social__item" target="_blank">
                                    <?php if ($img = get_sub_field('icon', "option")) { ?> 
                                        <img src="<?= $img['url']; ?>" alt="<?= $img['alt']; ?>"> 
                                    <?php } ?>                                    
                                </a>
                             <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</footer>
</div>
<?php wp_footer(); ?>
</body>

</html>