<?php
if (!empty($settings['btn_link']['url'])) {
    $this->add_link_attributes('btn_link', $settings['btn_link']);
}
?>

<div class="bk-team-content mt-60">
    <?php $i = 0;
    foreach ($settings['teams'] as $item):
        $i++; ?>
        <div class="bk-team-item">
            <div class="item-img position-relative">
                <div class="inner-img img-cover">
                    <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php if (!empty($item['image']['alt'])) {
                           echo esc_attr($item['image']['alt']);
                       } else {
                           esc_attr_e('List', 'bookora-plugin');
                       } ?>">
                </div>
                <div class="inner-social">
                    <?php foreach ($item['social_links'] as $s_icon): ?>
                        <a target="<?php echo esc_attr($s_icon['social_link']['is_external'] ? '_blank' : '_self'); ?>"
                            rel="<?php echo esc_attr($s_icon['social_link']['nofollow'] ? 'nofollow' : ''); ?>"
                            href="<?php echo esc_url($s_icon['social_link']['url']); ?>" aria-label="name">
                            <?php \Elementor\Icons_Manager::render_icon($s_icon['social_icon'], ['aria-hidden' => 'true']); ?>
                        </a>
                    <?php endforeach; ?>
                    <span><i class="fa-solid fa-plus"></i></span>
                </div>
            </div>
            <div class="item-text headline pera-content">
                <h3><a target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                        rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                        href="<?php echo esc_url($item['link']['url']); ?>"><?php echo bookora_wp_kses($item['title']); ?></a>
                </h3>
                <?php if (!empty($item['designation'])): ?>
                    <span><?php echo bookora_wp_kses($item['designation']); ?></span>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>