<?php
$this->add_render_attribute('title', 'class', 'elementor-gt-heading sec_title  tx-split-text split-in-up');
if (!empty($settings['btn_link']['url'])) {
    $this->add_link_attributes('btn_link', $settings['btn_link']);
}
?>
<div class="bk-price-item wow slideInUp" data-wow-delay="200ms" data-wow-duration="1000ms">
    <div class="item-title headline pera-content">
        <?php if (!empty($settings['title'])): ?>
            <h3><?php echo bookora_wp_kses($settings['title']); ?></h3>
        <?php endif; ?>
        <?php if (!empty($settings['description'])) {
            echo bookora_wp_kses(wpautop($settings['description']));
        } ?>
    </div>
    <div class="item-price headline">
        <h3><?php if (!empty($settings['currency'])): ?><sup><?php echo bookora_wp_kses($settings['currency']); ?></sup><?php endif; ?><?php echo bookora_wp_kses($settings['price']); ?>
        </h3>
        <?php if (!empty($settings['period'])): ?>
            <span class="text-uppercase"><?php echo bookora_wp_kses($settings['period']); ?></span>
        <?php endif; ?>
    </div>
    <?php if (!empty($settings['btn_label'])): ?>
        <div class="item-btn mt-30 mb-30">
            <a <?php echo $this->get_render_attribute_string('btn_link'); ?>><?php echo bookora_wp_kses($settings['btn_label']); ?></a>
        </div>
    <?php endif; ?>
    <?php if (!empty($settings['lists'])): ?>
        <div class="item-list ul-li-block">
            <ul>
                <?php foreach ($settings['lists'] as $item): ?>
                    <li>
                        <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                        <?php echo bookora_wp_kses($item['title']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>