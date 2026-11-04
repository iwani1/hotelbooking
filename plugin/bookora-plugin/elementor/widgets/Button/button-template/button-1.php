<?php
if (!empty($settings['btn_link']['url'])) {
    $this->add_link_attributes('btn_link', $settings['btn_link']);
}
?>
<div class="tc-btn1 d-flex justify-content-center align-items-center">
    <a <?php echo $this->get_render_attribute_string('btn_link'); ?>><span><?php echo bookora_wp_kses($settings['btn_label']); ?></span> <i
            class="fa-solid fa-chevron-right"></i></a>
</div>