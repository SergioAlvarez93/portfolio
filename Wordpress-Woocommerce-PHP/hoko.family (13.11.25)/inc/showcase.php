 <section id="showcase" class="showcase">
     <div class="container">
         <?php if ($value = get_field('showcase_title')) { ?>
             <?= $value; ?>
         <?php } ?>
         <ul class="showcase-list">
             <?php
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 4,
                    'order' => 'ASC',
                );
                $loop = new WP_Query($args);

                // Устанавливаем контекст showcase-list
                add_filter('is_showcase_list_context', '__return_true');

                while ($loop->have_posts()) {
                    $loop->the_post();
                    wc_get_template_part('content', 'product');
                }

                // Сбрасываем фильтр после цикла
                remove_filter('is_showcase_list_context', '__return_true');
                wp_reset_postdata();
                ?>
         </ul>
     </div>
 </section>