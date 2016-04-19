<?php
/**
 * Sudoku API library
 * 
 * @author Oleksii Dovzhenko <zirkonus@gmail.com>
 * @copyright 2016
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class api {
	// API main url
	public $api_url = "http://www.mydomain.com/api.php";
	// user language
	public $ln = 'en';
	// data to API request
	public $data = array();
	// data of API response
	public $sudoku = array();
	// API error code
	public $error = 0;
	
	/**
	 * Calling it will bring all relevant data for a puzzle.
	 *
	 * @param number $direct_id
	 * @param mixed  $difficult
	 * @return array
	 */
	public function apiSelectPuzzle($direct_id=0, $difficult=0) {
		$this->data["operation"]					= "SELECT_PUZZLE";
		if ($direct_id) $this->data["direct_id"] 	= $direct_id;
		else $this->data["difficult"] 				= $difficult ? $difficult : '';
		return $this->apiRequest();
	}
	
	/**
	 * Choose puzzle page.
	 *
	 * @param number $review_page1
	 * @return array
	 */
	public function apiSelectPuzzles($review_page1=0) {
		$this->data["operation"] 		= "SELECT_PUZZLES";
		$this->data["uploads_show"] 	= $this->puzzle_difficult_level;
		$this->data["review_page1"] 	= $review_page1;
		return $this->apiRequest();
	
	}
	
	/**
	 * View or rating update.
	 *
	 * @param number $id
	 * @param number $views
	 * @param number $rating
	 * @return array
	 */
	public function apiUpdatePuzzle($id, $views=0, $rating=0) {
		$this->data["operation"] 				= "UPDATE_PUZZLE";
		$this->data["id"] 						= $id;
		if ($views) $this->data["views_rand"]	= $views < 30 ? 10 : 200;
		else $this->data["rating"] 				= ($rating >= 1 || $rating <= 5) ? $rating : 5;
		return $this->apiRequest();
	}
	
	/**
	 * Update last time, user used APP. It should be used only on main page, and only if user is logged in.
	 *
	 * @param number $user_id
	 * @return array
	 */
	public function apiLastLogged($user_id) {
		$this->data["operation"] 		= "LAST_LOGGED";
		$this->data["user_id"]			= $user_id;
		return $this->apiRequest();
	}
	
	/**
	 * Calculating statistics after solving puzzle.
	 *
	 * @param number $solving_time
	 * @param number $avarage_votes
	 * @param number $difficult
	 * @param number $puzzle_num
	 * @param number $user_id
	 * @return array
	 */
	public function apiPuzzleStatistics($solving_time, $avarage_votes, $difficult, $puzzle_num, $user_id=0) {
		$this->data["operation"] 				= "LAST_LOGGED";
		$this->data["solving_time"]				= $solving_time;
		$this->data["avarage_time"]				= '';
		$this->data["avarage_votes"]			= $avarage_votes;
		$this->data["difficult"]				= $difficult;
		$this->data["puzzle_num"]				= $puzzle_num;
		if ($user_id) $this->data["user_id"]	= $user_id;
		return $this->apiRequest();
	}
	
	/**
	 * Lists users according their position in the scoreboard.
	 *
	 * @param number $review_page1
	 * @return array
	 */
	public function apiSelectUsers($review_page1=0) {
		$this->data["operation"] 		= "SELECT_USERS";
		$this->data["review_page1"]		= $review_page1;
		return $this->apiRequest();
	}
	
	/**
	 * It will return a user position and the correct pagination page for it.
	 *
	 * @param number $get_user_id
	 * @return array
	 */
	public function apiGetUserPosition($get_user_id) {
		$this->data["operation"] 		= "GET_USER_POSITION";
		$this->data["get_user_id"]		= $get_user_id;
		return $this->apiRequest();
	}
	
	/**
	 * Will return any user's general profile details. This api should be used when
	 * a logged user try to check his position on scoreboard.
	 *
	 * @param number $user_id
	 * @return array
	 */
	public function apiSelectUser($user_id) {
		$this->data["operation"] 		= "SELECT_USER";
		$this->data["user_id"]			= $user_id;
		return $this->apiRequest(true);
	}
	
	/**
	 * Signs up a user.
	 *
	 * @param string $user_name
	 * @param string $email
	 * @param string $password
	 * @param number $tmp_xp
	 * @param number $num_games
	 * @param number $num_easy
	 * @param number $avarage_easy
	 * @param number $num_medium
	 * @param number $avarage_medium
	 * @param number $num_hard
	 * @param number $avarage_hard
	 * @param number $num_very_hard
	 * @param number $avarage_very_hard
	 * @return array
	 */
	public function apiSignup($user_name, $email, $password, $tmp_xp, $num_games, $num_easy, $avarage_easy, $num_medium, $avarage_medium, $num_hard, $avarage_hard, $num_very_hard, $avarage_very_hard) {
		$this->data["operation"]			= "SIGNUP";
		$this->data["user_name"]			= $user_name;
		$this->data["email"]				= $email;
		$this->data["password"]				= $password;
		$this->data["tmp_xp"]				= $tmp_xp;
		$this->data["num_games"]			= $num_games;
		$this->data["num_easy"]				= $num_easy;
		$this->data["avarage_easy"]			= $avarage_easy;
		$this->data["num_medium"]			= $num_medium;
		$this->data["avarage_medium"]		= $avarage_medium;
		$this->data["num_hard"]				= $num_hard;
		$this->data["avarage_hard"]			= $avarage_hard;
		$this->data["num_very_hard"]		= $num_very_hard;
		$this->data["avarage_very_hard"]	= $avarage_very_hard;
		return $this->apiRequest();
	}
	
	/**
	 * Enables user to login.
	 *
	 * @param string $email
	 * @param string $password
	 * @return array
	 */
	public function apiLogin($email, $password) {
		$this->data["operation"]	= "LOGIN";
		$this->data["email"] 		= $email;
		$this->data["password"] 	= $password;
		return $this->apiRequest();
	}
	
	/**
	 * Forgot password.
	 *
	 * @param string $email
	 * @return array
	 */
	public function apiForgotPassword($email) {
		$this->data["operation"]	= "LOGIN";
		$this->data["email"] 		= $email;
		return $this->apiRequest();
	}
	
	/**
	 * Statistics.
	 *
	 * @param number $user_id
	 * @return array
	 */
	public function apiStatistics($user_id) {
		$this->data["operation"]	= "STATISTICS";
		$this->data["user_id"] 		= $user_id;
		return $this->apiRequest();
	}
	
	/**
	 * Sending data to the API and getting a response.
	 *
	 * @param boolean $simple_data
	 */
	private function apiRequest($simple_data=false) {
		// preparing data for sending
		foreach ($this->data as $k=>$v) {
			$d[] = $k.'='.$v;
		}
		$d[] = "ln=".$this->ln;
		
		// sending data to the API
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->api_url."?".implode('&', $d));
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		curl_close($ch);
		
		// processing results
		if($output === false) {
			$this->error == 500;
		} else {
			$tmp = explode("<$>", $output);
			if (count($tmp) > 1) {
				foreach ($tmp as $t) {
					$t = strip_tags(trim($t));
					if ($t) {
						$t1 = $this->preparingApiData($t);
						if ($this->error) {
							$this->sudoku = array_merge($this->sudoku, $this->preparingApiData($t));
						} else {
							if ($simple_data) $this->sudoku = array_merge($this->sudoku, $this->preparingApiData($t));
							else $this->sudoku[] = $this->preparingApiData($t);
						}
					}
				}
			} else {
				$output = trim($output);
				if ($output) $this->sudoku = $this->preparingApiData($output);
			}
		}
	}
	
	/**
	 * Data transfer to an array.
	 *
	 * @param string $output
	 * @return array
	 */
	private function preparingApiData($output) {
		$ret = array();
		if ($output) {
			$tmp = explode(',', $output);
			foreach ($tmp as $t) {
				if ($t) {
					@list($k, $v) = explode('=', $t);
					if ($k == 'error') {
						$this->error = $v;
						$ret[$k] = $v;
					} else {
						if ($v) $ret[$k] = $v;
						else $ret['text'] = $k;
					}
				}
			}
		}
		return $ret;
	}
}
?>