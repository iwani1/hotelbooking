<?php
if (!empty($settings['btn_link']['url'])) {
    $this->add_link_attributes('btn_link', $settings['btn_link']);
}
if (!empty($settings['btn_link2']['url'])) {
    $this->add_link_attributes('btn_link2', $settings['btn_link2']);
}
$this->add_render_attribute('title', 'class', 'hero_title bk_hero_title hero_title_3');
?>
<section id="bk-hero3" class="bk-hero3-sec">
    <div class="bk-hero3-wrapper position-relative">
        <div class="container">
            <div class="bk-hero3-content d-flex justify-content-between">
                <span class="bg_shape"></span>
                <?php if (($settings['search_hide'] ?? '') === 'yes' && !empty($settings['booking_shortcode'])): ?>
                    <div class="bk-hero3-form position-relative">
                        <div class="form-wrapper headline">
                            <?php if (!empty($settings['search_title'])): ?>
                                <h3 class="text-center"><?php echo bookora_wp_kses($settings['search_title']); ?></h3>
                            <?php endif; ?>
                            <?php echo do_shortcode($settings['booking_shortcode']); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="bk-hero3-text">
                    <div class="bk-hero-text  headline">
                        <?php if (!empty($settings['subtitle'])): ?>
                            <div class="hero-slug"><i></i><?php echo bookora_wp_kses($settings['subtitle']); ?> <i></i>
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
                        <div class="btn-wrap d-flex  flex-wrap">
                            <?php if (!empty($settings['btn_label'])): ?>
                                <a <?php echo $this->get_render_attribute_string('btn_link'); ?>><?php echo bookora_wp_kses($settings['btn_label']); ?></a>
                            <?php endif; ?>
                            <?php if (!empty($settings['btn_label2'])): ?>
                                <a <?php echo $this->get_render_attribute_string('btn_link2'); ?>><?php echo bookora_wp_kses($settings['btn_label2']); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if (!empty($settings['image']['url'])): ?>
            <div class="bk-hr3-img">
                <div class="item-img img-cover">
                    <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="<?php if (!empty($settings['image']['alt'])) {
                           echo esc_attr($settings['image']['alt']);
                       } else {
                           esc_attr_e('List', 'ternat-plugin');
                       } ?>">
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>