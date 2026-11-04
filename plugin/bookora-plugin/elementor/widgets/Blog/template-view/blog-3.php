<?php
$this->add_render_attribute('title', 'class', 'elementor-gt-heading');
if (!empty($settings['btn_link']['url'])) {
    $this->add_link_attributes('btn_link', $settings['btn_link']);
}
?>
<div id="bk-blog3" class="bk-blog3-sec pt-110 pb-110">
    <div class="container">
        <div class="bk-blog3-top pera-content d-flex align-items-end justify-content-between">
            <?php if (!empty($settings['subtitle'])): ?>
                <div class="top-title headline">
                    <h2 class="text-uppercase"><?php echo bookora_wp_kses($settings['subtitle']); ?></h2>
                </div>
            <?php endif; ?>
            <?php if (!empty($settings['descripiton'])) {
                echo bookora_wp_kses(wpautop($settings['descripiton']));
            } ?>
        </div>
        <?php if (!empty($settings['tagtitle'])): ?>
            <div class="bk-blog3-label mt-50 d-flex justify-content-between">
                <?php echo bookora_wp_kses($settings['tagtitle']); ?>
            </div>
        <?php endif; ?>
        <div class="bk-blog3-content mt-50">
            <div class="bk-blog3-text headline">
                <?php
                printf(
                    '<%1$s %2$s>%3$s</%1$s>',
                    tag_escape($settings['title_tag']),
                    $this->get_render_attribute_string('title'),
                    nl2br(bookora_wp_kses($settings['title']))
                );
                ?>
                <?php if (!empty($settings['h_btn_label'])): ?>
                    <div class="bk-btn1">
                        <a <?php echo $this->get_render_attribute_string('btn_link'); ?>><?php echo bookora_wp_kses($settings['h_btn_label']); ?> </a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="bk-blog3-item-feed">
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
                        <div class="bk-blog3-item">
                            <div class="item-img img-cover">
                                <img src="<?php echo esc_url($post_thumb); ?>" alt="<?php the_title_attribute(); ?>">
                            </div>
                            <div class="item-text headline pera-content">
                                <span><?php echo esc_html($post_date); ?></span>
                                <h3 class="blg_title"><a
                                        href="<?php the_permalink(); ?>"><?php echo wp_trim_words(get_the_title(), $settings['title_length'] ?? 8, '...'); ?></a>
                                </h3>
                                <?php if (!empty($settings['btn_label'])): ?>
                                    <a class="blog_more text-uppercase"
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
        </div>
    </div>
</div>