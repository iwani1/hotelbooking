<footer id="bk-footer2" class="bk-footer2-sec">
    <div class="bk-footer2-top">
        <?php if (!empty($settings['map'])): ?>
            <div class="bk-ftr2-widget">
                <div class="map-widget">
                    <?php echo bookora_wp_kses($settings['map']); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($settings['reserves'])): ?>
            <div class="bk-ftr2-widget">
                <div class="cta-widget headline">
                    <div class="item-icon">
                        <?php \Elementor\Icons_Manager::render_icon($settings['rvp_icon'], ['aria-hidden' => 'true']); ?>
                    </div>
                    <?php if (!empty($settings['rsv_title'])): ?>
                        <h3 class="widget-title"><span><?php echo bookora_wp_kses($settings['rsv_title']); ?></span></h3>
                    <?php endif; ?>
                    <?php foreach ($settings['reserves'] as $item): ?>
                        <div class="item-info">
                            <h4><?php echo bookora_wp_kses($item['title']); ?></h4>
                            <span><?php echo bookora_wp_kses($item['info']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($settings['contacts'])): ?>
            <div class="bk-ftr2-widget">
                <div class="cta-widget headline ver_2">
                    <div class="item-icon">
                        <?php \Elementor\Icons_Manager::render_icon($settings['ct_icon'], ['aria-hidden' => 'true']); ?>
                    </div>
                    <?php if (!empty($settings['ct_title'])): ?>
                        <h3 class="widget-title"><span><?php echo bookora_wp_kses($settings['ct_title']); ?></span></h3>
                    <?php endif; ?>
                    <?php foreach ($settings['contacts'] as $item): ?>
                        <div class="item-info">
                            <h4><?php echo bookora_wp_kses($item['title']); ?></h4>
                            <span><?php echo bookora_wp_kses($item['info']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="bk-footer2-bottom">
        <?php if (!empty($settings['links'])): ?>
            <div class="bk-ftr1-menu-area">
                <div class="bk-ftr1-menu-widget">
                    <?php foreach ($settings['links'] as $item): ?>
                        <div class="bk-ftr1-widget headline pera-content ul-li-block">
                            <div class="menu-widget">
                                <?php if (!empty($item['title'])): ?>
                                    <h3 class="widget-title"><span><?php echo bookora_wp_kses($item['title']); ?></span></h3>
                                <?php endif; ?>
                                <ul>
                                    <?php foreach ($item['links_inr'] as $link): ?>
                                        <li><a
                                                href="<?php echo esc_url($link['link']['url']); ?>"><?php echo bookora_wp_kses($link['title']); ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($settings['newsletter_shortcode'])): ?>
            <div class="bk-ftr2-newslatter">
                <div class="bk-ftr2-widget">
                    <div class="newsltr-widget pera-content headline">
                        <?php echo do_shortcode($settings['newsletter_shortcode']); ?>
                    </div>
                </div>
            </div><?php endif; ?>
    </div>
    <div class="bk-ftr-copyright">
        <div class="container">
            <div class="copyright-text pera-content d-flex align-items-center justify-content-between">
                <?php if (!empty($settings['copyright'])) {
                    echo bookora_wp_kses(wpautop($settings['copyright']));
                } ?>
                <?php if (!empty($settings['socials'])): ?>
                    <div class="inner-social">
                        <?php foreach ($settings['socials'] as $item): ?>
                            <a target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                                rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                                href="<?php echo esc_url($item['link']['url']); ?>">
                                <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($settings['developer_credit'])) {
                    echo bookora_wp_kses(wpautop($settings['developer_credit']));
                } ?>
            </div>
        </div>
    </div>
</footer>