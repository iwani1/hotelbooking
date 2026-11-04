<div class="bk-blog-content mt-55">
    <?php
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $post_date = get_the_date('F j, Y', $post_id); // Example: November 5, 2025
    
            // Get first category
            $categories = get_the_category($post_id);
            if (!empty($categories)) {
                $post_cat = esc_html($categories[0]->name);
                $post_cat_link = esc_url(get_category_link($categories[0]->term_id));
            } else {
                $post_cat = esc_html__('Uncategorized', 'your-textdomain');
                $post_cat_link = '#';
            }

            // Get featured image or fallback
            $post_thumb = has_post_thumbnail($post_id)
                ? get_the_post_thumbnail_url($post_id, 'large')
                : get_template_directory_uri() . '/assets/img/blog/default.jpg';

            // Author data
            $author_id = get_post_field('post_author', $post_id);
            $author_name = get_the_author_meta('display_name', $author_id);
            $author_avatar = get_avatar_url($author_id, ['size' => 64]);
            $author_desc = get_the_author_meta('description', $author_id);
            $author_url = get_author_posts_url($author_id);
            ?>
            <div class="bk-blog-item">
                <div class="item-img">
                    <img src="<?php echo esc_url($post_thumb); ?>" alt="<?php the_title_attribute(); ?>">
                </div>
                <div class="item-text headline pera-content">
                    <span><?php echo esc_html($post_date); ?></span>
                    <h3 class="blog_title"><a
                            href="<?php the_permalink(); ?>"><?php echo wp_trim_words(get_the_title(), $settings['title_length'] ?? 8, '...'); ?></a>
                    </h3>
                    <?php echo wp_trim_words(get_the_excerpt(), $settings['excerpt_length'] ?? 8, '...'); ?>
                    <?php if (!empty($settings['btn_label'])): ?>
                        <a class="read_more text-uppercase"
                            href="<?php the_permalink(); ?>"><?php echo bookora_wp_kses($settings['btn_label']) ?></a>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        }
        wp_reset_postdata();
    } else {
        echo '<p>' . esc_html__('No posts found.', 'your-textdomain') . '</p>';
    }
    ?>
</div>