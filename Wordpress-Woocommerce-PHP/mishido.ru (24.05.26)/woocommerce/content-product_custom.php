<?php

/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined('ABSPATH') || exit;

global $product;

$variations = $product->get_available_variations();
$output = [];
$first_var = reset($variations);
$first_data = [
	'id'      => $first_var['variation_id'] ?? 0,
	'image'   => $first_var['image']['src'] ?? '',
	'srcset'  => $first_var['image']['srcset'] ?? '',
	'acf'     => get_fields($first_var['variation_id'] ?? 0) ?? []
];

// Check if the product is a valid WooCommerce product and ensure its visibility before proceeding.
if (! is_a($product, WC_Product::class) || ! $product->is_visible()) {
	return;
}
?>
<?php if (SITE_LANG == 'en' || (SITE_LANG !== 'en' and !get_field('ru_exclude', $product->id))) { ?>
	<li <?php wc_product_class('products__item', $product); ?>>
		<article class="products__item-article">
			<div class="product-preview-grid">
				<?php if (SITE_LANG !== 'en') { ?>
					<?php if ($ruImg = get_field('product_img_ru', $product->id)) { ?> 				
						<div class="product-preview-img-ru">
							<img src="<?= $ruImg["url"] ?>" alt="$ruImg['alt']" loading="lazy">
						</div>
					<?php } else { ?>
						<div class="product-preview-img">
							<img src="<?php echo esc_url(wp_get_attachment_url($product->get_image_id())); ?>"
								alt="<?php echo esc_attr($product->get_name()); ?>"
								loading="lazy">
						</div>
					<?php } ?> 
				<?php } else { ?>
					<div class="product-preview-img">
						<img src="<?php echo esc_url(wp_get_attachment_url($product->get_image_id())); ?>"
							alt="<?php echo esc_attr($product->get_name()); ?>"
							loading="lazy">
					</div>
				<?php } ?> 
				<?php if (SITE_LANG !== 'en') { ?> 
					<?php if ($value = get_field('product_name_ru', $product->id)) { ?> 
						<h2 class="woocommerce-loop-product__title"><?= $value; ?></h2>
					<?php } ?>
				<?php } else { ?>
					<h2 class="woocommerce-loop-product__title"><?= $product->get_name(); ?></h2>
				<?php } ?>
				<?php if (SITE_LANG !== 'en') { ?> 
					<?php if ($value = get_field('product_short_desc_ru', $product->id)) { ?> 
						<div class="product-short-description"><?= $value; ?></div> 
					<?php } ?>
				<?php } else { ?>
					<?php if ($product->get_short_description()) { ?>
						<div class="product-short-description"><?= wp_kses_post($product->get_short_description()); ?></div>
					<?php } ?>
				<?php } ?>
				<?php if (SITE_LANG !== 'en') { ?> 
					<?php if ($value = get_field('product_detail_desc_ru', $product->id)) { ?>
						<?= $value; ?>
					<?php } ?>
				<?php } else { ?>
					<?= wp_kses_post($product->get_description()); ?>
				<?php } ?>
				<?php if ($value = get_field('product_unit', $product->id)) { ?>
					<div class="product-unit__wrap">
						<img src="<?= $value['url']; ?> " class="product-unit" alt="<?= $value['alt']; ?> ">
					</div>
				<?php } ?>
				<?php if ($product->is_type('variable')) { ?>
					<div class="product-formation">
						<?php foreach ($variations as $var) {
							$qty_slug = $var['attributes']['attribute_pa_quantity'] ?? '';

							$term = get_term_by('slug', $qty_slug, 'pa_quantity');

							$qty_name = $qty_slug;

							if ($term && !is_wp_error($term)) {
								if (SITE_LANG !== 'en') {
									$qty_name = get_field('variation_quantity_ru', $term->term_id) 
											?: get_field('variation_quantity_ru', 'pa_quantity_' . $term->term_id) 
											?: $term->name 
											?: $qty_slug;
								} else {
									$qty_name = $term->name ?: $qty_slug;
								}
							}

							$data = [
								'id'      => $var['variation_id'],
								'image'   => $var['image']['src'] ?? '',
								'srcset'  => $var['image']['srcset'] ?? '',
								'acf'     => [
									"product_backside_img" => get_field('product_backside_img', $var['variation_id']),
									'ozon'                 => get_field('ozon', $var['variation_id']),
									'wildberries'          => get_field('wildberries', $var['variation_id'])
								],
							];

							$ru_image = get_field('product_backside_img_ru', $var['variation_id']);
							if (SITE_LANG !== 'en' && !empty($ru_image)) {
								$data['acf']['product_backside_img_ru'] = $ru_image;
							} else {
								$data['acf']['product_backside_img_ru'] = get_field('product_backside_img', $var['variation_id']);
							}

							$output[] = '<button type="button" class="quantity-btn" '
									. 'data-variation="' . esc_attr(json_encode($data)) . '" '
									. 'data-id-variation="' . esc_attr($data['id']) . '">'
									. esc_html($qty_name)
									. '</button>';
						}
						echo implode(' | ', $output);
						?>
					</div>
				<?php } ?>
				<div class="marketplace-block">
					<a data-acf="ozon" class="ozon" href="" target="_blank">OZON</a>
					<a data-acf="wildberries" class="wildberries" href="" target="_blank">WILDBERRIES</a>
				</div>
			</div>
			<?php if (SITE_LANG !== 'en') { ?>
				<div data-acf="product_backside_img_ru" class="product-backside-img"></div>
			<?php } else { ?>
				<div data-acf="product_backside_img" class="product-backside-img"></div>
			<?php } ?>
		</article>
	</li>
<?php } ?> 