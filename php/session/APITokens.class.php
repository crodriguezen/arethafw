<?php
namespace aretha\php\session;

class APITokens {
	private $bbdd        = "aretha";
	private $table       = "user_instances";
	private $username    = "username";
	private $instance    = "instance";

	private $userInstance = "";
	private $userToken     = "";
	private $idAPI        = "";

	public function setUserInstance($userInstance) {
		$this->userInstance = $userInstance;
	}

	public function setUserToken($userToken) {
		$this->userToken = $userToken;
	}

	public function setIdAPI($idAPI) {
		$this->idAPI = $idAPI;
	}

	public function addAPIToken() {
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("INSERT INTO api_tokens(usr_instance, usr_token, id_api) 
						VALUES ('%s', '%s', %s);",
						$this->userInstance,
						$this->userToken,
						$this->idAPI
						);
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			$da->disconnect();
		}
	}


}
?>