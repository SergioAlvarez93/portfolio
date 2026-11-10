<?php

defined('ABSPATH') || exit;

global $product;

if (! is_a($product, WC_Product::class) || ! $product->is_visible()) {
    return;
}
?>
<li <?php wc_product_class('swiper-slide market-list__item', $product); ?>>
    <?php
    do_action('custom_before_shop_loop_item_title_market');
    if (!$product) {
        error_log('Product object missing in else branch for ID: ' . get_the_ID());
        echo '<p>Ошибка: товар не найден</p>';
    }
    ?>
    <article class="market-list-item-flexbox modal-trigger" data-product-id="<?php echo get_the_ID(); ?>">
        <?php
        do_action('woocommerce_before_shop_loop_item_title_market');
        ?>
        <?php if (get_the_excerpt() || get_the_content() || $product->get_attributes()) { ?>
            <div class="market-list-item-flexbox__info-box">
                <div class="market-list-item-flexbox__info-head">
                    <?php if ($value = get_the_excerpt()) { ?>
                        <div class="market-list-item-flexbox__title"><?= $value; ?></div>
                    <?php } ?>
                    <?php if ($value = get_the_content()) { ?>
                        <div class="market-list-item-flexbox__descr"><?= $value; ?></div>
                    <?php } ?>
                </div>
                <?php
                $attributes = $product->get_attributes();
                if (!empty($attributes)) {
                    $keys = array_keys($attributes);
                    $count = count($keys);
                    echo '<div class="market-list-item-flexbox-attr">';
                    foreach ($attributes as $key => $attribute) {
                        $label = wc_attribute_label($attribute->get_name());
                        $values = wc_get_product_terms($product->get_id(), $attribute->get_name(), array('fields' => 'names')); ?>
                        <?php if ($label === 'Количество капсул' || $label === 'Способ применения' || $label === 'Масса нетто') { ?>
                            <div class="market-list-item-flexbox-attr__item <?php if ($key === end($keys)) { echo 'market-list-item-flexbox-attr__item_last'; } ?>" data-label="<?= $label; ?>">
                                <?php 
                                $count = count($values);
                                foreach ($values as $index => $value): 
                                    $parts = explode(' ', $value, 2);
                                    $number = $parts[0];
                                    
                                    echo '<span class="variation-option" data-variation-index="' . $index . '" data-attribute-value="' . esc_attr($value) . '">' . $number . '</span>';
                                    
                                    if ($index < $count - 1) {
                                        echo ' | ';
                                    } else {
                                        echo ' ' . $parts[1];
                                    }
                                endforeach; 
                                ?>
                            </div>
                        <?php } ?>
                <?php }
                    echo '</div>';
                }
                ?>
            </div>
        <?php } ?>
        <?php /* <a href="#" class="market-list__item-btn modal-trigger" data-product-id="<?php echo get_the_ID(); ?>">Подробнее</a> */ ?>
    </article>
</li>