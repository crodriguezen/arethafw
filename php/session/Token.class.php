<?php
namespace aretha\php\session;

class Token {
	private $username = "";
	private $token    = "";

	public function setUsername($username) {
		$this->username = $username;	
	}

	public function insertToken() {
		$this->token = $this->randomToken();
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("INSERT INTO user_token(username, token, timemodified) VALUES('%s', '%s', '%s');", 
			$this->username,
			$this->token,
			date('Y-m-d h:i:s')
			
		);
		if ($da->connect()) {
			$result = $da->execSetQuery($query);
			$da->disconnect();
		}
	}

	public function updateToken() {
		$this->token = $this->randomToken();
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("UPDATE user_token SET token = '%s', timemodified = '%s' WHERE username = '%s';", 
			$this->token,
			date('Y-m-d h:i:s'),
			$this->username
		);
		if ($da->connect()) {
			$result = $da->execSetQuery($query);
			$da->disconnect();
		}
		return $this->token;
	}

	public function countToken() {
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("SELECT COUNT(*) FROM user_token WHERE username = '%s';", 
			$this->username
		);

		$count = 0;
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			$da->disconnect();
			if ($result != false) {
				$count = $result[0][0];
			}
		}
		return $count;
	}

	public function getToken() {
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("SELECT token FROM user_token WHERE username = '%s';", 
			$this->username
		);

		$token = "";
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			$da->disconnect();
			if ($result != false) {
				$token = $result[0][0];
			}
		}
		return $token;
	}

	public function randomToken($length = 80) {
	    $token = "";
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		$codeAlphabet.= "0123456789";
		$max = strlen($codeAlphabet); // edited

		for ($i=0; $i < $length; $i++) {
			$token .= $codeAlphabet[random_int(0, $max-1)];
		}

		return $token;
	}

	public function generate() {
		if (session_status() == PHP_SESSION_NONE) {
		    session_start();
		}

		if ($this->username != "") {
			if ($this->countToken() > 0) {
				$this->updateToken();
				$_SESSION['af-usertoken'] = $this->token;
				return true;
			} else {
				$this->insertToken();
				$_SESSION['af-usertoken'] = $this->token;
				return false;
			}
		} else {
			return false;
		}
	}

	public function validate() {
		if (session_status() == PHP_SESSION_NONE) {
		    session_start();
		}
		$this->setUsername($_SESSION['af-username']);
		return $_SESSION['af-usertoken'] == $this->getToken();
	}

	public function removeUser() {
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("DELETE FROM user_token WHERE username = '%s';", 
			$this->username
		);
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			$da->disconnect();
		}
	}

}
?>