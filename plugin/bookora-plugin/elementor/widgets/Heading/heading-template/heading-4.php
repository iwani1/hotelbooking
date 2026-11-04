<?php
$this->add_render_attribute('title', 'class', 'elementor-gt-heading sec_title  bk-itm-title bk-itm-anim');
if (!empty($settings['btn_link']['url'])) {
    $this->add_link_attributes('btn_link', $settings['btn_link']);
}
?>
<div class="bk-offer-top d-flex justify-content-between align-items-end">
    <div class="bk-sec-title headline pera-content">
        <?php if (!empty($settings['subtitle'])): ?>
            <div class="subtitle">
                <i></i> <?php echo bookora_wp_kses($settings['subtitle']); ?> <i></i>
            </div>
        <?php endif; ?>
        <?php
        printf(
            '<%1$s %2$s>%3$s</%1$s>',
            tag_escape($settings['title_tag']),
            $this->get_render_attribute_string('title'),
            nl2br(bookora_wp_kses($settings['title']))
        );
        ?>
    </div>
    <div class="item-desc pera-content">
        <?php if (!empty($settings['descripiton'])) {
            echo bookora_wp_kses(wpautop($settings['descripiton']));
        } ?>
        <?php if (!empty($settings['btn_label'])): ?>
            <div class="bk-btn1 mt-25">
                <a <?php echo $this->get_render_attribute_string('btn_link'); ?>><?php echo bookora_wp_kses($settings['btn_label']); ?> </a>
            </div>
        <?php endif; ?>
    </div>
</div>