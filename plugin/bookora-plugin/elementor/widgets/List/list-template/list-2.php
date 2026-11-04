<div class="tc-why-c-ft-video pt-20 d-flex align-items-end">
    <div class="tc-why-c-ft">
        <?php foreach ($settings['lists'] as $item): ?>
            <div class="tc-wc-ft-item d-flex">
                <div class="item-icon d-flex align-items-center justify-content-center">
                    <i><img src="<?php echo esc_url($item['img']['url']); ?>" alt="<?php if (!empty($item['img']['alt'])) {
                           echo esc_attr($item['img']['alt']);
                       } else {
                           esc_attr_e('List', 'bookora-plugin');
                       } ?>"></i>
                </div>
                <div class="item-text headline pera-content">
                    <h3><?php echo bookora_wp_kses($item['title']); ?></h3>
                    <?php if (!empty($item['description'])) {
                        echo bookora_wp_kses(wpautop($item['description']));
                    } ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="tc-why-c-video">
        <div class="item-img img-cover over-hidden">
            <video autoplay="" muted="" loop="" playsinline="" preload="metadata">
                <source src="<?php
                if ($settings['video_type'] == 'exu') {
                    echo esc_url($settings['video_link']['url']);
                } else {
                    echo esc_url($settings['video_upload']['url']);
                }
                ?>" type="video/mp4">
            </video>
        </div>
    </div>
</div>