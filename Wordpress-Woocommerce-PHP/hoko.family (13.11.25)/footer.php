<footer id="footer" class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-grid-logo-item">
                <?php if ($value = get_field('logo', 2)) { ?>
                    <a href="<?= get_home_url(); ?>" class="logo-wrap footer-grid-logo-item__logo-wrap">
                        <img src="<?= $value['url']; ?>" class="logo footer-grid-logo-item__logo" alt="<?= $value['alt'] ?>">
                    </a>
                <?php } ?>
                <p class="logo-wrap footer-grid-logo-item__text">Качественные витамины для
                    здорового образа жизни
                </p>
            </div>
            <nav class="navigation footer-grid-navigation">
                <?php wp_nav_menu([
                    'theme_location' => 'Footer',
                    'container' => null,
                    'menu_class' => 'footer-menu',
                    'menu_id' => 'nav-ul'
                ]); ?>
            </nav>
            <?php
            if (have_rows('social', 'option')) { ?>
                <div class="footer-grid-social">
                    <?php while (have_rows('social', 'option')) {
                        the_row(); ?>
                        <?php if ($value = get_sub_field('link', 'option')) { ?>
                            <a href="<?= $value; ?>" class="footer-grid-social__item" target="_blank">
                                <?php if ($value = get_sub_field('icon', 'option')) { ?>
                                    <img src="<?= $value['url']; ?>" class="footer-grid-social__item-img" alt="<?= $value['alt']; ?>">
                                <?php } ?>
                            </a>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
            <?php
            if (have_rows('tel', 'option')) { ?>
                <div class="footer-grid-tel">
                    <?php while (have_rows('tel', 'option')) {
                        the_row(); ?>
                        <?php if ($value = get_sub_field('number', 'option')) { ?>
                            <a href="tel:<?= preg_replace('/[^0-9\+]/', '', $value); ?>" class="footer-grid-tel__item" target="_blank"><?= $value; ?></a>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <div class="copyright">Copyright © <?php echo date("Y"); ?> все права защищены</div>
    </div>
</footer>
</div>
<?php include get_template_directory() . '/inc/product-modal.php'; ?>
<?php if (get_field('confidential', 2)) {
    include get_template_directory() . '/inc/confidential-modal.php';
} ?>
<?php if (get_field('legal_offer', 2)) {
    include get_template_directory() . '/inc/legal-offer-modal.php';
} ?>
<?php wp_footer(); ?>
</body>

</html>