<div class="bk-cus-content mt-60">
    <?php $i = 0;
    foreach ($settings['services'] as $item):
        $i++; ?>
        <div class="bk-cus-item">
            <div class="inner-item">
                <div class="item-icon d-flex align-items-center justify-content-center">
                    <?php if ($item['type'] === 'image' && ($item['icon_img']['url'])): ?>
                        <img src="<?php echo esc_url($item['icon_img']['url']); ?>" alt="<?php if (!empty($item['icon_img']['alt'])) {
                               echo esc_attr($item['icon_img']['alt']);
                           } else {
                               esc_attr_e('List', 'agenriver-plugin');
                           } ?>">
                    <?php else: ?>
                        <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                    <?php endif; ?>
                </div>
                <div class="item-text headline pera-content">
                    <h3><?php echo bookora_wp_kses($item['title']); ?></h3>
                    <?php if (!empty($item['description'])): ?>
                        <p><?php echo bookora_wp_kses($item['description']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>