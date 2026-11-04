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
function get_role($name) { return $GLOBALS['roles'][$name] ?? null; }
function add_role($name, $label, $caps) { $r = new Fake_Role(); $r->caps = $caps; $GLOBALS['roles'][$name] = $r; return $r; }
function dbDelta($sql) { $GLOBALS['schemas'][] = $sql; }
class Activation_Db {
    public $prefix = 'wp_';
    public $fail_tables = false;
    public function get_charset_collate() { return 'DEFAULT CHARACTER SET utf8mb4'; }
    public function esc_like($name) { return $name; }
    public function prepare($query, $value) { return $value; }
    public function get_var($query) { return $this->fail_tables ? null : $query; }
}
$GLOBALS['wpdb'] = new Activation_Db();
require __DIR__ . '/../plugin/bookora-reception/bookora-reception.php';
Bookora_Reception::activate(false);
check(count($GLOBALS['schemas']) === 2, 'Both tables must be created');
check(strpos($GLOBALS['schemas'][0], 'wp_bookora_rooms') !== false && strpos($GLOBALS['schemas'][1], 'wp_bookora_reservations') !== false, 'Site-prefixed tables');
check(!empty($GLOBALS['roles']['administrator']->caps['manage_bookora_reception']), 'Admin capability');
check(!empty($GLOBALS['roles']['bookora_receptionist']->caps['manage_bookora_reception']) && !empty($GLOBALS['roles']['bookora_receptionist']->caps['read']), 'Receptionist capability');
check(empty($GLOBALS['roles']['bookora_receptionist']->caps['manage_options']), 'Receptionist is not an administrator');
try { Bookora_Reception::activate(true); throw new Exception('network activation allowed'); } catch (Exception $e) { check($e->getMessage() === 'activation denied', 'Network activation refused'); }
$GLOBALS['wpdb']->fail_tables = true;
try { Bookora_Reception::activate(false); throw new Exception('failed schema allowed'); } catch (Exception $e) { check($e->getMessage() === 'activation denied', 'Schema failure stops activation'); }
unlink($root . '/wp-admin/includes/upgrade.php');
rmdir($root . '/wp-admin/includes'); rmdir($root . '/wp-admin'); rmdir($root);
echo "Reception activation tests passed.\n";
