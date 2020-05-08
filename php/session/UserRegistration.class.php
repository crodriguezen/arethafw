<?php
namespace aretha\php\session;

class UserRegistration {
	private $firstname         = "";
	private $mothersmaidenname = "";
	private $lastname          = "";
	private $middlename        = "";
	private $username          = "";
	private $password          = "";
	private $extra             = "";
	private $active            = "t";
	private $registerDate      = "";

	public function __construct() {

	}

	public function setFirstName($firstname) {
		$this->firstname = $firstname;
	}

	public function setMothersMaidenName($mothersmaidenname) {
		$this->mothersmaidenname = $mothersmaidenname;
	}

	public function setLastName($lastname) {
		$this->lastname = $lastname;
	}

	public function setMiddlename($middlename) {
		$this->middlename = $middlename;
	}

	public function setUsername($username) {
		$this->username = $username;	
	}

	public function setPassword($password) {
		$this->password = $password;
	}

	public function setExtra($extra) {
		$this->extra = $extra;
	}

	public function setActive($active) {
		$this->active = $active;
	}

	public function setRegisterDate($registerDate) {
		$this->registerDate = $registerDate;
	}

	public function register() {
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("INSERT INTO users (username, password, firstname, middlename, lastname, extra, active, register_date) 
						  VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');", 
			$this->username,
			$this->password,
			$this->firstname,
			$this->middlename,
			$this->lastname,
			$this->extra,
			$this->active,
			$this->registerDate
		);
		if ($da->connect()) {
			$result = $da->execSetQuery($query);
			$da->disconnect();
			return $result;
		} else {
			return false;
		}

	}

	public function userExist() {
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("SELECT COUNT(*) FROM users WHERE username = '%s';", 
			$this->username
		);
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			$da->disconnect();

			$arrResult = array();
			$count = 0;
			if ($result != false) {
				$count = $result[0][0];
			}

			if ($count > 0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function selectUserID() {
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("SELECT id FROM users WHERE username = '%s';", 
			$this->username
		);
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			$da->disconnect();

			$arrResult = array();
			$count = 0;
			if ($result != false) {
				return $result[0][0];
			}

		} else {
			return -1;
		}
	}


}
?>