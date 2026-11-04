<?php
$this->add_render_attribute('title', 'class', 'elementor-gt-heading sec_title  bk-itm-title bk-itm-anim');
if (!empty($settings['btn_link']['url'])) {
    $this->add_link_attributes('btn_link', $settings['btn_link']);
}
if (!empty($settings['btn_link2']['url'])) {
    $this->add_link_attributes('btn_link2', $settings['btn_link2']);
}
?>
<div class="bk-gallery-content d-flex">
    <div class="bk-gallery-text">
        <div class="bk-sec-title  headline pera-content">
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
        <?php if (!empty($settings['btn_label']) || !empty($settings['btn_label2'])): ?>
            <div class="btn-wrap mt-40 d-flex  flex-wrap">
                <a <?php echo $this->get_render_attribute_string('btn_link'); ?>><?php echo bookora_wp_kses($settings['btn_label']); ?></a>
                <a <?php echo $this->get_render_attribute_string('btn_link2'); ?>><?php echo bookora_wp_kses($settings['btn_label2']); ?></a>
            </div>
        <?php endif; ?>
    </div>
    <div class="bk-gallery-slide-area">
        <div class="bk-gallery-slider swiper-container">
            <div class="swiper-wrapper">
                <?php foreach ($settings['gallerys'] as $item): ?>
                    <div class="swiper-slide">
                        <div class="bk-gallery-item">
                            <div class="item-img zoom-gallery img-cover">
                                <a href="<?php echo esc_url($item['image']['url']); ?>"
                                    data-source="<?php echo esc_url($item['image']['url']); ?>"><img
                                        src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php if (!empty($item['image']['alt'])) {
                                               echo esc_attr($item['image']['alt']);
                                           } else {
                                               esc_attr_e('List', 'ternat-plugin');
                                           } ?>">
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="bk-gl-nav">
            <div class="bk-gl-prev d-flex align-items-center justify-content-center"><i
                    class="fa-solid fa-arrow-left"></i></div>
            <div class="bk-gl-next d-flex align-items-center justify-content-center"><i
                    class="fa-solid fa-arrow-right"></i></div>
        </div>
    </div>
</div>