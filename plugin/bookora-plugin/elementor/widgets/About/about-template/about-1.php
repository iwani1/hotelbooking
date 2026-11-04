<?php
if (!empty($settings['btn_link']['url'])) {
    $this->add_link_attributes('btn_link', $settings['btn_link']);
}
$this->add_render_attribute('title', 'class', 'elementor-gt-heading sec_title  bk-itm-title bk-itm-anim');
?>
<div class="bk-about-content d-flex">
    <div class="bk-about-text">
        <div class="bk-about-wrap mb-60 d-flex">
            <?php if (!empty($settings['image']['url'])): ?>
                <div class="ab-img1 bk-img-app img-cover">
                    <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="<?php if (!empty($settings['image']['alt'])) {
                           echo esc_attr($settings['image']['alt']);
                       } else {
                           esc_attr_e('List', 'ternat-plugin');
                       } ?>">
                </div>
            <?php endif; ?>
            <div class="ab-text1 pt-15">
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
                    <?php if (!empty($settings['description'])) {
                        echo bookora_wp_kses(wpautop($settings['description']));
                    } ?>
                </div>
                <?php if (!empty($settings['btn_label'])): ?>
                    <div class="bk-btn1 mt-30">
                        <a <?php echo $this->get_render_attribute_string('btn_link'); ?>><?php echo bookora_wp_kses($settings['btn_label']); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php if (!empty($settings['counters'])): ?>
            <div class="bk-about-count d-flex justify-content-between">
                <?php foreach ($settings['counters'] as $item): ?>
                    <div class="bk-ab-count-item d-flex justify-content-between align-items-end headline pera-content">
                        <div class="item-text">
                            <h3><span class="odometer" data-count="<?php echo bookora_wp_kses($item['number']); ?>">00</span><?php if (!empty($item['prefix'])) {
                                   echo esc_attr($item['prefix']);
                               } ?>
                            </h3>
                            <p><?php echo bookora_wp_kses($item['title']); ?></p>
                        </div>
                        <div class="item-icon">
                            <i><?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?></i>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php if (!empty($settings['image2']['url'])): ?>
        <div class="bk-about-img bk-img-app img-cover">
            <img src="<?php echo esc_url($settings['image2']['url']); ?>" alt="<?php if (!empty($settings['image2']['alt'])) {
                   echo esc_attr($settings['image2']['alt']);
               } else {
                   esc_attr_e('List', 'ternat-plugin');
               } ?>">
        </div>
    <?php endif; ?>
</div>