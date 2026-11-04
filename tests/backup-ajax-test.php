<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// WordPress-stubbed checks for bundled Codestar backup endpoints.
define('ABSPATH', __DIR__);
$GLOBALS['capable'] = false;
$GLOBALS['options'] = array('bookora_theme_options' => array('old' => 'value'), 'secret' => 'do-not-delete');
class CSF { public static $args = array(
    'admin_options' => array('bookora_theme_options' => array()),
    'customize_options' => array(),
    'sections' => array('bookora_theme_options' => array(array('fields' => array(array('type' => 'backup'))))),
); }
function add_action(...$args) {}
function sanitize_text_field($s) { return $s; }
function wp_unslash($s) { return $s; }
function wp_kses_post_deep($v) { return $v; }
function wp_verify_nonce($nonce, $action) { return $nonce === 'valid'; }
function current_user_can($capability) { return $GLOBALS['capable']; }
function esc_html__($s, $domain) { return $s; }
function update_option($k, $v) { $GLOBALS['options'][$k] = $v; }
function delete_option($k) { unset($GLOBALS['options'][$k]); }
class JsonResponse extends Exception { public $success; public function __construct($success) { $this->success = $success; } }
function wp_send_json_error($data = null, $code = 200) { throw new JsonResponse(false); }
function wp_send_json_success($data = null) { throw new JsonResponse(true); }
function invoke($function) { try { $function(); } catch (JsonResponse $e) { return $e->success; } throw new Exception('Missing response'); }
function check($condition, $message) { if (!$condition) throw new Exception($message); }
require __DIR__ . '/../plugin/bookora-plugin/lib/codestar-framework/functions/actions.php';
$_POST = array('nonce' => 'valid', 'unique' => 'bookora_theme_options', 'data' => '{"new":"value"}');
check(!invoke('csf_import_ajax'), 'Low privilege import must fail');
check(!invoke('csf_reset_ajax'), 'Low privilege reset must fail');
$GLOBALS['capable'] = true;
$_POST['unique'] = 'secret';
check(!invoke('csf_import_ajax'), 'Import into arbitrary option must fail');
check(!invoke('csf_reset_ajax') && $GLOBALS['options']['secret'] === 'do-not-delete', 'Reset of arbitrary option must fail');
$_POST['unique'] = 'bookora_theme_options';
$_POST['nonce'] = 'invalid';
check(!invoke('csf_import_ajax'), 'Invalid nonce must fail');
$_POST['nonce'] = 'valid';
check(invoke('csf_import_ajax') && $GLOBALS['options']['bookora_theme_options']['new'] === 'value', 'Authorized theme import');
check(invoke('csf_reset_ajax') && !isset($GLOBALS['options']['bookora_theme_options']), 'Authorized theme reset');
echo "Backup endpoint tests passed.\n";
