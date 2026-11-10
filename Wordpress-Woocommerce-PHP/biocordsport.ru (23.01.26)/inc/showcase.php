<section class="showcase" id="showcase">
    <div class="container">
        <div class="showcase-grid">
            <?php if ($value = get_field('showcase_title')) { ?>
                <h2 class="classic-h2 showcase__title"><?= $value; ?></h2>
            <?php } ?>
            <ul class="showcase-list">
                <div class="swiper-wrapper">
                    <?= do_shortcode('[showcase_products]'); ?>
                </div>
            </ul>
            <div class="showcase-controls">
                <div class="showcase-controls__flexbox">
                    <div class="showcase-controls__prev-wrap">
                        <img src="<?= get_template_directory_uri(); ?>/img/showcase/prev_arrow.png" class="showcase-controls__prev" alt='Кнопка управления для слайдера витрины "Назад"'>
                    </div>
                    <div class="swiper-pagination showcase-controls__pagination"></div>
                    <div class="showcase-controls__next-wrap">
                        <img src="<?= get_template_directory_uri(); ?>/img/showcase/next_arrow.png" class="showcase-controls__next" alt='Кнопка управления для слайдера витрины "Вперёд"'>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>