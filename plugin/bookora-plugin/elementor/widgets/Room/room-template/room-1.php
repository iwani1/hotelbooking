<div id="bk-room3" class="bk-room3-sec over-hidden">
    <div class="bk-room3-wrapper">
        <div class="bk-room3-content  position-relative">
            <div class="bk-room3-img">
                <?php $i = 0;
                foreach ($settings['rooms'] as $item):
                    $i++ ?>
                    <div class="item_<?php echo esc_attr($i); ?>_img img-cover">
                        <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php if (!empty($item['image']['alt'])) {
                               echo esc_attr($item['image']['alt']);
                           } else {
                               esc_attr_e('List', 'bookora-plugin');
                           } ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="bk-room3-wrap position-relative">
                <?php $i = 0;
                foreach ($settings['rooms'] as $item):
                    $i++ ?>
                    <div class="bk-room3-item item_<?php echo esc_attr($i); ?>">
                        <?php if (!empty($settings['bg_img']['url'])): ?>
                            <span class="item-bg img-cover position-absolute">
                                <img src="<?php echo esc_url($settings['bg_img']['url']); ?>" alt="<?php if (!empty($settings['bg_img']['alt'])) {
                                       echo esc_attr($settings['bg_img']['alt']);
                                   } else {
                                       esc_attr_e('List', 'bookora-plugin');
                                   } ?>">
                            </span>
                        <?php endif; ?>
                        <div class="item-top mb-65 d-flex align-items-center justify-content-between">
                            <?php if (!empty($item['tag'])): ?>
                                <span><?php echo bookora_wp_kses($item['tag']); ?></span>
                            <?php endif; ?>
                            <?php if (!empty($item['count'])): ?>
                                <span><?php echo bookora_wp_kses($item['count']); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="item-img img-cover">
                            <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php if (!empty($item['image']['alt'])) {
                                   echo esc_attr($item['image']['alt']);
                               } else {
                                   esc_attr_e('List', 'bookora-plugin');
                               } ?>">
                        </div>
                        <div class="item-text text-center headline pera-content">
                            <h3><a target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                                    rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                                    href="<?php echo esc_url($item['link']['url']); ?>"><?php echo bookora_wp_kses($item['title']); ?></a>
                            </h3>
                            <?php if (!empty($item['description'])) {
                                echo bookora_wp_kses(wpautop($item['description']));
                            } ?>
                            <?php if (!empty($item['btn_label'])): ?>
                                <a class="read_more"
                                    target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                                    rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                                    href="<?php echo esc_url($item['link']['url']); ?>"><?php echo bookora_wp_kses($item['btn_label']); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>