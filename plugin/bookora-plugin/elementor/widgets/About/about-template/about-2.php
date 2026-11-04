<?php
if (!empty($settings['btn_link']['url'])) {
    $this->add_link_attributes('btn_link', $settings['btn_link']);
}
$this->add_render_attribute('title', 'class', 'elementor-gt-heading sec_title  bk-itm-title bk-itm-anim');
?>
<div class="bk-ab2-content">
    <div class="bk-ab2-img1">
        <?php if (!empty($settings['image']['url'])): ?>
            <div class="item-img bk-img-app">
                <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="<?php if (!empty($settings['image']['alt'])) {
                       echo esc_attr($settings['image']['alt']);
                   } else {
                       esc_attr_e('List', 'ternat-plugin');
                   } ?>">
            </div>
        <?php endif; ?>
        <div class="item-count headline pera-content d-flex align-items-center">
            <h3><span class="odometer" data-count="<?php echo bookora_wp_kses($settings['number']); ?>">00</span><?php if (!empty($settings['prefix'])) {
                   echo esc_attr($settings['prefix']);
               } ?>
            </h3>
            <p><?php echo bookora_wp_kses($settings['ct_title']); ?></p>
        </div>
    </div>
    <div class="bk-ab2-text-wrap">
        <div class="bk-ab2-top mb-75 d-flex justify-content-between">
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
            </div>
            <?php if (!empty($settings['description'])): ?>
                <div class="item-desc">
                    <?php echo bookora_wp_kses($settings['description']); ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="item-img-scroll position-relative">
            <?php if (!empty($settings['image2']['url'])): ?>
                <div class="item-scroll d-flex justify-content-center align-items-center">
                    <img src="<?php echo esc_url($settings['image2']['url']); ?>" alt="<?php if (!empty($settings['image2']['alt'])) {
                           echo esc_attr($settings['image2']['alt']);
                       } else {
                           esc_attr_e('List', 'ternat-plugin');
                       } ?>">
                </div>
            <?php endif; ?>
            <?php if (!empty($settings['image3']['url'])): ?>
                <div class="item-img">
                    <img src="<?php echo esc_url($settings['image3']['url']); ?>" alt="<?php if (!empty($settings['image3']['alt'])) {
                           echo esc_attr($settings['image3']['alt']);
                       } else {
                           esc_attr_e('List', 'ternat-plugin');
                       } ?>">
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>