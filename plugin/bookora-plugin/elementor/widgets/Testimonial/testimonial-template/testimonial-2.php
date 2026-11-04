<div id="bk-testi2" class="bk-testi2-sec over-hidden pb-120">
    <?php if (!empty($settings['title'])): ?>
        <div class="bk-testi-head text-center headline">
            <h2 class="text-uppercase bottom-text">
                <?php echo bookora_wp_kses(val: $settings['title']); ?>
            </h2>
        </div>
    <?php endif; ?>
    <div class="container">
        <div class="bk-testi2-content mt-50">
            <?php foreach ($settings['testimonials'] as $item): ?>
                <div class="bk-testi2-item pera-content">
                    <div class="inner-item">
                        <div class="item-author mb-60 d-flex align-items-center">
                            <?php if (!empty($item['authore']['url'])): ?>
                                <div class="item-img">
                                    <img src="<?php echo esc_url($item['authore']['url']); ?>" alt="<?php if (!empty($item['authore']['alt'])) {
                                           echo esc_attr($item['authore']['alt']);
                                       } ?>">
                                </div>
                            <?php endif; ?>
                            <div class="item-text headline pera-content">
                                <?php if (!empty($item['designation'])): ?>
                                    <h3><?php echo bookora_wp_kses($item['designation']) ?></h3>
                                <?php endif; ?>
                                <?php if (!empty($item['name'])): ?>
                                    <span><?php echo bookora_wp_kses($item['name']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if (!empty($item['feedback'])) {
                            echo bookora_wp_kses(wpautop($item['feedback']));
                        } ?>
                        <div class="item-bottom d-flex align-items-center justify-content-between ul-li">
                            <ul>
                                <?php for ($i = 0; $i < $item['rating']; $i++): ?>
                                    <li><i class="fa-solid fa-star"></i></li>
                                <?php endfor; ?>
                            </ul>
                            <?php if (!empty($item['link']['url'])): ?>
                                <span><a target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                                        rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                                        href="<?php echo esc_url($item['link']['url']); ?>"><i
                                            class="fa-brands fa-x-twitter"></i></a></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>