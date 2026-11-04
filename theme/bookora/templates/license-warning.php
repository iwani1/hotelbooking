<?php
/**
 * Template Part: License warning
 *
 * Rendered on the frontend when the theme license is not active.
 *
 * @package bookora
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="bookora-license-warning" role="alert">
    <div class="bookora-license-warning__inner">
        <strong><?php esc_html_e('Theme License Inactive', 'bookora'); ?></strong>
        <span><?php esc_html_e('The theme license is not active or could not be verified. Review the license settings.', 'bookora'); ?></span>
        <a href="<?php echo esc_url(admin_url('themes.php?page=theme-license')); ?>" class="bookora-license-warning__link"><?php esc_html_e('Activate License', 'bookora'); ?></a>
    </div>
</div>
