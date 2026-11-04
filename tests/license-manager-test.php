<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Focused unit tests with WordPress HTTP/options stubs; not an integration test.
define('ABSPATH', __DIR__);
define('DAY_IN_SECONDS', 86400);
define('HOUR_IN_SECONDS', 3600);
define('BOOKORA_LICENSE_STORE_ID', 12);
define('BOOKORA_LICENSE_PRODUCT_ID', 34);
$GLOBALS['options'] = array();
$GLOBALS['transients'] = array();
$GLOBALS['responses'] = array();
$GLOBALS['requests'] = array();
function add_action(...$args) {}
function get_option($key, $default = false) { return $GLOBALS['options'][$key] ?? $default; }
function update_option($key, $value, ...$args) { $GLOBALS['options'][$key] = $value; }
function delete_option($key) { unset($GLOBALS['options'][$key]); }
function get_transient($key) { return $GLOBALS['transients'][$key] ?? false; }
function set_transient($key, $value, $ttl) { $GLOBALS['transients'][$key] = $value; }
function delete_transient($key) { unset($GLOBALS['transients'][$key]); }
function check_ajax_referer(...$args) {}
function current_user_can($cap) { return true; }
function sanitize_text_field($value) { return trim((string) $value); }
function wp_unslash($value) { return $value; }
function home_url($path) { return 'https://example.test' . $path; }
function is_wp_error($v) { return $v === 'network-error'; }
function wp_remote_post($url, $args) { $GLOBALS['requests'][] = array($url, $args); return array_shift($GLOBALS['responses']); }
function wp_remote_retrieve_response_code($response) { return $response['status']; }
function wp_remote_retrieve_body($response) { return json_encode($response['body']); }
class JsonResponse extends Exception { public $success; public $data; public function __construct($success, $data) { $this->success = $success; $this->data = $data; } }
function wp_send_json_error($message, $status = 200) { throw new JsonResponse(false, $message); }
function wp_send_json_success($message) { throw new JsonResponse(true, $message); }
function check($condition, $message) { if (!$condition) throw new Exception($message); }
function call_ajax($callback) { try { $callback(); } catch (JsonResponse $r) { return $r; } throw new Exception('No JSON response'); }
function reply($body, $status = 200) { return array('status' => $status, 'body' => $body); }
require __DIR__ . '/../theme/bookora/includes/class-license-manager.php';
$manager = Theme_License_Manager::get_instance();
$valid = array('valid' => true, 'license_key' => array('expires_at' => null), 'meta' => array('store_id' => 12, 'product_id' => 34, 'product_name' => 'Bookora'));
$activated = $valid + array('activated' => true, 'instance' => array('id' => 'instance-1'));
$_POST = array('license_key' => 'key-1');
$GLOBALS['responses'] = array(reply($valid), reply($activated));
$r = call_ajax(array($manager, 'ajax_activate_license'));
check($r->success && $manager->is_license_active(), 'Activation should succeed');
check(count($GLOBALS['requests']) === 2 && str_ends_with($GLOBALS['requests'][0][0], '/validate') && str_ends_with($GLOBALS['requests'][1][0], '/activate'), 'Validate before activation');
check($GLOBALS['requests'][0][1]['body']['license_key'] === 'key-1' && !isset($GLOBALS['requests'][0][1]['headers']['Authorization']), 'Public API form body');
check(get_option('theme_license_data')['instance_id'] === 'instance-1', 'Instance must be stored');
$r = call_ajax(array($manager, 'ajax_activate_license'));
check(!$r->success, 'Do not overwrite an active instance');
// Temporary network failures preserve state but only inside the grace period.
delete_transient('theme_license_check');
$GLOBALS['responses'] = array('network-error');
$manager->check_license_status();
check($manager->is_license_active(), 'Temporary failures use grace period');
$GLOBALS['options']['theme_license_data']['checked_at'] = time() - 15 * DAY_IN_SECONDS;
check(!$manager->is_license_active(), 'Grace period is bounded');
// A definitive invalid response disables local status.
delete_transient('theme_license_check');
$GLOBALS['responses'] = array(reply(array('valid' => false)));
$manager->check_license_status();
check(!$manager->is_license_active() && get_option('theme_license_data')['status'] === 'invalid', 'Invalid response disables license');
// Failed remote deactivation retains the instance; successful deactivation removes it.
$GLOBALS['responses'] = array('network-error');
$r = call_ajax(array($manager, 'ajax_deactivate_license'));
check(!$r->success && get_option('theme_license_data')['instance_id'] === 'instance-1', 'Retain instance on deactivation failure');
$GLOBALS['responses'] = array(reply(array('deactivated' => true)));
$r = call_ajax(array($manager, 'ajax_deactivate_license'));
check($r->success && !get_option('theme_license_key'), 'Deactivate and clear local state');
// Wrong product is rejected before consuming an activation slot.
$wrong = $valid; $wrong['meta']['product_id'] = 99;
$GLOBALS['requests'] = array(); $GLOBALS['responses'] = array(reply($wrong));
$r = call_ajax(array($manager, 'ajax_activate_license'));
check(!$r->success && count($GLOBALS['requests']) === 1, 'Wrong product must not be activated');
echo "License manager tests passed.\n";
