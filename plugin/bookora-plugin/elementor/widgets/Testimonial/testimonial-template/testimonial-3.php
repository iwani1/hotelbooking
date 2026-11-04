<div class="bk-testi3-content mt-50">
    <div class="bk-testi3-marque marquee-left">
        <?php foreach ($settings['testimonials'] as $item): ?>
            <div class="bk-testi3-item">
                <div class="item-top ul-li d-flex align-items-center justify-content-between">
                    <ul>
                        <?php for ($i = 0; $i < $item['rating']; $i++): ?>
                            <li><i class="fa-solid fa-star"></i></li>
                        <?php endfor; ?>
                    </ul>
                    <?php if (!empty($item['link']['url'])): ?>
                        <a target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                            rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                            href="<?php echo esc_url($item['link']['url']); ?>"><i class="fa-brands fa-x-twitter"></i></a>
                    <?php endif; ?>
                </div>
                <div class="item-text">
                    <?php if (!empty($item['feedback'])) {
                        echo bookora_wp_kses($item['feedback']);
                    } ?>
                </div>
                <div class="item-author headline d-flex align-items-center">
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
            </div>
        <?php endforeach; ?>
    </div>
    <div class="bk-testi3-marque marquee-right">
        <?php foreach (array_reverse($settings['testimonials']) as $item): ?>
            <div class="bk-testi3-item">
                <div class="item-top ul-li d-flex align-items-center justify-content-between">
                    <ul>
                        <?php for ($i = 0; $i < $item['rating']; $i++): ?>
                            <li><i class="fa-solid fa-star"></i></li>
                        <?php endfor; ?>
                    </ul>
                    <?php if (!empty($item['link']['url'])): ?>
                        <a target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                            rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                            href="<?php echo esc_url($item['link']['url']); ?>"><i class="fa-brands fa-x-twitter"></i></a>
                    <?php endif; ?>
                </div>
                <div class="item-text">
                    <?php if (!empty($item['feedback'])) {
                        echo bookora_wp_kses($item['feedback']);
                    } ?>
                </div>
                <div class="item-author headline d-flex align-items-center">
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
            </div>
        <?php endforeach; ?>
    </div>
</div>