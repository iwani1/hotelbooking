<?php
if (!empty($settings['btn_link']['url'])) {
    $this->add_link_attributes('btn_link', $settings['btn_link']);
}
?>
<div class="bk-footer1-widget-area">
    <div class="container">
        <div class="bk-footer1-widget-wrap d-flex">
            <div class="bk-ftr1-widget headline pera-content">
                <div class="cta-widget">
                    <div class="brand-logo">
                        <?php if (!empty($settings['logo']['url'])): ?>
                            <a href="<?php echo esc_url(home_url()); ?>">
                                <img class="logo_site-size" src="<?php echo esc_url($settings['logo']['url']); ?>" alt="<?php if (!empty($settings['logo']['alt'])) {
                                       echo esc_attr($settings['logo']['alt']);
                                   } ?>">
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($settings['description'])) {
                            echo bookora_wp_kses(wpautop($settings['description']));
                        } ?>
                    </div>
                    <?php if (!empty($settings['ct_title'])): ?>
                        <h3 class="widget-title"><span><?php echo bookora_wp_kses($settings['ct_title']); ?></span></h3>
                    <?php endif; ?>
                    <?php if (!empty($settings['contacts'])): ?>
                        <div class="cta-item-wrap">
                            <?php foreach ($settings['contacts'] as $item): ?>
                                <div class="cta-info d-flex align-items-center">
                                    <div class="c-icon d-flex align-items-center justify-content-center">
                                        <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                                    </div>
                                    <div class="c-text">
                                        <?php echo bookora_wp_kses($item['title']); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($settings['socials'])): ?>
                        <div class="cta-social mt-40 d-flex">
                            <?php foreach ($settings['socials'] as $item): ?>
                                <a href="<?php echo esc_url($item['link']['url']); ?>">
                                    <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?></a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (!empty($settings['links'])): ?>
                <div class="bk-ftr1-menu-widget">
                    <?php foreach ($settings['links'] as $item): ?>
                        <div class="bk-ftr1-widget headline pera-content ul-li-block">
                            <div class="menu-widget">
                                <?php if (!empty($item['title'])): ?>
                                    <h3 class="widget-title">
                                        <span><?php echo bookora_wp_kses($item['title']); ?></span>
                                    </h3>
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
            <?php endif; ?>
            <?php if (!empty($settings['newsletter_shortcode'])): ?>
                <div class="bk-ftr1-widget headline pera-content">
                    <div class="newslatter-widget">
                        <?php echo do_shortcode($settings['newsletter_shortcode']); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>