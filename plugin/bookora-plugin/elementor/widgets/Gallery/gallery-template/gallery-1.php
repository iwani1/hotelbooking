<div class="bk-rooms-content mt-70 over-hidden">
    <div class="bk-rooms-marque marquee-left">
        <?php foreach ($settings['gallerys'] as $item): ?>
            <div class="bk-rooms-img1">
                <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php if (!empty($item['image']['alt'])) {
                       echo esc_attr($item['image']['alt']);
                   } else {
                       esc_attr_e('List', 'ternat-plugin');
                   } ?>">
            </div>
        <?php endforeach; ?>
    </div>
</div>