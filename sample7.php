<?php

/*
 * How to use
 *
 * 1. Place a file into some hosting.
 * 2. Run somedomain.com/info.php in browser.
 * 2.1. Select source file (must be txt or csv file).
 * 2.2. Fill search field.
 * 2.3. Click OK button.
 * 2.4. Check result.
 */

if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
	if ( !($_FILES['userfile']['type'] == 'text/plain' || $_FILES['userfile']['type'] == 'text/csv') ) {
		echo 'Wrong file! Please select another one.';
	} elseif (empty(trim($_POST['search']))) {
		echo 'Please fill all fields!';
	} else {
		$search = explode(', ', $_POST['search']);
		// read source file
		$file_handle = fopen($_FILES['userfile']['tmp_name'], "r");
		if ($file_handle !== FALSE) {
			// extract data
			while (($line = fgets($file_handle)) !== FALSE) {
				$tmp = explode(', ', trim($line));
				$arr = array_slice($tmp, 2);
				$items = array_combine($arr, array_fill(0, count($arr), $tmp[1]));
				if ( isset($data[$tmp[0]]) ) {
					$data[$tmp[0]] = array_merge($data[$tmp[0]], $items);
				} else {
					$data[$tmp[0]] = $items;
				}
			}
			// calculation
			foreach ($data as $k=>$v) {
				$i = 0;
				while ($search[$i]) {
					if ( isset($v[trim($search[$i])]) ) {
						$result[$k] += $v[trim($search[$i])]; 
						$i++;
					} else {
						unset($result[$k]);
						break;
					}
				}
			}
			// show result
			if (!empty($result)) {
				asort($result);
				$id = key($result);
				echo 'Restaurant: ' . $id . '<br />';
				echo 'Total cost: ' . number_format($result[$id], 2);
			} else {
				echo 'Restaurant: none';
			}
		} else {
			echo 'Something wrong with source file! Please select another one.';
		}
	}
}
?>

<br /><br />
<form method="POST" enctype="multipart/form-data">
File: <input type="file" name="userfile" /><br />
Search: <input type="text" name="search" value="<?=$_POST['search']?>" />
<input type="submit" value="GO" />
</form>
