<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Isolated service tests. WordPress and MySQL are stubbed; not an integration test.
define('ABSPATH', __DIR__);
define('ARRAY_A', 'ARRAY_A');
function add_action(...$args) {}
function register_activation_hook(...$args) {}
function is_email($value) { return (bool) filter_var($value, FILTER_VALIDATE_EMAIL); }
function current_time($type, $gmt = false) { return $type === 'Y-m-d' ? '2030-01-01' : '2030-01-01 12:00:00'; }
function check($condition, $message) { if (!$condition) throw new Exception($message); }
$GLOBALS['allow_reception'] = false;
$GLOBALS['nonce_valid'] = false;
$GLOBALS['allow_admin'] = false;
function current_user_can($cap) { return ($cap === 'manage_bookora_reception' && $GLOBALS['allow_reception']) || ($cap === 'manage_options' && $GLOBALS['allow_admin']); }
function wp_die(...$args) { throw new Exception('denied'); }
function check_admin_referer($action) { if (!$GLOBALS['nonce_valid']) throw new Exception('nonce'); }
function sanitize_text_field($s) { return trim(strip_tags($s)); }
function wp_unslash($s) { return $s; }
function get_current_user_id() { return 2; }
function admin_url($path) { return 'http://example.test/wp-admin/' . $path; }
function add_query_arg($args, $url) { return $url . '?' . http_build_query($args); }
function wp_safe_redirect($url) { throw new Exception('redirect:' . $url); }

