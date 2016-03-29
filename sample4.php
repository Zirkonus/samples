<?php
/**
 * Sample #4:
 * Needs to work for BOTH querystring GET and JSON POST and writes the contents to a text file in JSON format
 *
 * PHP-code for validate this script:
 * <?php
 * $data = array(
 * 				'key'	=> 'name',
 * 				'value'	=> 'Oleksii',
 * 				'toID'	=> 56
 * 			);
 * $ch = curl_init();
 * curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
 * curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
 * curl_setopt($ch, CURLOPT_URL, 'localhost/simple4.php');  
 * curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 * curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
 * $output = curl_exec($ch);
 * curl_close($ch);
 * echo $output;
 * ?>
 *
 *
 * @author Oleksii Dovzhenko <zirkonus@gmail.com>
 * @copyright 2016
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

$query_string = $_SERVER['QUERY_STRING'];
// if QUERY_STRING not empty
if ($query_string) {
	parse_str($query_string, $params);
	$data = json_encode($params);
} else { // else use JSON POST
	$data = file_get_contents('php://input');
	$params = json_decode($data, TRUE);
}

// if key toID absent - like an exception
$id = isset($params["toID"]) ? $params["toID"] : '_timestamp_'.time();

// save JSON data
$filename = __DIR__."/tmp/ID" . $id;
file_put_contents($filename, $data);
print "success";
?>