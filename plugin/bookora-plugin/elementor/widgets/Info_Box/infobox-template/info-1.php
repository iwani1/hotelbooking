<?php
if (!empty($settings['link']['url'])) {
    $this->add_link_attributes('link', $settings['link']);
}
?>
<div class="wow fadeInUp" data-wow-delay="250ms" data-wow-duration="1200ms">
    <div class="jr-rev-card ver_2">
        <div class="inner-item">
            <div class="item-text headline pera-content">
                <h3 class="text-uppercase"><?php echo bookora_wp_kses($settings['title']); ?></h3>
                <?php if (!empty($settings['description'])) {
                    echo bookora_wp_kses(wpautop($settings['description']));
                } ?>
            </div>
            <?php if (!empty($settings['image']['url'])): ?>
                <div class="item-icon">
                    <i><img src="<?php echo esc_url($settings['image']['url']); ?>" alt="<?php if (!empty($settings['image']['alt'])) {
                           echo esc_attr($settings['image']['alt']);
                       } else {
                           esc_attr_e('List', 'bookora-plugin');
                       } ?>"></i>
                </div><?php endif; ?>
            <?php if (!empty($settings['info'])): ?>
                <h2><?php echo bookora_wp_kses($settings['info']); ?></h2>
            <?php endif; ?>
        </div>
    </div>
</div>