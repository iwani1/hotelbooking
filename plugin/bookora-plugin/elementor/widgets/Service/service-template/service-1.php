<?php
$this->add_render_attribute('title', 'class', 'elementor-gt-heading sec_title  bk-itm-title bk-itm-anim');
if (!empty($settings['btn_link']['url'])) {
    $this->add_link_attributes('btn_link', $settings['btn_link']);
}
?>
<section id="bk-facilites" class="bk-facilites-sec">
    <div class="bk-fc-bg" <?php if (!empty($settings['bg_img']['url'])): ?>
            data-background="<?php echo esc_url($settings['bg_img']['url']); ?>" <?php endif; ?>>
        <div class="container">
            <div class="bk-facilites-content d-flex justify-content-between">
                <div class="bk-facilites-text">
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
                    <?php if (!empty($settings['btn_label'])): ?>
                        <div class="bk-btn1 mt-30">
                            <a <?php echo $this->get_render_attribute_string('btn_link'); ?>><?php echo bookora_wp_kses($settings['btn_label']); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="bk-facilites-card-wrap">
                    <?php $i = 0;
                    foreach ($settings['services'] as $item):
                        $i++; ?>
                        <div class="bk-facilites-card text-center wow slideInUp"
                            data-wow-delay="<?php echo esc_attr($i); ?>00ms" data-wow-duration="1000ms">
                            <div class="item-icon d-flex justify-content-center align-items-center">
                                <i>
                                    <?php if ($item['type'] === 'image' && ($item['icon_img']['url'])): ?>
                                        <img src="<?php echo esc_url($item['icon_img']['url']); ?>" alt="<?php if (!empty($item['icon_img']['alt'])) {
                                               echo esc_attr($item['icon_img']['alt']);
                                           } else {
                                               esc_attr_e('List', 'agenriver-plugin');
                                           } ?>">
                                    <?php else: ?>
                                        <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                                    <?php endif; ?>
                                </i>
                            </div>
                            <div class="item-text headline pera-content">
                                <h3><?php echo bookora_wp_kses($item['title']); ?></h3>
                                <?php if (!empty($item['description'])): ?>
                                    <p><?php echo bookora_wp_kses($item['description']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>