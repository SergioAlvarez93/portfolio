<?php  ?>
<footer id="footer" class="footer">
    <div class="container">
        <div class="footer-flexbox">
            <?php if ($value = get_field('logo', 9)) { ?>
                <div class="footer-flexbox-logo__wrap">
                    <a href="<?= get_home_url(); ?>" class="footer-flexbox-logo__link">
                        <img src="<?= $value['url']; ?>" class="footer-flexbox-logo" alt="<?= $value['alt'] ?>">
                    </a>
                </div>
            <?php } ?>
            <?php if (have_rows('law_information', 'option')) { ?>
                <div class="footer-flexbox-law">
                    <?php while (have_rows('law_information', 'option')) {
                        the_row(); ?>
                        <?php if (get_sub_field('label', 'option') and get_sub_field('data', 'option')) { ?>
                            <div class="footer-flexbox-law__item">
                                <div class="footer-flexbox-law__item-label"><?= get_sub_field('label', 'option'); ?></div>
                                <div class="footer-flexbox-law__item-data"> <?= get_sub_field('data', 'option'); ?></div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
            <?php
            if ($value = get_field('tel', 'option')) { ?>
                <div class="footer-flexbox-tel">
                    <a href="tel:<?= preg_replace('/[^0-9\+]/', '', $value); ?>" class="footer-flexbox-tel__item" target="_blank"><?= $value; ?></a>
                    <div class="footer-flexbox-button">
                        <a href="<?= get_home_url(); ?>/#request" class="footer-flexbox-button__link">Заказать аудит безопасности</a>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="footer-bottom">
            <div class="footer-bottom__security">Компания полностью защищена от кибератак и утечки информации</div>
            <div class="footer-bottom__security footer-bottom__security_privacy">Политика конфиденциальности</div>
            <div class="footer-bottom__security footer-bottom__security_cookie">Условиями использования cookie</div>
            <div class="footer-bottom__security">Не является публичной офертой</div>
            <div class="footer-bottom__copyright">Copyright © <?php echo date("Y"); ?> все права защищены</div>
        </div>
    </div>
</footer>
</div>
<?php if (get_field('privacy_txt', 9)) {
    include get_template_directory() . '/inc/privacy_modal.php';
} ?>
<?php if (get_field('personal_txt', 9)) {
    include get_template_directory() . '/inc/personal_modal.php';
} ?>
<?php if (get_field('cookie_txt', 9)) {
    include get_template_directory() . '/inc/cookie_modal.php';
} ?>
<?php if (get_field('cookie_txt', 9)) {
    include get_template_directory() . '/inc/cookie_bottom.php';
} ?>
<?php wp_footer(); ?>
</body>

</html>