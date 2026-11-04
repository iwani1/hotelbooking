<?php

//Recent Posts
class bookora_Recent_Posts extends WP_Widget
{
    /** constructor */
    public function __construct()
    {
        parent::__construct( /* Base ID */ 'bookora_Recent_Posts', /* Name */ esc_html__('bookora Recent Posts', 'bookora-tools'), array('description' => esc_html__('Show the Recent Posts', 'bookora-tools')));
    }


    /** @see WP_Widget::widget */
    public function widget($args, $instance)
    {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);

        echo wp_kses_post($before_widget); ?>

                <div class="news-widget">
                    <?php echo wp_kses_post($before_title . $title . $after_title); ?>
                    <!-- Footer Column -->
                    <?php $query_string = array('showposts' => $instance['number']);
                    if ($instance['cat']) {
                        $query_string['tax_query'] = array(array('taxonomy' => 'category', 'field' => 'id', 'terms' => (array) $instance['cat']));
                    }
                    $this->posts($query_string);
                    ?>
                </div>

                <?php echo wp_kses_post($after_widget);
    }


    /** @see WP_Widget::update */
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = $new_instance['number'];
        $instance['cat'] = $new_instance['cat'];

        return $instance;
    }

    /** @see WP_Widget::form */
    public function form($instance)
    {
        $title = ($instance) ? esc_attr($instance['title']) : esc_html__('Recent Post', 'bookora-tools');
        $number = ($instance) ? esc_attr($instance['number']) : 3;
        $cat = ($instance) ? esc_attr($instance['cat']) : ''; ?>

                <p>
                    <label
                        for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title: ', 'bookora-tools'); ?></label>
                    <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                        name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                        value="<?php echo esc_attr($title); ?>" />
                </p>
                <p>
                    <label
                        for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('No. of Posts:', 'bookora-tools'); ?></label>
                    <input class="widefat" id="<?php echo esc_attr($this->get_field_id('number')); ?>"
                        name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="text"
                        value="<?php echo esc_attr($number); ?>" />
                </p>
                <p>
                    <label
                        for="<?php echo esc_attr($this->get_field_id('categories')); ?>"><?php esc_html_e('Category', 'bookora-tools'); ?></label>
                    <?php wp_dropdown_categories(array('show_option_all' => esc_html__('All Categories', 'bookora-tools'), 'taxonomy' => 'category', 'selected' => $cat, 'class' => 'widefat', 'name' => $this->get_field_name('cat'))); ?>
                </p>

                <?php
    }

    public function posts($query_string)
    {
        ?>

                <div class="recent-post-wrap">
                    <!-- Title -->
                    <?php
                    $query = new WP_Query($query_string);
                    if ($query->have_posts()):
                        global $post;
                        $iten_number = 0;
                        while ($query->have_posts()):
                            $query->the_post();
                            $iten_number++;
                            $post_thumbnail_id = get_post_thumbnail_id($post->ID);
                            $post_thumbnail_url = wp_get_attachment_url($post_thumbnail_id);
                            ?>
                                    <div class="recent-post-item">
                                        <div class="item-img img-cover">
                                            <img src="<?php echo esc_url($post_thumbnail_url); ?>" alt="">
                                        </div>
                                        <div class="item-text headline pera-content">
                                            <span><i class="fa-solid fa-calendar-days"></i> <?php echo get_the_date(); ?></span>
                                            <h3><a
                                                    href="<?php echo esc_url(get_the_permalink(get_the_id())); ?>"><?php echo wp_trim_words(get_the_title(), 8, '.'); ?></a>
                                            </h3>
                                        </div>
                                    </div>
                            <?php endwhile; ?>
                    <?php endif;
                    wp_reset_postdata(); ?>
                </div>
                <?php
    }
}