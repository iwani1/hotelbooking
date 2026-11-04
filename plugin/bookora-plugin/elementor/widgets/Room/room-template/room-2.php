<div id="bk-project3" class="bk-project3-sec position-relative">
    <div class="bk-project3-slider swiper-container">
        <div class="swiper-wrapper">
            <?php $i = 0;
            foreach ($settings['rooms'] as $item):
                $i++ ?>
                <div class="swiper-slide">
                    <div class="bk-project3-item position-relative">
                        <div class="item-img img-cover">
                            <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php if (!empty($item['image']['alt'])) {
                                   echo esc_attr($item['image']['alt']);
                               } else {
                                   esc_attr_e('List', 'bookora-plugin');
                               } ?>">
                        </div>
                        <div class="item-card-wrap">
                            <div class="item-icon d-flex justify-content-center align-items-center">
                                <i><?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?></i>
                            </div>
                            <div class="inner-img img-cover">
                                <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php if (!empty($item['image']['alt'])) {
                                       echo esc_attr($item['image']['alt']);
                                   } else {
                                       esc_attr_e('List', 'bookora-plugin');
                                   } ?>">
                            </div>
                            <div class="item-text text-center headline pera-content">
                                <?php if (!empty($item['tag'])): ?>
                                    <span><?php echo bookora_wp_kses($item['tag']); ?></span>
                                <?php endif; ?>
                                <h3><a target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                                        rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                                        href="<?php echo esc_url($item['link']['url']); ?>"><?php echo bookora_wp_kses($item['title']); ?></a>
                                </h3>
                                <?php if (!empty($item['description'])) {
                                    echo bookora_wp_kses(wpautop($item['description']));
                                } ?>
                                <?php if (!empty($item['btn_label'])): ?>
                                    <div class="bk-btn1">
                                        <a target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                                            rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                                            href="<?php echo esc_url($item['link']['url']); ?>"><?php echo bookora_wp_kses($item['btn_label']); ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="bk-pr3-pagi"></div>
</div>