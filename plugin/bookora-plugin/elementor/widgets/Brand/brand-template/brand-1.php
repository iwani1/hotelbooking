<div class="bk-footer1-sponsor">
    <div class="container">
        <div class="bk-ft-spon-area d-flex justify-content-end">
            <div class="bk-ft-spon-wrap">
                <div class="bk-ft-spon-slide swiper-container">
                    <div class="swiper-wrapper">
                        <?php foreach ($settings['brands'] as $item): ?>
                            <div class="swiper-slide">
                                <div class="bk-spon-item">
                                    <div class="item-img">
                                        <img src="<?php echo esc_url($item['brand_img']['url']); ?>" alt="<?php if (!empty($item['brand_img']['alt'])) {
                                               echo esc_attr($item['brand_img']['alt']);
                                           } else {
                                               esc_attr_e('List', 'aivora-plugin');
                                           } ?>">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>