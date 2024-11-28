<footer id="footer" class="footer">
    <div class="footer__container">
        <div class="footer-flexbox">
            <div class="footer-flexbox__st-item">
                <?php if ($value = get_field('footer_title')) { ?>
                    <div class="footer-flexbox__st-item-title"><?= $value; ?></div>
                <?php } ?>
                <?php if ($value = get_field('footer_txt')) { ?>
                    <p class="footer-flexbox__st-item-txt"><?= $value; ?></p>
                <?php } ?>
                <?php
                if (get_field('email', 'option')) {
                    $value = get_field('email', 'option'); ?>
                    <a href="mailto:<?= $value; ?>" class="footer-flexbox__st-item-link" target="_blank">contact</a>
                <?php } ?>
            </div>
            <?php if (shortcode_exists('contact-form-7')) { ?>
                <div class="footer-flexbox__nd-item">
                    <?php echo do_shortcode('[contact-form-7 id="c354b1f" title="Подписка на рассылку"]'); ?>
                </div>
            <?php } ?>
        </div>
        <?php include 'inc/form-updates.php'; ?>
    </div>
</footer>
</div>
<?php wp_footer(); ?>
</body>

</html>