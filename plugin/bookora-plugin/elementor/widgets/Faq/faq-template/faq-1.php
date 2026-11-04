<?php
$rand__id = rand(3243243, 324324324);
$this->add_render_attribute('title', 'class', 'elementor-gt-heading sec_title  tx-split-text split-in-up');
?>
<div class="tc-faq-content">
    <div class="accordion" id="accordionExample_<?php echo esc_attr($rand__id); ?>">
        <?php
        $i = 3;
        foreach ($settings['faqs'] as $id => $item):
            $collapsed_tab = ($id == 0) ? '' : 'collapsed';
            $area_expanded = ($id == 0) ? 'true' : 'false';
            $active_show_tab = ($id == 0) ? 'show' : '';
            $active_show_bg = ($id == 0) ? 'active' : '';
            $i++;
            ?>
            <div class="accordion-item <?php echo esc_attr($active_show_bg); ?>  wow fadeInUp" <?php if (!empty($settings['anims'])): ?> data-wow-delay="<?php echo esc_attr($item['anims']) ?>"
                    data-wow-duration="1000ms" <?php endif; ?>>
                <h2 class="accordion-header"
                    id="heading<?php echo esc_attr($rand__id); ?>-<?php echo esc_attr($item['_id']); ?>">
                    <button class="accordion-button <?php echo esc_attr($collapsed_tab); ?>" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse<?php echo esc_attr($rand__id); ?>-<?php echo esc_attr($item['_id']); ?>"
                        aria-expanded="true"
                        aria-controls="collapse<?php echo esc_attr($rand__id); ?>-<?php echo esc_attr($item['_id']); ?>">
                        <span><?php echo bookora_wp_kses($item['title']); ?></span>
                    </button>
                </h2>
                <div id="collapse<?php echo esc_attr($rand__id); ?>-<?php echo esc_attr($item['_id']); ?>"
                    class="accordion-collapse collapse <?php echo esc_attr($active_show_tab); ?>"
                    aria-labelledby="heading<?php echo esc_attr($rand__id); ?>-<?php echo esc_attr($item['_id']); ?>"
                    data-bs-parent="#accordionExample_<?php echo esc_attr($rand__id); ?>">
                    <div class="accordion-body ">
                        <div class="bi-faq-text">
                            <?php echo bookora_wp_kses($item['description']); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</div>