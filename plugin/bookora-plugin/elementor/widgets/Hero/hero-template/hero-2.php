<?php
if (!empty($settings['btn_link']['url'])) {
    $this->add_link_attributes('btn_link', $settings['btn_link']);
}
if (!empty($settings['btn_link2']['url'])) {
    $this->add_link_attributes('btn_link2', $settings['btn_link2']);
}
$this->add_render_attribute('title', 'class', 'elementor-gt-heading hero_title bk_hero_title hero_title_1');
?>
<section id="bk-hero2" class="bk-hero2-sec over-hidden position-relative">
    <div class="bk-hero2-bg position-absolute img-cover">
        <video autoplay="" muted="" loop="" playsinline="" preload="metadata">
            <source src="<?php
            if ($settings['video_type'] == 'exu') {
                echo esc_url($settings['video_links']['url']);
            } else {
                echo esc_url($settings['video_upload']['url']);
            }
            ?>" type="video/mp4">
        </video>
    </div>
    <div class="container">
        <div class="bk-hero2-text  text-center headline">
            <?php if (!empty($settings['subtitle'])): ?>
                <div class="hero-slug text-uppercase"><i></i><?php echo bookora_wp_kses($settings['subtitle']); ?> <i></i>
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
            <div class="btn-wrap d-flex justify-content-center flex-wrap">
                <?php if (!empty($settings['btn_label'])): ?>
                    <a <?php echo $this->get_render_attribute_string('btn_link'); ?>><?php echo bookora_wp_kses($settings['btn_label']); ?></a>
                <?php endif; ?>
                <?php if (!empty($settings['btn_label2'])): ?>
                    <a <?php echo $this->get_render_attribute_string('btn_link2'); ?>><?php echo bookora_wp_kses($settings['btn_label2']); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>