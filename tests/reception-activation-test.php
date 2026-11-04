<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Activation tests with WordPress and schema operations stubbed.
$root = sys_get_temp_dir() . '/bookora-reception-test-' . uniqid();
mkdir($root . '/wp-admin/includes', 0777, true);
file_put_contents($root . '/wp-admin/includes/upgrade.php', "<?php\n");
define('ABSPATH', $root . '/');
function add_action(...$args) {}
function register_activation_hook(...$args) {}
function wp_die(...$args) { throw new Exception('activation denied'); }
function check($condition, $message) { if (!$condition) throw new Exception($message); }
class Fake_Role { public $caps = array(); public function add_cap($cap) { $this->caps[$cap] = true; } }
$GLOBALS['roles'] = array('administrator' => new Fake_Role());
$GLOBALS['schemas'] = array();
$GLOBALS['options'] = array();
function get_option($key) { return $GLOBALS['options'][$key] ?? false; }
function update_option($key, $value, $autoload = false) { $GLOBALS['options'][$key] = $value; }
function get_role($name) { return $GLOBALS['roles'][$name] ?? null; }
function add_role($name, $label, $caps) { $r = new Fake_Role(); $r->caps = $caps; $GLOBALS['roles'][$name] = $r; return $r; }
function dbDelta($sql) { $GLOBALS['schemas'][] = $sql; }
class Activation_Db {
    public $prefix = 'wp_';
    public $fail_tables = false;
    public $fail_columns = false;
    public function get_charset_collate() { return 'DEFAULT CHARACTER SET utf8mb4'; }
    public function esc_like($name) { return $name; }
    public function prepare($query, $value) { return array($query, $value); }
    public function get_var($query) { return ($this->fail_tables || ($this->fail_columns && strpos($query[0], 'SHOW COLUMNS') !== false)) ? null : $query[1]; }
}
$GLOBALS['wpdb'] = new Activation_Db();
require __DIR__ . '/../plugin/bookora-reception/bookora-reception.php';
Bookora_Reception::activate(false);
check(count($GLOBALS['schemas']) === 2, 'Both tables must be created');
check(get_option('bookora_reception_schema_version') === '1.1.0', 'Schema version saved');
Bookora_Reception::maybe_upgrade();
check(count($GLOBALS['schemas']) === 2, 'Current schema does not rerun');
$GLOBALS['options']['bookora_reception_schema_version'] = '1.0.0';
Bookora_Reception::maybe_upgrade();
check(count($GLOBALS['schemas']) === 4 && get_option('bookora_reception_schema_version') === '1.1.0', 'Existing install upgraded');
check(strpos($GLOBALS['schemas'][0], 'nightly_rate_pesewas bigint(20) unsigned NOT NULL DEFAULT 0') !== false && strpos($GLOBALS['schemas'][1], 'total_pesewas bigint(20) unsigned DEFAULT NULL') !== false, 'Legacy rows stay unpriced');
check(strpos($GLOBALS['schemas'][0], 'wp_bookora_rooms') !== false && strpos($GLOBALS['schemas'][1], 'wp_bookora_reservations') !== false, 'Site-prefixed tables');
check(!empty($GLOBALS['roles']['administrator']->caps['manage_bookora_reception']), 'Admin capability');
check(!empty($GLOBALS['roles']['bookora_receptionist']->caps['manage_bookora_reception']) && !empty($GLOBALS['roles']['bookora_receptionist']->caps['read']), 'Receptionist capability');
check(empty($GLOBALS['roles']['bookora_receptionist']->caps['manage_options']), 'Receptionist is not an administrator');
try { Bookora_Reception::activate(true); throw new Exception('network activation allowed'); } catch (Exception $e) { check($e->getMessage() === 'activation denied', 'Network activation refused'); }
$GLOBALS['options']['bookora_reception_schema_version'] = '1.0.0';
$GLOBALS['wpdb']->fail_columns = true;
try { Bookora_Reception::maybe_upgrade(); throw new Exception('failed migration allowed'); } catch (Exception $e) { check($e->getMessage() === 'activation denied', 'Missing column stops migration'); }
check(get_option('bookora_reception_schema_version') === '1.0.0', 'Failed migration does not mark successful');
$GLOBALS['wpdb']->fail_columns = false;
$GLOBALS['wpdb']->fail_tables = true;
try { Bookora_Reception::activate(false); throw new Exception('failed schema allowed'); } catch (Exception $e) { check($e->getMessage() === 'activation denied', 'Schema failure stops activation'); }
unlink($root . '/wp-admin/includes/upgrade.php');
rmdir($root . '/wp-admin/includes'); rmdir($root . '/wp-admin'); rmdir($root);
echo "Reception activation tests passed.\n";
