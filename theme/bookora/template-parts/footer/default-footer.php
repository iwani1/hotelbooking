<?php
$footer_copyright = cs_get_option('footer_copyright');
?>
<footer class="default-footer cta__bg">
    <div class="container">
        <div class="footer__copyright-text text-center">
            <?php if (!empty($footer_copyright)): ?>
                <?php echo wp_kses($footer_copyright, true); ?>
            <?php else: ?>
                <?php esc_html_e('© 2023 bookora - IT Services. All rights reserved.', 'bookora'); ?>
            <?php endif; ?>
        </div>
    </div>
</footer>