<?php
namespace aretha\php\session;

class UserModulesInstance {
	private $bbdd        = "aretha";
	private $table       = "user_modules_instance";
	private $idUser      = 0;
	private $instance    = 0;
	private $returnArray = true;

	public function setBBDD($bbdd) {
		$this->bbdd = $bbdd;
	}

	public function setTable($table) {
		$this->table = $table;
	}

	public function setIdUser($idUser) {
		$this->idUser = $idUser;
	}

	public function setInstance($instance) {
		$this->instance = $instance;
	}

	public function getModules() {
		$arrResult = array();
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("SELECT id_module FROM user_modules_instance WHERE id_user = %s AND instance = '%s'", 
			$this->idUser, $this->instance
		);
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			$da->disconnect();

			if ($result != false) {
				if ($this->returnArray) {
					foreach ($result as $row) {
						$arrResult[] = $row[0];	
					}
				}
			}
		}

		return $arrResult;
	}
}
?>