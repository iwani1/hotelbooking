<section id="jr-cta2" class="jr-cta2-sec over-hidden position-relative">
    <?php if (!empty($settings['image']['url'])): ?>
        <span class="jr-cta2-bg img-parallax img-cover position-absolute">
            <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="<?php if (!empty($settings['image']['alt'])) {
                   echo esc_attr($settings['image']['alt']);
               } else {
                   esc_attr_e('List', 'ternat-plugin');
               } ?>">
        </span>
    <?php endif; ?>
    <div class="container">
        <div class="jr-cta2-content d-flex justify-content-between flex-wrap align-items-end">
            <div class="jr-cta2-text">
                <?php if (!empty($settings['image2']['url'])): ?>
                    <div class="item-logo mb-45">
                        <i><img src="<?php echo esc_url($settings['image2']['url']); ?>" alt="<?php if (!empty($settings['image2']['alt'])) {
                               echo esc_attr($settings['image2']['alt']);
                           } else {
                               esc_attr_e('List', 'ternat-plugin');
                           } ?>"></i>
                    </div>
                <?php endif; ?>
                <div class="jr-sec-title  headline pera-content">
                    <?php if (!empty($settings['sub_title'])): ?>
                        <div class="subtitle  jr-sub-tilte jr-sub-anim">
                            <?php echo bookora_wp_kses($settings['sub_title']) ?>
                        </div>
                    <?php endif; ?>
                    <h2 class="sec_title  tx-split-text split-in-up">
                        <?php echo bookora_wp_kses($settings['title']) ?>
                        <?php if (!empty($settings['shape']['url'])): ?>
                            <i><img src="<?php echo esc_url($settings['shape']['url']); ?>" alt="<?php if (!empty($settings['shape']['alt'])) {
                                   echo esc_attr($settings['shape']['alt']);
                               } else {
                                   esc_attr_e('List', 'ternat-plugin');
                               } ?>"></i>
                        <?php endif; ?>
                        <?php echo bookora_wp_kses($settings['title2']) ?>
                    </h2>
                </div>
            </div>
            <?php if (!empty($settings['btn_label'])): ?>
                <div class="jr-btn mb-20 d-flex ">
                    <a <?php echo $this->get_render_attribute_string('btn_link'); ?>><span><?php echo bookora_wp_kses($settings['btn_label']) ?></span> <i
                            class="fa-solid fa-arrow-right"></i></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>