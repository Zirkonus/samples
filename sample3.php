<?php
/**
 * Sample #3:
 * Function to calculate exponential moving average from array
 * 
 * @author Oleksii Dovzhenko <zirkonus@gmail.com>
 * @copyright 2016
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

function get_ema($data, $count_return=0, $interval=2, $precision=1) {
	if (!intval($interval) || intval($interval) < 2) $interval = 2;
	
	if (count($data) - $interval < $count_return) return false;
	
	$ema = $tmp = array();
	$i = 1;
	$a = 2 / ($interval + 1);
	
	foreach ($data as $k=>$v) {
		if ($interval < $i) {
			$ema[$k] = $ema_prev = sprintf( "%.{$precision}f", ($a * $v + (1 - $a) * $ema_prev) );
		} elseif ($interval == $i) {
			$tmp[] = $v;
			$ema[$k] = $ema_prev = sprintf( "%.{$precision}f", (array_sum($tmp) / $interval) );
		} else {
			$tmp[] = $v;
			$ema[$k] = '';
		}
		$i++;
	}
	return $count_return ? array_slice($ema, -$count_return) : $ema;
}
?>