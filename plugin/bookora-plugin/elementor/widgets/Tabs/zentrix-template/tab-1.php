<div class="ss-feat-content">
    <div class="ss-feat-btn-wrap mt-20 mb-65 text-center tx-tab-btn ul-li">
        <ul class="nav nav-tabs" id="mt-about-tab" role="tablist">
            <?php
            $tab_count = 0;
            foreach ($settings['tabs'] as $index => $item):
                $tab_count++;
                $tab_id = $tab_count;
                $active_class = ($index === 0) ? 'active' : '';
                ?>
                <li class="nav-item" role="presentation">
                    <div class="nav-link <?php echo esc_attr($active_class); ?>"
                        id="ft_tab_<?php echo esc_attr($tab_id); ?>" data-bs-toggle="tab"
                        data-bs-target="#ft_1_<?php echo esc_attr($tab_id); ?>" role="tab">
                        <?php if (!empty($item['tab_title'])): ?>
                            <div class="ss-feat-btn">
                                <span><i class="fa-solid fa-plus"></i>
                                    <?php echo bookora_wp_kses($item['tab_title']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="ss-feat-tab-desc">
        <div class="tab-content" id="myTabContent">
            <?php
            $content_count = 0;
            foreach ($settings['tabs'] as $index => $item):
                $content_count++;
                $content_id = $content_count;
                $active_class = ($index === 0) ? 'show active' : '';
                ?>
                <div class="tab-pane <?php echo esc_attr($active_class); ?> animated fade"
                    id="ft_1_<?php echo esc_attr($content_id); ?>" role="tabpanel">
                    <div class="ss-feat-item d-flex">
                        <div class="item-img-wrap position-relative">
                            <?php if (!empty($item['image']['url'])): ?>
                                <div class="item-img1">
                                    <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php if (!empty($item['image']['alt'])) {
                                           echo esc_attr($item['image']['alt']);
                                       } else {
                                           esc_attr_e('List', 'bookora-plugin');
                                       } ?>">
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($item['image2']['url'])): ?>
                                <div class="item-img2 position-absolute">
                                    <img src="<?php echo esc_url($item['image2']['url']); ?>" alt="<?php if (!empty($item['image2']['alt'])) {
                                           echo esc_attr($item['image2']['alt']);
                                       } else {
                                           esc_attr_e('List', 'bookora-plugin');
                                       } ?>">
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($item['image3']['url'])): ?>
                                <div class="item-img3 position-absolute">
                                    <img src="<?php echo esc_url($item['image3']['url']); ?>" alt="<?php if (!empty($item['image3']['alt'])) {
                                           echo esc_attr($item['image3']['alt']);
                                       } else {
                                           esc_attr_e('List', 'bookora-plugin');
                                       } ?>">
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="item-text-wrap headline pera-content ul-li">
                            <h3><?php echo bookora_wp_kses($item['title']); ?></h3>
                            <?php if (!empty($item['description'])) {
                                echo bookora_wp_kses(wpautop($item['description']));
                            } ?>
                            <?php if (!empty($item['lists'])): ?>
                                <ul>
                                    <?php foreach ($item['lists'] as $list): ?>
                                        <li>
                                            <i><?php \Elementor\Icons_Manager::render_icon($list['icon'], ['aria-hidden' => 'true']); ?></i>
                                            <?php echo bookora_wp_kses($list['title']); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            <?php if (!empty($item['btn_label'])): ?>
                                <div class="ss-btn mt-20">
                                    <a target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                                        rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                                        href="<?php echo esc_url($item['link']['url']); ?>"><?php echo bookora_wp_kses($item['btn_label']); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>