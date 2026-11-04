<section id="bk-faci2" class="bk-faci2-sec position-relative pt-120 pb-120">
    <div class="line_animation">
        <div class="line_area"></div>
        <div class="line_area"></div>
        <div class="line_area"></div>
        <div class="line_area"></div>
        <div class="line_area"></div>
        <div class="line_area"></div>
        <div class="line_area"></div>
        <div class="line_area"></div>
        <div class="line_area"></div>
        <div class="line_area"></div>
    </div>
    <div class="container">
        <div class="bk-faci2-title text-center headline pera-content">
            <?php if (!empty($settings['title'])): ?>
                <h2 class="text-uppercase"><?php echo wp_kses_post($settings['title']); ?></h2>
            <?php endif; ?>
            <?php if (!empty($settings['descripiton'])) {
                echo wp_kses_post(wpautop($settings['descripiton']));
            } ?>
        </div>
        <div class="bk-faci2-content">
            <?php if (!empty($settings['facilitiess'])): ?>
                <div class="bk-faci2-item-wrap d-flex justify-content-between">
                    <?php foreach ($settings['facilitiess'] as $item): ?>
                        <div class="bk-faci2-item  <?php if (!empty($item['class'])) {
                            echo esc_attr($item['class']);
                        } ?>  text-center">
                            <div class="inner-item">
                                <div class="item-icon">
                                    <i><?php if ($item['type'] === 'image' && ($item['icon_img']['url'])): ?>
                                            <img src="<?php echo esc_url($item['icon_img']['url']); ?>" alt="<?php if (!empty($item['icon_img']['alt'])) {
                                                   echo esc_attr($item['icon_img']['alt']);
                                               } else {
                                                   esc_attr_e('List', 'agenriver-plugin');
                                               } ?>">
                                        <?php else: ?>
                                            <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                                        <?php endif; ?></i>
                                </div>
                                <div class="item-text headline pera-content">
                                    <h3><a target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                                            rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                                            href="<?php echo esc_url($item['link']['url']); ?>" aria-label="name">
                                            <?php echo bookora_wp_kses($item['title']); ?>
                                        </a>
                                    </h3>
                                    <?php if (!empty($item['description'])): ?>
                                        <p><?php echo bookora_wp_kses($item['description']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($settings['facilitiess2'])): ?>
                <div class="bk-faci2-item-wrap  d-flex justify-content-center">
                    <?php foreach ($settings['facilitiess2'] as $item): ?>

                        <div class="bk-faci2-item  <?php if (!empty($item['class'])) {
                            echo esc_attr($item['class']);
                        } ?>  text-center">
                            <div class="inner-item">
                                <div class="item-icon">
                                    <i><?php if ($item['type'] === 'image' && ($item['icon_img']['url'])): ?>
                                            <img src="<?php echo esc_url($item['icon_img']['url']); ?>" alt="<?php if (!empty($item['icon_img']['alt'])) {
                                                   echo esc_attr($item['icon_img']['alt']);
                                               } else {
                                                   esc_attr_e('List', 'agenriver-plugin');
                                               } ?>">
                                        <?php else: ?>
                                            <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                                        <?php endif; ?></i>
                                </div>
                                <div class="item-text headline pera-content">
                                    <h3><a target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                                            rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                                            href="<?php echo esc_url($item['link']['url']); ?>" aria-label="name">
                                            <?php echo bookora_wp_kses($item['title']); ?>
                                        </a>
                                    </h3>
                                    <?php if (!empty($item['description'])): ?>
                                        <p><?php echo bookora_wp_kses($item['description']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($settings['facilitiess3'])): ?>
                <div class="bk-faci2-item-wrap d-flex justify-content-between">
                    <?php foreach ($settings['facilitiess3'] as $item): ?>

                        <div class="bk-faci2-item  <?php if (!empty($item['class'])) {
                            echo esc_attr($item['class']);
                        } ?>  text-center">
                            <div class="inner-item">
                                <div class="item-icon">
                                    <i><?php if ($item['type'] === 'image' && ($item['icon_img']['url'])): ?>
                                            <img src="<?php echo esc_url($item['icon_img']['url']); ?>" alt="<?php if (!empty($item['icon_img']['alt'])) {
                                                   echo esc_attr($item['icon_img']['alt']);
                                               } else {
                                                   esc_attr_e('List', 'agenriver-plugin');
                                               } ?>">
                                        <?php else: ?>
                                            <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                                        <?php endif; ?></i>
                                </div>
                                <div class="item-text headline pera-content">
                                    <h3><a target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                                            rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                                            href="<?php echo esc_url($item['link']['url']); ?>" aria-label="name">
                                            <?php echo bookora_wp_kses($item['title']); ?>
                                        </a>
                                    </h3>
                                    <?php if (!empty($item['description'])): ?>
                                        <p><?php echo bookora_wp_kses($item['description']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($settings['facilitiess4'])): ?>
                <div class="bk-faci2-item-wrap d-flex justify-content-center">
                    <?php foreach ($settings['facilitiess4'] as $item): ?>

                        <div class="bk-faci2-item  <?php if (!empty($item['class'])) {
                            echo esc_attr($item['class']);
                        } ?>  text-center">
                            <div class="inner-item">
                                <div class="item-icon">
                                    <i><?php if ($item['type'] === 'image' && ($item['icon_img']['url'])): ?>
                                            <img src="<?php echo esc_url($item['icon_img']['url']); ?>" alt="<?php if (!empty($item['icon_img']['alt'])) {
                                                   echo esc_attr($item['icon_img']['alt']);
                                               } else {
                                                   esc_attr_e('List', 'agenriver-plugin');
                                               } ?>">
                                        <?php else: ?>
                                            <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                                        <?php endif; ?></i>
                                </div>
                                <div class="item-text headline pera-content">
                                    <h3><a target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                                            rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                                            href="<?php echo esc_url($item['link']['url']); ?>" aria-label="name">
                                            <?php echo bookora_wp_kses($item['title']); ?>
                                        </a>
                                    </h3>
                                    <?php if (!empty($item['description'])): ?>
                                        <p><?php echo bookora_wp_kses($item['description']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($settings['facilitiess5'])): ?>
                <div class="bk-faci2-item-wrap d-flex">
                    <?php foreach ($settings['facilitiess5'] as $item): ?>

                        <div class="bk-faci2-item  <?php if (!empty($item['class'])) {
                            echo esc_attr($item['class']);
                        } ?>  text-center">
                            <div class="inner-item">
                                <div class="item-icon">
                                    <i><?php if ($item['type'] === 'image' && ($item['icon_img']['url'])): ?>
                                            <img src="<?php echo esc_url($item['icon_img']['url']); ?>" alt="<?php if (!empty($item['icon_img']['alt'])) {
                                                   echo esc_attr($item['icon_img']['alt']);
                                               } else {
                                                   esc_attr_e('List', 'agenriver-plugin');
                                               } ?>">
                                        <?php else: ?>
                                            <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                                        <?php endif; ?></i>
                                </div>
                                <div class="item-text headline pera-content">
                                    <h3><a target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                                            rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                                            href="<?php echo esc_url($item['link']['url']); ?>" aria-label="name">
                                            <?php echo bookora_wp_kses($item['title']); ?>
                                        </a>
                                    </h3>
                                    <?php if (!empty($item['description'])): ?>
                                        <p><?php echo bookora_wp_kses($item['description']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($settings['facilitiess6'])): ?>
                <div class="bk-faci2-item-wrap d-flex justify-content-end">
                    <?php foreach ($settings['facilitiess6'] as $item): ?>

                        <div class="bk-faci2-item  <?php if (!empty($item['class'])) {
                            echo esc_attr($item['class']);
                        } ?>  text-center">
                            <div class="inner-item">
                                <div class="item-icon">
                                    <i><?php if ($item['type'] === 'image' && ($item['icon_img']['url'])): ?>
                                            <img src="<?php echo esc_url($item['icon_img']['url']); ?>" alt="<?php if (!empty($item['icon_img']['alt'])) {
                                                   echo esc_attr($item['icon_img']['alt']);
                                               } else {
                                                   esc_attr_e('List', 'agenriver-plugin');
                                               } ?>">
                                        <?php else: ?>
                                            <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                                        <?php endif; ?></i>
                                </div>
                                <div class="item-text headline pera-content">
                                    <h3><a target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
                                            rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
                                            href="<?php echo esc_url($item['link']['url']); ?>" aria-label="name">
                                            <?php echo bookora_wp_kses($item['title']); ?>
                                        </a>
                                    </h3>
                                    <?php if (!empty($item['description'])): ?>
                                        <p><?php echo bookora_wp_kses($item['description']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>