<?php
if (!empty($settings['btn_link']['url'])) {
    $this->add_link_attributes('btn_link', $settings['btn_link']);
}
$this->add_render_attribute('title', 'class', 'elementor-gt-heading sec_title ver_2  bk-itm-title bk-itm-anim');
?>
<div class="bk-ab3-content">
    <div class="bk-ab3-text-wrap">
        <div class="bk-sec-title  headline pera-content">
            <?php
            printf(
                '<%1$s %2$s>%3$s</%1$s>',
                tag_escape($settings['title_tag']),
                $this->get_render_attribute_string('title'),
                nl2br(bookora_wp_kses($settings['title']))
            );
            ?>
            <div class="bk-ab3-title mt-50 justify-content-between d-flex">
                <?php if (!empty($settings['subtitle'])): ?>
                    <div class="subtitle">
                        <i></i> <?php echo bookora_wp_kses($settings['subtitle']); ?> <i></i>
                    </div>
                <?php endif; ?>
                <div class="item-desc pera-content">
                    <?php if (!empty($settings['description'])) {
                        echo bookora_wp_kses(wpautop($settings['description']));
                    } ?>
                    <?php if (!empty($settings['btn_label'])): ?>
                        <div class="bk-btn1 mt-25">
                            <a <?php echo $this->get_render_attribute_string('btn_link'); ?>><?php echo bookora_wp_kses($settings['btn_label']); ?> </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if (!empty($settings['counters'])): ?>
            <div class="bk-ab3-ft-wrap mt-70 d-flex">
                <?php $i = 0;
                foreach ($settings['counters'] as $item):
                    $i++; ?>
                    <div class="bk-ab3-ft-item <?php if ($i == 2) {
                        echo esc_attr('ver_2');
                    } ?>" <?php if (!empty($item['bg_img']['url'])): ?>
                            data-background="<?php echo esc_url($item['bg_img']['url']); ?>" <?php endif; ?>>
                        <span class="b-title"><?php echo bookora_wp_kses($item['title']); ?></span>
                        <div class="item-text headline pera-content">
                            <h3><span class="odometer" data-count="<?php echo bookora_wp_kses($item['number']); ?>"></span><?php if (!empty($item['prefix'])) {
                                   echo esc_attr($item['prefix']);
                               } ?>
                            </h3>
                            <p><?php echo bookora_wp_kses($item['desc']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php if (!empty($settings['image']['url'])): ?>
        <div class="bk-ab3-img-wrap bk-img-app img-cover">
            <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="<?php if (!empty($settings['image']['alt'])) {
                   echo esc_attr($settings['image']['alt']);
               } else {
                   esc_attr_e('List', 'ternat-plugin');
               } ?>">
        </div>
    <?php endif; ?>
</div>