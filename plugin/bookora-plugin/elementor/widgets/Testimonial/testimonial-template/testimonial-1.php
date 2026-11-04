<?php
$this->add_render_attribute('title', 'class', 'elementor-gt-heading sec_title  tx-split-text split-in-up');
?>
<div class="bk-testimonial-sec over-hidden">
    <div class="bk-testimonial-content position-relative">
        <?php if (!empty($settings['tst_bg'])): ?>
            <div class="bk-testi-bg img-cover">
                <img src="<?php echo esc_url($settings['tst_bg']['url']); ?>" alt="<?php if (!empty($settings['tst_bg']['alt'])) {
                       echo esc_attr($settings['tst_bg']['alt']);
                   } ?>">
            </div>
        <?php endif; ?>
        <div class="bk-testi-item-wrap position-relative">
            <?php foreach ($settings['testimonials'] as $item): ?>
                <div class="bk-testi-area">
                    <div class="bk-testi-item ul-li headline pera-content">
                        <ul>
                            <?php for ($i = 0; $i < $item['rating']; $i++): ?>
                                <li><i class="fa-solid fa-star"></i></li>
                            <?php endfor; ?>
                        </ul>
                        <?php if (!empty($item['feedback'])) {
                            echo bookora_wp_kses(wpautop($item['feedback']));
                        } ?>
                        <div class="item-author-icon d-flex align-items-center justify-content-between">
                            <div class="item-author d-flex align-items-center">
                                <?php if (!empty($item['authore']['url'])): ?>
                                    <div class="auth-img img-cover">
                                        <img src="<?php echo esc_url($item['authore']['url']); ?>" alt="<?php if (!empty($item['authore']['alt'])) {
                                               echo esc_attr($item['authore']['alt']);
                                           } ?>">
                                    </div>
                                <?php endif; ?>
                                <div class="auth-text">
                                    <?php if (!empty($item['designation'])): ?>
                                        <h3><?php echo bookora_wp_kses($item['designation']) ?></h3>
                                    <?php endif; ?>
                                    <?php if (!empty($item['name'])): ?>
                                        <span><?php echo bookora_wp_kses($item['name']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if (!empty($item['link']['url'])): ?>
                                <div class="item-icon">
                                    <a target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                                        rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                                        href="<?php echo esc_url($item['link']['url']); ?>"><i
                                            class="fa-brands fa-x-twitter"></i></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>