<?php
namespace aretha\php\session;

class LogIn {
	private $bbdd          = "aretha";
	private $table         = "users";
	private $username      = "username";
	private $password      = "password";
	private $isPassEncrypt = true;
	private $encryptMethod = "password_hash";
	private $lastSession   = "";

	public function setBBDD($bbdd) {
		$this->bbdd = $bbdd;
	}

	public function setTable($table) {
		$this->table = $table;
	}

	public function setUsername($username) {
		$this->username = $username;
	}

	public function setPassword($password) {
		$this->password = $password;	
	}

	public function setLastSession($lastSession) {
		$this->lastSession = $lastSession;	
	}

	public function setPasswordEncrypt($isPassEncrypt) {
		$this->isPassEncrypt = $isPassEncrypt;		
	}

	public function setEncryptMethod($encryptMethod) {
		$this->encryptMethod = $encryptMethod;	
	}

	public function logIn() {
		$db_password = $this->getPassword();
		if ($this->isPassEncrypt) {
			switch ($this->encryptMethod) {
				case 'password_hash': return password_verify($this->password, $db_password);
			}
		}

		return false;
	}

	public function getPassword() {
		$password = "";

		$da = new \aretha\dao\DataAccess();
		$query = sprintf("SELECT password FROM users WHERE username = '%s';", 
			$this->username
		);
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			$da->disconnect();

			$arrResult = array();
			if ($result != false) {
				$password = $result[0][0];
			}
		}
		return $password;
	}

	public function updateLastSession() {
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("UPDATE users SET lastsession = '%s' WHERE username = '%s';", 
			$this->lastSession,
			$this->username
		);
		if ($da->connect()) {
			$result = $da->execSetQuery($query);
			$da->disconnect();
		}
	}

	public function getProfileImage() {
		$profileimage = "";

		$da = new \aretha\dao\DataAccess();
		$query = sprintf("SELECT profileimage FROM users WHERE username = '%s';", 
			$this->username
		);
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			$da->disconnect();

			if ($result != false) {
				$profileimage = $result[0][0];
			}
		}
		return $profileimage;
	}

	public function getId() {
		$id = "";

		$da = new \aretha\dao\DataAccess();
		$query = sprintf("SELECT id FROM users WHERE username = '%s';", 
			$this->username
		);
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			$da->disconnect();

			if ($result != false) {
				$id = $result[0][0];
			}
		}
		return $id;
	}
}
?>