<?php

defined('ABSPATH') || exit;

global $product;

if (! is_a($product, WC_Product::class) || ! $product->is_visible()) {
	return;
}

$variations = $product->get_available_variations();
$variations_data = array();
foreach ($variations as $index => $variation) {
    $var_id = $variation['variation_id'];
    $attrs = array();
    foreach ($variation['attributes'] as $attr_name => $attr_value) {
        $clean_name = str_replace('attribute_', '', $attr_name);
        $attrs[$clean_name] = $attr_value;
    }
    $gallery = get_field('variations_gallery', $var_id) ?: array();
    $variations_data[] = array(
		'id' => $var_id,
		'index' => $index,
		'attributes' => $attrs,
		'ozon' => get_field('variations_ozon', $var_id) ?: '',
		'wildberries' => get_field('variations_wildberries', $var_id) ?: '',
		'image' => $variation['image']['src'] ?? null,
	);
}
?>
<li <?php wc_product_class((get_field('showcase') === true) ? 'swiper-slide showcase-list__item' : 'swiper-slide market-list__item', $product); ?>>
	<?php if (get_field('showcase') === true) { ?>
		<article class="showcase-product" data-product-id="<?= $product->get_id(); ?>">
			<div class="showcase-product-img-box">
				<div class="showcase-product-img-box__wrap">
					<?php do_action('woocommerce_before_shop_loop_item_title_showcase'); ?>
				</div>
			</div>
			<div class="showcase-product-content-box" style="background-color: <?php if ($value = get_field('showcase_color')) { echo $value; } ?>;">
				<?php if (get_field('showcase_bg') and get_field('showcase_bg_mob') and !get_field('showcase_video')) { ?>
					<picture class="showcase-product-content-box__picture">
						<source srcset="<?= get_field('showcase_bg')['url']; ?>" alt="<?= get_field('showcase_bg')['alt']; ?>" media="(min-width: 768px)" />
						<img src="<?= get_field('showcase_bg_mob')['url']; ?>" class="showcase-product-content-box__bg" alt="<?= get_field('showcase_mob')['alt']; ?>" />
					</picture>
				<?php } elseif (get_field('showcase_video')) { ?>
					<video autoplay loop muted playsinline class="showcase-product-content-box__video">
						<source src="<?= get_field('showcase_video')['url']; ?>" type="video/mp4">
					</video>
				<?php } ?>
				<div class="showcase-product-content-box__general">
					<?php if (have_rows('product_target')) { ?>
						<ul class="showcase-product-content-box__target">
							<?php while (have_rows('product_target')) { the_row(); ?>
								<?php if ($value = get_sub_field('target')) { ?>
									<li class="showcase-product-content-box__target-item"><?= $value; ?></li>
								<?php } ?>
							<?php } ?>
						</ul>
					<?php } ?>
					<?php if ($value = $product->get_name()) { ?>
						<div class="showcase-product-content-box__title"><?= $value; ?></div>
					<?php } ?>
					<?php
					$attributes = $product->get_attributes();
					if (!empty($attributes)) {
						$keys = array_keys($attributes);
						foreach ($attributes as $key => $attribute) {
							$label = wc_attribute_label($attribute->get_name());
							$values = wc_get_product_terms($product->get_id(), $attribute->get_name(), array('fields' => 'names')); ?>
							<div class="showcase-product-content-box__attribute <?php if ($key === end($keys)) { echo 'showcase-product-content-box__attribute_last'; } ?>" data-label="<?= $label; ?>">
								<?php if (($label === 'Количество капсул' || $label === 'Масса нетто') && !empty($values)): ?>
									<?php 
									$count_values = count($values);
									foreach ($values as $index => $value):
										$parts = explode(' ', $value, 2);
										$number = $parts[0];
										if ($count_values > 1) {
											$activeClass = ($index === 0) ? ' variation-option__active' : '';
											$showcaseColor = get_field('showcase_color') ?: '#000';
											echo '<span class="variation-option' . $activeClass . '" style="--variation-color: ' . $showcaseColor . ';" data-variation-index="' . $index . '" data-attribute-value="' . esc_attr($value) . '">' . $number . '</span>';
										} else {
											echo '<span>' . $number . '</span>';
										}
									?>
										<?php if ($index < $count_values - 1): ?> | <?php else: ?> <?= $parts[1]; ?><?php endif; ?>
									<?php endforeach; ?>
								<?php elseif ($label === 'Активное вещество' && !empty($values)): ?>
									<?php 
									$count_values = count($values);
									foreach ($values as $index => $value):
										$parts = explode(' ', $value, 2);
										$number = $parts[0];
									?>
										<span data-attribute-value="<?= esc_attr($value); ?>"><?= $number; ?></span>
										<?php if ($index < $count_values - 1): ?> | <?php else: ?> <?= $parts[1]; ?><?php endif; ?>
									<?php endforeach; ?>
								<?php else: ?>
									<?= implode(', ', $values); ?>
								<?php endif; ?>
							</div>
					<?php }
					} ?>
				</div>
				<?php 
				$default_ozon = $variations_data[0]['ozon'] ?? '';
				$default_wb = $variations_data[0]['wildberries'] ?? '';
				if ($default_ozon || $default_wb) { ?>
					<div class="showcase-product-content-box__buy-flexbox">
						<div class="showcase-product-content-box__buy-text">КУПИТЬ:</div>
						<div class="showcase-product-content-box-btn-box">
							<?php if ($default_ozon) { ?>
								<a href="<?= esc_url($default_ozon); ?>" class="showcase-product-content-box-btn-box__link" style="color: <?php if ($value = get_field('showcase_color')) { echo $value; } ?>;" target="_blank">OZON</a>
							<?php } ?>
							<?php if ($default_wb) { ?>
								<a href="<?= esc_url($default_wb); ?>" class="showcase-product-content-box-btn-box__link" style="color: <?php if ($value = get_field('showcase_color')) { echo $value; } ?>;" target="_blank"><span>WILDBERRIES</span></a>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
			</div>
			<script type="application/json" class="variations-data">
				<?= json_encode($variations_data, JSON_UNESCAPED_UNICODE); ?>
			</script>
		</article>
	<?php } ?>
</li>