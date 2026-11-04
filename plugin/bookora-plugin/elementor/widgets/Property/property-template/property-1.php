<?php
$this->add_render_attribute('title', 'class', 'elementor-gt-heading sec_title  sec_title  bk-itm-title bk-itm-anim');

?>
<section id="bk-service" class="bk-service-sec">
    <div class="container">
        <div class="bk-service-content">
            <div class="bk-sec-title text-center headline pera-content">
                <?php if (!empty($settings['subtitle'])): ?>
                    <div class="subtitle">
                        <i></i> <?php echo bookora_wp_kses($settings['subtitle']); ?> <i></i>
                    </div>
                <?php endif; ?>
                <?php
                printf(
                    '<%1$s %2$s>%3$s</%1$s>',
                    tag_escape($settings['title_tag']),
                    $this->get_render_attribute_string('title'),
                    nl2br(bookora_wp_kses($settings['title']))
                );
                ?>
            </div>
            <div class="bk-ser-slider-wrapper mt-55">
                <div class="bk-service-slider swiper-container">
                    <div class="swiper-wrapper">
                        <?php
                        $args = [
                            'post_type' => 'at_biz_dir',
                            'posts_per_page' => $settings['properties_count'],
                            'post_status' => 'publish',
                        ];

                        if (!empty($settings['porp_tags'][0])) {
                            $args['tax_query'] = array(
                                array(
                                    'taxonomy' => 'at_biz_dir-tags',
                                    'field' => 'ids',
                                    'terms' => $settings['porp_tags']
                                )
                            );
                        }

                        $query = new \WP_Query($args);

                        if ($query->have_posts()) {
                            while ($query->have_posts()) {
                                $query->the_post();
                                $property_id = get_the_ID();



                                // Get property details using Directorist's meta keys
                                $beds = get_post_meta(get_the_ID(), '_bookora__bed', true);
                                $guest = get_post_meta(get_the_ID(), '_bookora-guest', true);
                                $bath = get_post_meta(get_the_ID(), '_bookora-bath', true);
                                $wifi = get_post_meta(get_the_ID(), '_bookora-wifi', true);
                                $price = get_post_meta($property_id, '_price', true);
                                $price = !empty($price) ? $this->format_price($price) : 'Price on request';

                                $location = get_post_meta($property_id, '_address', true);
                                $location = !empty($location) ? $location : 'Location not specified';
                                $area = get_post_meta($property_id, '_area', true);

                                $categories = get_the_terms($property_id, 'at_biz_dir-category');
                                $category_slugs = [];
                                if ($categories && !is_wp_error($categories)) {
                                    foreach ($categories as $category) {
                                        $category_slugs[] = $category->slug;
                                    }
                                }

                                $author_id = get_post_field('post_author', $property_id);
                                $author_name = get_the_author_meta('display_name', $author_id);
                                $author_avatar = get_avatar_url($author_id, ['size' => 60]);

                                $author_id = get_post_field('post_author', $property_id);
                                $author_name = get_the_author_meta('display_name', $author_id);
                                $author_avatar = get_avatar_url($author_id, ['size' => 60]);


                                $post_thumb = has_post_thumbnail($property_id)
                                    ? get_the_post_thumbnail_url($property_id, 'large')
                                    : get_template_directory_uri() . '/assets/img/blog/default.jpg';
                                ?>

                                <div class="swiper-slide">
                                    <div class="bk-service-item">
                                        <div class="item-img img-cover">
                                            <img src="<?php echo esc_url($post_thumb); ?>"
                                                alt="<?php the_title_attribute(); ?>">
                                        </div>
                                        <div class="item-text">
                                            <div class="inner-title headline text-center">
                                                <h3><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
                                                <span><?php echo esc_html($price); ?>/ Night</span>
                                            </div>
                                            
                                            <div class="inner-list ul-li">
                                                <ul>
                                                    <?php if (!empty($guest)): ?>
                                                        <li><i class="fa-solid fa-user"></i> <?php echo bookora_wp_kses($guest); ?></li>
                                                        <?php endif; ?>
                                                        <?php if (!empty($bath)): ?>
                                                    <li><i class="fa-solid fa-bath"></i> <?php echo bookora_wp_kses($bath); ?></li><?php endif; ?>
                                                    <?php if (!empty($beds)): ?>
                                                        <li><i class="fa-solid fa-bed"></i> <?php echo bookora_wp_kses($beds); ?></li>
                                                    <?php endif; ?>
                                                    <?php if (!empty($wifi)): ?>
                                                    <li><i class="fa-solid fa-wifi"></i> <?php echo bookora_wp_kses($wifi); ?></li><?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }

                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
                <div class="bk-service-nav mt-55 d-flex align-items-center justify-content-between">
                    <div class="lg-ser-pagi"></div>
                    <div class="lg-ser-scrollbar"></div>
                    <div class="lg-nav-arrow d-flex">
                        <div class="lg-ser-prev d-flex align-items-center justify-content-center"><i
                                class="fa-solid fa-angles-left"></i></div>
                        <div class="lg-ser-next d-flex align-items-center justify-content-center"><i
                                class="fa-solid fa-angles-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>