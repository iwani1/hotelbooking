<?php
$this->add_render_attribute('title', 'class', 'elementor-gt-heading sec_title  bk-itm-title bk-itm-anim');

?>
<div class="bk-sec-title headline pera-content d-flex justify-content-between flex-wrap">
    <?php if (!empty($settings['subtitle'])): ?>
        <div class="subtitle text-capitalize">
            <i></i> <?php echo bookora_wp_kses($settings['subtitle']); ?> <i></i>
        </div>
    <?php endif; ?>
    <div class="title_desc">
        <?php
        printf(
            '<%1$s %2$s>%3$s</%1$s>',
            tag_escape($settings['title_tag']),
            $this->get_render_attribute_string('title'),
            nl2br(bookora_wp_kses($settings['title']))
        );
        ?>
        <?php if (!empty($settings['descripiton'])) {
            echo bookora_wp_kses(wpautop($settings['descripiton']));
        } ?>
    </div>
</div>