class Fake_Wpdb {
    public $prefix = 'wp_';
    public $reservations = array();
    public $rooms = array(1 => array('id' => 1, 'capacity' => 2, 'nightly_rate_pesewas' => 12050));
    public $locked = false;
    public $busy = false;
    public $db_error = false;
    public $releases = 0;
    public function prepare($query, ...$args) {
        foreach ($args as $arg) {
            $replacement = is_int($arg) ? (string) $arg : "'" . str_replace("'", "''", $arg) . "'";
            $query = preg_replace_callback('/%[sd]/', function () use ($replacement) { return $replacement; }, $query, 1);
        }
        return $query;
    }
    public function get_var($query) {
        if (strpos($query, 'GET_LOCK') !== false) {
            if ($this->busy) return '0';
            $this->locked = true;
            return '1';
        }
        if (strpos($query, 'RELEASE_LOCK') !== false) {
            $this->locked = false; $this->releases++; return '1';
        }
        if (strpos($query, 'SELECT COUNT(*)') !== false) {
            check($this->locked, 'Availability check must be inside the lock');
            if ($this->db_error) return null;
            check(strpos($query, "status <> 'cancelled'") !== false, 'Cancelled reservations must not block rooms');
            check((bool) preg_match("/room_id = (\\d+) AND status <> 'cancelled' AND check_in < '([^']+)' AND check_out > '([^']+)'/", $query, $m), 'Expected half-open availability query');
            $count = 0;
            foreach ($this->reservations as $r) {
                if ($r['room_id'] === (int) $m[1] && $r['status'] !== 'cancelled' && $r['check_in'] < $m[2] && $r['check_out'] > $m[3]) $count++;
            }
            return (string) $count;
        }
        throw new Exception('Unexpected query: ' . $query);
    }
    public function get_row($query, $type) {
        check($this->locked, 'Room must be read inside the lock');
        preg_match('/WHERE id = (\d+)/', $query, $m);
        return $this->rooms[(int) $m[1]] ?? null;
    }
    public function insert($table, $data, $formats) {
        check($this->locked, 'Insertion must be inside the lock');
        $data['id'] = count($this->reservations) + 1;
        $this->reservations[$data['id']] = $data;
        return 1;
    }
    public function update($table, $data, $where, $formats, $where_formats) {
        $id = $where['id'];
        if (strpos($table, 'bookora_rooms') !== false) {
            check($this->locked, 'Rate update must be inside the lock');
            if ($this->db_error) return false;
            if (!isset($this->rooms[$id])) return 0;
            $this->rooms[$id]['nightly_rate_pesewas'] = $data['nightly_rate_pesewas'];
            return 1;
        }
        if (!isset($this->reservations[$id]) || $this->reservations[$id]['status'] !== $where['status']) return 0;
        $this->reservations[$id]['status'] = $data['status'];
        return 1;
    }
}
$GLOBALS['wpdb'] = new Fake_Wpdb();
require __DIR__ . '/../plugin/bookora-reception/bookora-reception.php';
function book($start, $end, $guests = 1, $email = '') {
    return Bookora_Reception::create_reservation(1, 'Test Guest', $email, '0201234567', $guests, $start, $end, 2);
}
foreach (array('0.01' => 1, '120.5' => 12050, '1000000' => 100000000) as $input => $expected) check(Bookora_Reception::parse_ghs((string) $input) === $expected, 'Decimal parsing');
foreach (array('', '0', '-1', '1.001', '1e3', '1000000.01', '1,000') as $input) check(Bookora_Reception::parse_ghs($input) === null, 'Invalid rate rejected');
check(book('2030-01-02', '2030-01-04') === 'booked', 'First booking');
check($wpdb->reservations[1]['nightly_rate_pesewas'] === 12050 && $wpdb->reservations[1]['total_pesewas'] === 24100, 'Integer price snapshot');
check(Bookora_Reception::set_room_rate(1, 15000) === 'rate_updated' && !$wpdb->locked, 'Rate updated under lock');
check($wpdb->reservations[1]['total_pesewas'] === 24100, 'Existing booking price is immutable');
check(Bookora_Reception::set_room_rate(999, 100) === 'rate_invalid' && !$wpdb->locked, 'Missing room releases lock');
check(book('2030-01-06', '2031-01-07') === 'invalid', 'Stay maximum enforced');
check(book('2030-01-03', '2030-01-05') === 'unavailable', 'Overlapping dates are rejected');
check(book('2030-01-04', '2030-01-05') === 'booked', 'Checkout day is available');
check($wpdb->reservations[2]['nightly_rate_pesewas'] === 15000 && $wpdb->reservations[2]['total_pesewas'] === 15000, 'Later booking uses new rate');
check(book('2030-02-30', '2030-03-02') === 'invalid', 'Invalid date rejected');
check(book('2029-12-31', '2030-01-02') === 'invalid', 'Past check-in rejected');
check(book('2030-01-06', '2030-01-06') === 'invalid', 'Empty stay rejected');
check(book('2030-01-06', '2030-01-07', 3) === 'invalid', 'Capacity enforced');
check(book('2030-01-06', '2030-01-07', 1, 'not-an-email') === 'invalid', 'Email validated');
check(Bookora_Reception::update_status(1, 'check_out') === 'bad_status', 'Must check in before checkout');
check(Bookora_Reception::update_status(1, 'check_in') === 'status_updated', 'Check in');
check(Bookora_Reception::update_status(1, 'cancel') === 'bad_status', 'Cannot cancel after check-in');
check(Bookora_Reception::update_status(1, 'check_out') === 'status_updated', 'Check out');
check(book('2030-01-02', '2030-01-03') === 'unavailable', 'Checked-out booking keeps historical occupancy');
check(Bookora_Reception::update_status(2, 'cancel') === 'status_updated', 'Cancel a confirmed booking');
check(book('2030-01-04', '2030-01-05') === 'booked', 'Cancelled room becomes available');
$wpdb->busy = true;
$releases = $wpdb->releases;
check(book('2030-01-10', '2030-01-11') === 'busy' && $wpdb->releases === $releases, 'Lock contention rejects booking');
check(Bookora_Reception::set_room_rate(1, 100) === 'busy', 'Rate update respects lock contention');
$wpdb->busy = false; $wpdb->db_error = true;
check(Bookora_Reception::set_room_rate(1, 100) === 'db_error' && !$wpdb->locked, 'Rate database errors release lock');
check(book('2030-01-10', '2030-01-11') === 'db_error' && !$wpdb->locked, 'Database failures release lock');
check(Bookora_Reception::update_status(999, 'cancel') === 'bad_status', 'Missing reservation rejected');
$wpdb->db_error = false;
$wpdb->rooms[1]['nightly_rate_pesewas'] = 0;
check(book('2030-02-01', '2030-02-02') === 'booked', 'Legacy unpriced room remains bookable');
check(end($wpdb->reservations)['total_pesewas'] === null && end($wpdb->reservations)['nightly_rate_pesewas'] === null, 'Unpriced snapshot is null');
// The actual admin-post handler must reject unauthorized and missing-nonce requests.
$wpdb->db_error = false;
$_POST = array('room_id' => '1', 'guest_name' => '<b>Guest</b>', 'guest_email' => '', 'guest_phone' => '', 'guests' => '1', 'check_in' => '2030-01-10', 'check_out' => '2030-01-11');
$before = count($wpdb->reservations);
try { Bookora_Reception::handle_booking(); throw new Exception('Expected permission failure'); } catch (Exception $e) { check($e->getMessage() === 'denied', 'Capability required'); }
check(count($wpdb->reservations) === $before, 'Denied request cannot create a booking');
$GLOBALS['allow_reception'] = true;
try { Bookora_Reception::handle_booking(); throw new Exception('Expected nonce failure'); } catch (Exception $e) { check($e->getMessage() === 'nonce', 'Nonce required'); }
check(count($wpdb->reservations) === $before, 'Invalid nonce cannot create a booking');
$GLOBALS['nonce_valid'] = true;
$_POST['nightly_rate'] = '25.00';
try { Bookora_Reception::handle_rate(); throw new Exception('Expected permission failure'); } catch (Exception $e) { check($e->getMessage() === 'denied', 'Receptionist cannot change rate'); }
try { Bookora_Reception::handle_add_room(); throw new Exception('Expected permission failure'); } catch (Exception $e) { check($e->getMessage() === 'denied', 'Receptionist cannot add rooms'); }
$GLOBALS['allow_admin'] = true;
$GLOBALS['nonce_valid'] = false;
try { Bookora_Reception::handle_rate(); throw new Exception('Expected nonce failure'); } catch (Exception $e) { check($e->getMessage() === 'nonce', 'Rate requires nonce'); }
$GLOBALS['nonce_valid'] = true;
try { Bookora_Reception::handle_rate(); throw new Exception('Expected redirect'); } catch (Exception $e) { check(strpos($e->getMessage(), 'redirect:') === 0, 'Admin rate change redirects'); }
check($wpdb->rooms[1]['nightly_rate_pesewas'] === 2500, 'Admin rate stored');
try { Bookora_Reception::handle_booking(); throw new Exception('Expected redirect'); } catch (Exception $e) { check(strpos($e->getMessage(), 'redirect:') === 0, 'Successful request redirects'); }
check(count($wpdb->reservations) === $before + 1 && end($wpdb->reservations)['guest_name'] === 'Guest', 'Authorized request stores sanitized name');
echo "Reception service and authorization tests passed.\n";
