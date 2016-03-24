<?php
/**
 * Sample #1:
 * PHP Page required to create an HTML form that chooses a random line from a CSV file, 
 * and when it is filled, this line moves from this file to another CSV file.
 * 
 * @author Oleksii Dovzhenko <zirkonus@gmail.com>
 * @copyright 2016
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

$file1 = __DIR__."/1.csv";
$file2 = __DIR__."/2.csv";
$csv1 = array();

$handle = @fopen($file1, "r");
if ($handle) {
    while (($buffer = fgets($handle)) !== false) {
        $csv1[] = trim($buffer);
    }
    fclose($handle);
    if (!(count($csv1) > 0)) die("No data in file: ".$file1);
}

if (isset($_POST["some_text"]) && $_POST["some_text"] != ""):
	$random_row_key = (int) $_POST["random_row_key"];
	$random_row_val = $csv1[$random_row_key];
	unset($csv1[$random_row_key]);
	$handle = @fopen($file1, "w");
	if ($handle) {
		fwrite($handle, implode("\n", $csv1));
		fclose($handle);
	}
	$handle = @fopen($file2, "a");
	if ($handle) {
		fwrite($handle, "\n".trim($random_row_val));
		fclose($handle);
	}
?>

Data saved!
<br /><br />
<a href="">Try again?</a>

<?php
else:
	$random_row_key = array_rand($csv1);
	$random_row_val = $csv1[$random_row_key];
?>

<html>
	<body>
		<form action="" method="POST">
			Random row: <input type="text" name="random_row_val" value="<?=$random_row_val?>" />
			<input type="hidden" name="random_row_key" value="<?=$random_row_key?>" />
			<br />
			Please enter somthing: <input type="text" name="some_text" value="" />
			<br />
			<input type="submit" value="Send" />
		</form>
	</body>
</html>

<?php
endif;
?>
