<?php
/**
 * Sample #2:
 * Work with one-click mobile payment API Fortumo.
 * 
 * @author Oleksii Dovzhenko <zirkonus@gmail.com>
 * @copyright 2015
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

error_reporting(E_ALL);
require_once "pgsql.lib.php";

$db = new pgsql("localhost", "5432", "dbname_test", "user_test", "password_test");

// check that the request comes from Fortumo server
if (!in_array($_SERVER['REMOTE_ADDR'], array('79.125.5.95', '81.20.151.38', '81.20.148.122', '209.20.83.207', '79.125.125.1'))) {
	header("HTTP/1.0 403 Forbidden");
	die("Error: Unknown IP");
}

// check the signature
$secret = 'f6f769a6300a9e08b9b372e3eb51378c'; // insert your secret between ''
if (empty($secret) || !check_signature($_GET, $secret)) {
	header("HTTP/1.0 404 Not Found");
	die("Error: Invalid signature");
}

$p = array();
$sender = $p['p_sender']          = $_GET['sender'];
$message = $p['p_message']        = $_GET['message'];
$message_id = $p['p_message_id']  = $_GET['message_id'];//unique id
$p['p_country']                   = $_GET['country'];
$p['p_price']                     = $_GET['price'];
$p['p_price_wo_vat']              = $_GET['price_wo_vat'];
$p['p_currency']                  = $_GET['currency'];
$p['p_service_id']                = $_GET['service_id'];
$p['p_keyword']                   = $_GET['keyword'];
$p['p_shortcode']                 = $_GET['shortcode'];
$p['p_operator']                  = $_GET['operator'];
$p['p_billing_type']              = $_GET['billing_type'];
$p['p_status']                    = $_GET['status'];
$p['p_test']                      = $_GET['test'];
$p['p_sig']                       = $_GET['sig'];

$q = "INSERT INTO test.fortumo_transactions
		(message, sender, country, price, price_wo_vat, currency, service_id, message_id, keyword,
		shortcode, operator, billing_type, status, test, sig)
	VALUES
		(:p_message, :p_sender, :p_country, :p_price, :p_price_wo_vat, :p_currency, :p_service_id, :p_message_id, :p_keyword,
		:p_shortcode, :p_operator, :p_billing_type, :p_status, :p_test, :p_sig)";
$db->put($q, $p);

// do something with $sender and $message
$reply = "Thank you $sender for sending $message";

// print out the reply
echo($reply);

// only grant virtual credits to account, if payment has been successful
if (preg_match("/OK/i", $_GET['status']) || (preg_match("/MO/i", $_GET['billing_type']) && preg_match("/pending/i", $_GET['status']))) {
	add_credits($message);
}

function check_signature($params_array, $secret) {
	ksort($params_array);

	$str = '';
	foreach ($params_array as $k=>$v) {
		if ($k != 'sig') {
			$str .= "$k=$v";
		}
	}
	$str .= $secret;
	$signature = md5($str);

	return ($params_array['sig'] == $signature);
}
?>