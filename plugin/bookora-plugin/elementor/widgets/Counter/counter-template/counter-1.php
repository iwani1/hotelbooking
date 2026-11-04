<?php
if (!empty($settings['btn_link']['url'])) {
    $this->add_link_attributes('btn_link', $settings['btn_link']);
}
?>
<section id="bk-fact" class="bk-fact-sec pb-120">
    <div class="container">
        <div class="bk-fact-top flex-wrap headline d-flex align-items-center justify-content-between">
            <?php if (!empty($settings['title'])): ?>
                <h3><?php echo bookora_wp_kses($settings['title']); ?></h3>
            <?php endif; ?>
            <div class="bk-btn1">
                <a <?php echo $this->get_render_attribute_string('btn_link'); ?>><?php echo bookora_wp_kses($settings['btn_label']); ?></a>
            </div>
        </div>
        <div class="bk-fact-content mt-20">
            <?php foreach ($settings['counters'] as $item): ?>
                <?php if ($item['style'] == 1): ?>
                    <div class="bk-fact-item headline pera-content d-flex align-items-center justify-content-between">
                        <?php if ($item['description']): ?>
                            <p><?php echo bookora_wp_kses($item['description']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($item['image']['url'])): ?>
                            <div class="item-img img-cover">
                                <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php if (!empty($item['image']['alt'])) {
                                       echo esc_attr($item['image']['alt']);
                                   } else {
                                       esc_attr_e('List', 'ternat-plugin');
                                   } ?>">
                            </div>
                        <?php endif; ?>
                        <div class="item-text d-flex">
                            <h3><span class="odometer" data-count="<?php echo bookora_wp_kses($item['number']); ?>"></span><?php if (!empty($item['prefix'])) {
                                   echo esc_attr($item['prefix']);
                               } ?>
                            </h3>
                            <?php if ($item['title']): ?>
                                <sub class="text-uppercase"><?php echo bookora_wp_kses($item['title']); ?></sub>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php elseif ($item['style'] == 2): ?>
                    <div class="bk-fact-item headline pera-content d-flex align-items-center justify-content-between">
                        <div class="item-text d-flex">
                            <h3><span class="odometer" data-count="<?php echo bookora_wp_kses($item['number']); ?>"></span><?php if (!empty($item['prefix'])) {
                                   echo esc_attr($item['prefix']);
                               } ?>
                            </h3>
                            <?php if ($item['title']): ?>
                                <sub class="text-uppercase"><?php echo bookora_wp_kses($item['title']); ?></sub>
                            <?php endif; ?>
                        </div>
                        <?php if ($item['description']): ?>
                            <p><?php echo bookora_wp_kses($item['description']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($item['image']['url'])): ?>
                            <div class="item-img img-cover">
                                <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php if (!empty($item['image']['alt'])) {
                                       echo esc_attr($item['image']['alt']);
                                   } else {
                                       esc_attr_e('List', 'ternat-plugin');
                                   } ?>">
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="bk-fact-item headline pera-content d-flex align-items-center justify-content-between">
                        <?php if (!empty($item['image']['url'])): ?>
                            <div class="item-img img-cover">
                                <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php if (!empty($item['image']['alt'])) {
                                       echo esc_attr($item['image']['alt']);
                                   } else {
                                       esc_attr_e('List', 'ternat-plugin');
                                   } ?>">
                            </div>
                        <?php endif; ?>
                        <div class="item-text d-flex">
                            <h3><span class="odometer" data-count="<?php echo bookora_wp_kses($item['number']); ?>"></span><?php if (!empty($item['prefix'])) {
                                   echo esc_attr($item['prefix']);
                               } ?>
                            </h3>
                            <?php if ($item['title']): ?>
                                <sub class="text-uppercase"><?php echo bookora_wp_kses($item['title']); ?></sub>
                            <?php endif; ?>
                        </div>
                        <?php if ($item['description']): ?>
                            <p><?php echo bookora_wp_kses($item['description']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

        </div>
    </div>
</section>