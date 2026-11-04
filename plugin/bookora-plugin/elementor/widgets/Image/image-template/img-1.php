<div class="bk-price-img position-relative wow slideInUp" data-wow-delay="100ms" data-wow-duration="1000ms">
    <?php if (!empty($settings['image']['url'])): ?>
        <div class="item-img img-parallax img-cover">
            <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="<?php if (!empty($settings['image']['alt'])) {
                   echo esc_attr($settings['image']['alt']);
               } else {
                   esc_attr_e('List', 'ternat-plugin');
               } ?>">
        </div><?php endif; ?>
    <?php if (!empty($settings['title'])): ?>
        <div class="item-text headline">
            <h3><?php echo bookora_wp_kses($settings['title']); ?></h3>
        </div>
    <?php endif; ?>
</div>