<footer class="footer__bg">
    <div class="basic-container">
        <div class="footer">
            <div class="footer__grid">
                <h2 class="basic-title footer-header">Контакты</h2>
                <div class="footer-info">
                    <?php if ($contact = get_field('working_time', 'options')) { ?>
                        <div class="footer-contacts">
                            <div class="footer-img__wrap">
                                <img class="footer-img" src=<?= get_template_directory_uri() . "/img/footer/clock.svg" ?> alt="" />
                            </div>
                            <div class="footer-contacts__content"><?php echo $contact; ?></div>
                        </div>
                    <?php } ?>
                    <?php if ($contact = get_field('adress', 'options')) { ?>
                        <div class="footer-contacts">
                            <div class="footer-img__wrap">
                                <img class="footer-img" src=<?= get_template_directory_uri() . "/img/footer/point.svg" ?> alt="" />
                            </div>
                            <div class="footer-contacts__content"><?php echo $contact; ?></div>
                        </div>
                    <?php } ?>
                    <?php if ($contact = get_field('tel', 'options')) { ?>
                        <div class="footer-contacts">
                            <div class="footer-img__wrap">
                                <img class="footer-img" src=<?= get_template_directory_uri() . "/img/footer/phone.svg" ?> alt="" />
                            </div>
                            <div class="footer-contacts__content">
                                <a class="footer-contacts__content-link" href="tel:<?php echo preg_replace('/[^0-9\+]/', '', $contact); ?>"><?php echo $contact; ?></a>
                                <a class="footer-contacts__content-link" href="tel:<?php echo preg_replace('/[^0-9\+]/', '', $contact); ?>">Позвонить</a>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($contact = get_field('email', 'options')) { ?>
                        <div class="footer-contacts">
                            <div class="footer-img__wrap">
                                <img class="footer-img" src=<?= get_template_directory_uri() . "/img/footer/message.svg" ?> alt="" />
                            </div>
                            <div class="footer-contacts__content">
                                <a class="footer-contacts__content-link mail-link" href="mailto:<?php echo $contact; ?>"><?php echo $contact; ?></a>
                                <a class="footer-contacts__content-link copy-link">Скопировать эл. почту</a>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="footer-social">
                        <?php if ($contact = get_field('whatsapp', 'options')) { ?>
                            <a href="<?php echo $contact; ?>" class="footer-img__wrap" target="_blank">
                                <img class="footer-img" src=<?= get_template_directory_uri() . "/img/footer/whatsapp.svg" ?> alt="" />
                            </a>
                        <?php } ?>
                        <?php if ($contact = get_field('telegram', 'options')) { ?>
                            <a href="<?php echo $contact; ?>" class="footer-img__wrap" target="_blank">
                                <img class="footer-img" src=<?= get_template_directory_uri() . "/img/footer/telegram.svg" ?> alt="" />
                            </a>
                        <?php } ?>
                    </div>
                    <?php if ($contact = get_field('copyright', 'options')) { ?>
                        <div class="footer-copyright">
                            <?php echo $contact; ?> <span class="footer-copyright__year"><?php echo date("Y"); ?></span>
                        </div>
                    <?php } ?>
                </div>
                <div class="footer-map__wrap">
                    <?php if ($map = get_field('map', 'options')) { ?>
                        <?php echo $map ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</footer>
</div>
<?php wp_footer(); ?>
</body>

</html>