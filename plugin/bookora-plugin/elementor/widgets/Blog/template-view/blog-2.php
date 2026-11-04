<?php
$this->add_render_attribute('title', 'class', 'elementor-gt-heading sec_title  bk-itm-title bk-itm-anim');
if (!empty($settings['btn_link']['url'])) {
    $this->add_link_attributes('btn_link', $settings['btn_link']);
}
?>
<div class="bk-blog2-content">
    <div class="bk-blog2-text">
        <div class="bk-sec-title headline pera-content">
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
            <?php if (!empty($settings['descripiton'])) {
                echo bookora_wp_kses(wpautop($settings['descripiton']));
            } ?>
        </div>
        <?php if (!empty($settings['h_btn_label'])): ?>
            <div class="bk-btn1 mt-30">
                <a <?php echo $this->get_render_attribute_string('btn_link'); ?>><?php echo bookora_wp_kses($settings['h_btn_label']); ?> </a>
            </div>
        <?php endif; ?>
    </div>
    <div class="bk-blog2-feed-wrap d-flex">
        <?php
        if ($query->have_posts()) {
            $i = 0;
            while ($query->have_posts()) {
                $i++;
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
                <div class="bk-blog2-item <?php if ($i == 1) {
                    echo esc_attr('active');
                } ?> headline position-relative wow fadeInRight" data-wow-delay="150ms" data-wow-duration="1200ms">
                    <span><?php echo esc_html($post_date); ?></span>
                    <div class="item-img img-cover">
                        <img src="<?php echo esc_url($post_thumb); ?>" alt="<?php the_title_attribute(); ?>">
                    </div>
                    <h3 class="blg_title"><a
                            href="<?php the_permalink(); ?>"><?php echo wp_trim_words(get_the_title(), $settings['title_length'] ?? 8, '...'); ?></a>
                    </h3>
                </div>
                <?php
            }
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__('No posts found.', 'your-textdomain') . '</p>';
        }
        ?>
    </div>
</div>