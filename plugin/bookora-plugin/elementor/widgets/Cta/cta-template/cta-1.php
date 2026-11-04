<?php
if (!empty($settings['btn_link']['url'])) {
    $this->add_link_attributes('btn_link', $settings['btn_link']);
}
?>
<section id="jr-cta" class="jr-cta-sec pt-130 pb-130 over-hidden position-relative">
    <?php if (!empty($settings['image']['url'])): ?>
        <div class="jr-cta-bg img-parallax img-cover position-absolute">
            <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="<?php if (!empty($settings['image']['alt'])) {
                   echo esc_attr($settings['image']['alt']);
               } else {
                   esc_attr_e('List', 'ternat-plugin');
               } ?>">
        </div>
    <?php endif; ?>
    <div class="jr-cta-content text-center">
        <?php if (!empty($settings['image2']['url'])): ?>
            <div class="cta-logo">
                <img src="<?php echo esc_url($settings['image2']['url']); ?>" alt="<?php if (!empty($settings['image2']['alt'])) {
                       echo esc_attr($settings['image2']['alt']);
                   } else {
                       esc_attr_e('List', 'ternat-plugin');
                   } ?>">
            </div>
        <?php endif; ?>
        <div class="cta-text headline">
            <?php if (!empty($settings['sub_title'])): ?>
                <span class="cta_tag"><?php echo bookora_wp_kses($settings['sub_title']) ?></span>
            <?php endif; ?>
            <?php if (!empty($settings['title'])): ?>
                <h3>
                    <?php echo bookora_wp_kses($settings['title']) ?>
                    <?php if (!empty($settings['shape']['url'])): ?>
                        <i><img src="<?php echo esc_url($settings['shape']['url']); ?>" alt="<?php if (!empty($settings['shape']['alt'])) {
                               echo esc_attr($settings['shape']['alt']);
                           } else {
                               esc_attr_e('List', 'ternat-plugin');
                           } ?>"></i>
                    <?php endif; ?>
                    <?php echo bookora_wp_kses($settings['title2']) ?>
                </h3>
            <?php endif; ?>
            <?php if (!empty($settings['btn_label'])): ?>
                <div class="jr-btn d-flex mt-35 justify-content-center ">
                    <a <?php echo $this->get_render_attribute_string('btn_link'); ?>><span><?php echo bookora_wp_kses($settings['btn_label']) ?></span> <i
                            class="fa-solid fa-arrow-right"></i></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="line_animation d-flex justify-content-center">
        <div class="line_area"></div>
        <div class="line_area"></div>
        <div class="line_area"></div>
    </div>
</section>