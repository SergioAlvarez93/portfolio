<footer id="footer" class="footer">
    <?php if (get_field('logo') || get_field('contacts_bg') || get_field('contacts_title') || get_field('contacts_subtitle') || get_field('email', 'option') || get_field('phone', 'opttion')) { ?>
        <div class="container">
            <div class="footer-general-wrap">
                <div class="footer-flexbox">
                    <?php if ($value = get_field('logo')) { ?>
                        <div class="footer-flexbox__logo-wrap">
                            <img src="<?= $value['url']; ?>" class="footer-flexbox__logo" alt="<?= $value['alt']; ?>">
                        </div>
                    <?php } ?>
                    <div class="general-txt footer-flexbox__copyright">Сopyright@<?php echo date("Y"); ?></div>
                </div>
                <?php if (get_field('phone', 'option') || get_field('email', 'option')) { ?>
                    <div class="footer-contacts">
                        <?php if ($value = get_field('email', 'option')) { ?>
                            <a href="mailto:<?= $value; ?>" class="classic-h3 footer-contacts__email" target="_blank"><?= $value; ?></a>
                        <?php } ?>
                        <?php if ($value = get_field('phone', 'option')) { ?>
                            <a href="tel:<?= preg_replace('/[^0-9\+]/', '', $value); ?>" class="classic-h3 footer-contacts__phone" target="_blank"><?= $value; ?></a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</footer>
<div class="modal-overlay" id="modal">
    <div class="modal-content">
        <button class="modal-close">&times;</button>
        <?php echo do_shortcode('[contact-form-7 id="0a15662" title="Контактная форма 1"]'); ?>
    </div>
</div>
</div>
<?php wp_footer(); ?>
</body>

</html>