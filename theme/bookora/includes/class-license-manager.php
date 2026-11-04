<?php
/** Lemon Squeezy license management.
 * SPDX-License-Identifier: GPL-2.0-or-later
 */
if (!defined('ABSPATH')) {
    exit;
}

class Theme_License_Manager {
    private static $instance = null;
    private const API_URL = 'https://api.lemonsqueezy.com/v1/licenses/';
    private const CHECK_INTERVAL = 7 * DAY_IN_SECONDS;
    private const GRACE_PERIOD = 14 * DAY_IN_SECONDS;

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', array($this, 'add_license_page'));
        add_action('wp_ajax_activate_license', array($this, 'ajax_activate_license'));
        add_action('wp_ajax_deactivate_license', array($this, 'ajax_deactivate_license'));
        add_action('admin_init', array($this, 'check_license_status'));
    }

    public function add_license_page() {
        add_theme_page('Theme License', 'Theme License', 'manage_options', 'theme-license', array($this, 'license_page_content'));
    }

    public function license_page_content() {
        $data = get_option('theme_license_data', array());
        ?>
        <div class="wrap theme-license-wrapper">
            <h1><?php esc_html_e('Theme License', 'bookora'); ?></h1>
            <div id="license-activation-message" role="status"></div>
            <?php if ($this->is_license_active()): ?>
                <h2><?php esc_html_e('License active', 'bookora'); ?></h2>
                <p><?php echo esc_html($data['product_name'] ?? ''); ?></p>
                <p><?php echo esc_html($data['expires_at'] ?? ''); ?></p>
                <button id="deactivate-license" type="button" class="button"><?php esc_html_e('Deactivate license', 'bookora'); ?></button>
            <?php else: ?>
                <p><?php esc_html_e('Enter a Lemon Squeezy license key. The store and product IDs must be configured in wp-config.php first.', 'bookora'); ?></p>
                <?php if (!empty($data['instance_id'])): ?>
                    <p><?php esc_html_e('An existing activation is stored. Deactivate it before activating another key.', 'bookora'); ?></p>
                    <button id="deactivate-license" type="button" class="button"><?php esc_html_e('Deactivate stored license', 'bookora'); ?></button>
                <?php else: ?>
                    <form id="theme-license-activate-form">
                        <label for="bookora-license-key"><?php esc_html_e('License key', 'bookora'); ?></label>
                        <input id="bookora-license-key" type="text" name="license_key" class="regular-text" autocomplete="off" required>
                        <button type="submit" class="button button-primary"><?php esc_html_e('Activate', 'bookora'); ?></button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php
    }

    private function authorized() {
        check_ajax_referer('theme_license_nonce', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied.', 403);
        }
    }

    /** The public license API is form encoded and does not use a store API key. */
    private function request($operation, $body) {
        $response = wp_remote_post(self::API_URL . $operation, array(
            'headers' => array('Accept' => 'application/json'),
            'body' => $body,
            'timeout' => 15,
        ));
        if (is_wp_error($response)) {
            return array('kind' => 'temporary', 'message' => 'Unable to contact the licensing service. Try again later.');
        }
        $code = wp_remote_retrieve_response_code($response);
        $data = json_decode(wp_remote_retrieve_body($response), true);
        if (!is_array($data) || $code >= 500 || $code === 429 || $code < 200) {
            return array('kind' => 'temporary', 'message' => 'The licensing service is temporarily unavailable.');
        }
        if ($code >= 400 || !empty($data['error'])) {
            return array('kind' => 'invalid', 'message' => is_string($data['error'] ?? null) ? $data['error'] : 'License request rejected.');
        }
        return array('kind' => 'ok', 'data' => $data);
    }

    private function matches_product($data) {
        return defined('BOOKORA_LICENSE_STORE_ID') && defined('BOOKORA_LICENSE_PRODUCT_ID')
            && (string) BOOKORA_LICENSE_STORE_ID !== '' && (string) BOOKORA_LICENSE_PRODUCT_ID !== ''
            && isset($data['meta']['store_id'], $data['meta']['product_id'])
            && (string) $data['meta']['store_id'] === (string) BOOKORA_LICENSE_STORE_ID
            && (string) $data['meta']['product_id'] === (string) BOOKORA_LICENSE_PRODUCT_ID;
    }

    private function license_data($response, $instance_id) {
        return array(
            'status' => 'active',
            'instance_id' => $instance_id,
            'product_name' => sanitize_text_field($response['meta']['product_name'] ?? ''),
            'customer_name' => sanitize_text_field($response['meta']['customer_name'] ?? ''),
            'expires_at' => sanitize_text_field($response['license_key']['expires_at'] ?? ''),
            'checked_at' => time(),
        );
    }

    public function ajax_activate_license() {
        $this->authorized();
        $old = get_option('theme_license_data', array());
        if (!empty($old['instance_id'])) {
            wp_send_json_error('Deactivate the stored license before activating another key.');
        }
        if (!defined('BOOKORA_LICENSE_STORE_ID') || !defined('BOOKORA_LICENSE_PRODUCT_ID') || (string) BOOKORA_LICENSE_STORE_ID === '' || (string) BOOKORA_LICENSE_PRODUCT_ID === '') {
            wp_send_json_error('Set BOOKORA_LICENSE_STORE_ID and BOOKORA_LICENSE_PRODUCT_ID in wp-config.php first.');
        }
        $key = isset($_POST['license_key']) && is_string($_POST['license_key']) ? sanitize_text_field(wp_unslash($_POST['license_key'])) : '';
        if ($key === '') {
            wp_send_json_error('Please enter a license key.');
        }
        // Verify the product before consuming an activation slot.
        $result = $this->request('validate', array('license_key' => $key));
        if ($result['kind'] !== 'ok') {
            wp_send_json_error($result['message']);
        }
        if (empty($result['data']['valid']) || !$this->matches_product($result['data'])) {
            wp_send_json_error('This key is invalid or does not belong to the configured store and product.');
        }
        $result = $this->request('activate', array('license_key' => $key, 'instance_name' => home_url('/')));
        if ($result['kind'] !== 'ok') {
            wp_send_json_error($result['message']);
        }
        $data = $result['data'];
        $instance = $data['instance']['id'] ?? '';
        if (empty($data['activated']) || !is_string($instance) || $instance === '') {
            wp_send_json_error('The licensing service did not return an activation instance.');
        }
        // Save the instance even on an unexpected product mismatch so the user can release it.
        update_option('theme_license_key', $key, false);
        update_option('theme_license_data', $this->license_data($data, $instance), false);
        if (!$this->matches_product($data)) {
            $stored = get_option('theme_license_data');
            $stored['status'] = 'invalid';
            update_option('theme_license_data', $stored, false);
            wp_send_json_error('The activated product did not match. Deactivate the stored license to release the instance.');
        }
        delete_option('theme_lemonsqueezy_api_key');
        set_transient('theme_license_check', true, self::CHECK_INTERVAL);
        wp_send_json_success(array('message' => 'License activated.'));
    }

    public function ajax_deactivate_license() {
        $this->authorized();
        $key = get_option('theme_license_key');
        $data = get_option('theme_license_data', array());
        if (!empty($key) && !empty($data['instance_id'])) {
            $result = $this->request('deactivate', array('license_key' => $key, 'instance_id' => $data['instance_id']));
            if ($result['kind'] !== 'ok' || empty($result['data']['deactivated'])) {
                wp_send_json_error($result['message'] ?? 'Deactivation was not confirmed; the stored instance was retained.');
            }
        }
        delete_option('theme_license_key');
        delete_option('theme_license_data');
        delete_option('theme_lemonsqueezy_api_key');
        delete_transient('theme_license_check');
        wp_send_json_success('License deactivated.');
    }

    public function check_license_status() {
        $data = get_option('theme_license_data', array());
        $key = get_option('theme_license_key');
        if (empty($key) || empty($data['instance_id']) || get_transient('theme_license_check')) {
            return;
        }
        $result = $this->request('validate', array('license_key' => $key, 'instance_id' => $data['instance_id']));
        if ($result['kind'] === 'temporary') {
            // Keep the last verified state for a bounded grace period and retry later.
            set_transient('theme_license_check', true, HOUR_IN_SECONDS);
            return;
        }
        if ($result['kind'] === 'ok' && !empty($result['data']['valid']) && $this->matches_product($result['data'])) {
            update_option('theme_license_data', $this->license_data($result['data'], $data['instance_id']), false);
        } else {
            $data['status'] = 'invalid';
            update_option('theme_license_data', $data, false);
        }
        set_transient('theme_license_check', true, self::CHECK_INTERVAL);
    }

    public function is_license_active() {
        $data = get_option('theme_license_data', array());
        if (empty(get_option('theme_license_key')) || empty($data['instance_id']) || ($data['status'] ?? '') !== 'active' || empty($data['checked_at'])) {
            return false;
        }
        if (time() - (int) $data['checked_at'] > self::GRACE_PERIOD) {
            return false;
        }
        if (!empty($data['expires_at'])) {
            $expiry = strtotime($data['expires_at']);
            if ($expiry === false || $expiry <= time()) {
                return false;
            }
        }
        return true;
    }

    public function get_license_data() {
        return get_option('theme_license_data', array());
    }
}
