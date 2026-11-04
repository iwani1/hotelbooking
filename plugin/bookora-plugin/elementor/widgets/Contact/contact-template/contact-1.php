<?php
$this->add_render_attribute('title', 'class', 'elementor-gt-heading sec_title  tx-split-text split-in-up');
?>
<section id="tc-contact" class="tc-contact-sec over-hidden position-relative">
    <?php if (!empty($settings['bg_img'])): ?>
        <div class="tc-contact-bg position-absolute img-parallax img-cover">
            <img src="<?php echo esc_url($settings['bg_img']['url']); ?>" alt="<?php if (!empty($settings['bg_img']['alt'])) {
                   echo esc_attr($settings['bg_img']['alt']);
               } else {
                   esc_attr_e('List', 'bookora-plugin');
               } ?>">
        </div>
    <?php endif; ?>
    <div class="container">
        <div class="tc-contact-content d-flex justify-content-between">
            <div class="tc-contact-text">
                <div class="tc-sec-title  headline">
                    <?php if (!empty($settings['subtitle'])): ?>
                        <div class="subtitle">
                            <?php echo bookora_wp_kses($settings['subtitle']); ?>
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
            </div>
            <div class="tc-contact-form" <?php if (!empty($settings['bg_form_img']['url'])): ?>
                    data-background="<?php echo esc_url($settings['bg_form_img']['url']); ?>" <?php endif; ?>>
                <div class="item-title mb-30 headline pera-content">
                    <?php if (!empty($settings['form_title'])): ?>
                        <h2><?php echo bookora_wp_kses($settings['form_title']); ?></h2>
                    <?php endif; ?>
                    <?php if (!empty($settings['form_text'])) {
                        echo bookora_wp_kses($settings['form_text']);
                    } ?>
                </div>
                <?php if (!empty($settings['form_shortcode'])) {
                    echo do_shortcode($settings['form_shortcode']);
                } ?>
            </div>
        </div>
    </div>
</section>