<div class="bk-food-content mt-60">
    <div class="bk-food-slider swiper-container">
        <div class="swiper-wrapper">
            <?php foreach ($settings['packages'] as $item): ?>
                <div class="swiper-slide">
                    <div class="bk-food-item">
                        <div class="item-img-price over-hidden position-relative">
                            <div class="inner-img">
                                <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php if (!empty($item['image']['alt'])) {
                                       echo esc_attr($item['image']['alt']);
                                   } else {
                                       esc_attr_e('List', 'agenriver-plugin');
                                   } ?>">
                            </div>
                            <?php if (!empty($item['price'])): ?>
                                <div class="inner-price">
                                    <?php echo bookora_wp_kses($item['price']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="item-text text-center headline pera-content">
                            <h3><a target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                                    rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                                    href="<?php echo esc_url($item['link']['url']); ?>"><?php echo bookora_wp_kses($item['title']); ?></a>
                            </h3>
                            <?php if (!empty($item['description'])): ?>
                                <p><?php echo bookora_wp_kses($item['description']); ?></p>
                            <?php endif; ?>
                            <a class="more_arrow d-flex align-items-center justify-content-center"
                                target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                                rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                                href="<?php echo esc_url($item['link']['url']); ?>"><i
                                    class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>