<?php
if (!empty($settings['btn_link']['url'])) {
    $this->add_link_attributes('btn_link', $settings['btn_link']);
}
?>
<div class="tc-case-content pt-60">
    <div class="tc-case-top pt-55 pb-55 flex-wrap d-flex align-items-center justify-content-between">
        <?php if (!empty($settings['description'])): ?>
            <div class="item-text">
                <?php echo bookora_wp_kses($settings['description']); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($settings['btn_label'])): ?>
            <div class="tc-btn1  d-flex  align-items-center">
                <a <?php echo $this->get_render_attribute_string('btn_link'); ?>><span><?php echo bookora_wp_kses($settings['btn_label']); ?> </span> <i
                        class="fa-solid fa-chevron-right"></i></a>
            </div>
        <?php endif; ?>
    </div>
    <div class="tc-case-item-wrap">
        <div class="tc-case-slider swiper-container">
            <div class="swiper-wrapper">
                <?php foreach ($settings['projects'] as $item): ?>
                    <div class="swiper-slide">
                        <div class="tc-case-item position-relative">
                            <?php if (!empty($item['image']['url'])): ?>
                                <div class="item-img img-cover">
                                    <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php if (!empty($item['image']['alt'])) {
                                           echo esc_attr($item['image']['alt']);
                                       } else {
                                           esc_attr_e('List', 'bookora-plugin');
                                       } ?>">
                                </div>
                            <?php endif; ?>
                            <div class="item-text headline pera-content">
                                <div class="inner-text text-center">
                                    <h3><a target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                                            rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                                            href="<?php echo esc_url($item['link']['url']); ?>"><?php echo bookora_wp_kses($item['title']); ?></a>
                                    </h3>
                                    <?php if (!empty($item['description'])) {
                                        echo bookora_wp_kses(wpautop($item['description']));
                                    } ?>
                                </div>
                                <?php if (!empty($settings['btn_label'])): ?>
                                    <a class="cs_btn"
                                        target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                                        rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                                        href="<?php echo esc_url($item['link']['url']); ?>"><?php echo bookora_wp_kses($item['btn_label']); ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>