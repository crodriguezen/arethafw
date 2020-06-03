<?php
namespace aretha\php\session;

class UserInstances {
	private $bbdd        = "aretha";
	private $table       = "user_instances";
	private $username    = "username";
	private $instance    = "instance";
	private $returnArray = true;

	private $templateDatabase = "";
	private $newDatabase      = "";
	private $newUser          = "";
	private $newUserPass      = "";

	private $instanceAlias    = "";
	private $instanceUser     = "";
	private $modules          = array();
	private $idUser           = 0;

	public function setBBDD($bbdd) {
		$this->bbdd = $bbdd;
	}

	public function setTable($table) {
		$this->table = $table;
	}

	public function setUsername($username) {
		$this->username = $username;
	}

	public function setInstance($instance) {
		$this->instance = $instance;
	}

	public function setTemplateDatabase($templateDatabase) {
		$this->templateDatabase = $templateDatabase;
	}

	public function setNewDatabase($newDatabase) {
		$this->newDatabase = $newDatabase;
	}

	public function setNewUser($newUser) {
		$this->newUser = $newUser;
	}

	public function setNewUserPass($newUserPass) {
		$this->newUserPass = $newUserPass;
	}

	public function setInstanceUser($instanceUser) {
		$this->instanceUser = $instanceUser;
	}

	public function setInstanceAlias($instanceAlias) {
		$this->instanceAlias = $instanceAlias;
	}

	public function setModules($modules) {
		if (is_array($modules)) {
			$this->modules = $modules;
		}
	}

	public function setIdUser($idUser) {
		$this->idUser = $idUser;
	}

	public function getInstances() {
		$arrResult = array();
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("SELECT alias FROM user_instances WHERE username = '%s' ORDER BY id;", 
			$this->username
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

	public function getInstanceAlias() {
		$alias = "";
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("SELECT alias FROM user_instances WHERE instance = '%s' ORDER BY id;", 
			$this->instance
		);
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			$da->disconnect();

			if ($result != false) {
				$alias = $result[0][0];
			}
		}

		return $alias;
	}

	public function getInstancesIDs() {
		$arrResult = array();
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("SELECT instance FROM user_instances WHERE username = '%s' ORDER BY id;", 
			$this->username
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

	public function getDefaultInstance() {
		$defaultInstance = "";
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("SELECT instance 
						  FROM user_instances 
						  WHERE username = '%s' AND is_default = 't' ORDER BY id;", 
			$this->username
		);
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			$da->disconnect();

			if ($result != false) {
				$defaultInstance = $result[0][0];
			}
		}

		return $defaultInstance;
	}

	public function getDefaultInstanceAlias() {
		$defaultInstance = "";
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("SELECT alias 
						  FROM user_instances 
						  WHERE username = '%s' AND is_default = 't' ORDER BY id;", 
			$this->username
		);
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			$da->disconnect();

			if ($result != false) {
				$defaultInstance = $result[0][0];
			}
		}

		return $defaultInstance;
	}

	public function createInstance() {
		$da = new \aretha\dao\DataAccess();
		$queryUser = sprintf("CREATE USER \"%s\" WITH PASSWORD '%s';",
							$this->newUser,
							$this->newUserPass
							);
		
		$queryDB = sprintf("CREATE DATABASE \"%s\" OWNER \"%s\" TEMPLATE %s;", 
						  $this->newDatabase,
						  $this->newUser,
						  $this->templateDatabase
						  );

		if ($da->connect()) {
			$resultUser = $da->execGetQuery($queryUser);
			$resultDB   = $da->execGetQuery($queryDB);
			$da->disconnect();
		}
	}

	public function getAlterTable() {
		$da = new \aretha\dao\DataAccess();
		$res = false;
		$queryOwnerTable = sprintf("SELECT 'ALTER TABLE '|| schemaname || '.' || tablename ||' OWNER TO \"%s\";'
									FROM pg_tables 
									WHERE NOT schemaname IN ('pg_catalog', 'information_schema')
									ORDER BY schemaname, tablename;",
									$this->newUser
									);
		if ($da->connect()) {
			$res = $da->execGetQuery($queryOwnerTable);
		}
		$da->disconnect();
		return $res;	
	}

	public function getAlterSequence() {
		$da = new \aretha\dao\DataAccess();
		$res = false;
		$queryOwnerSeque = sprintf("SELECT 'ALTER SEQUENCE '|| sequence_schema || '.' || sequence_name ||' OWNER TO \"%s\";'
									FROM information_schema.sequences 
									WHERE NOT sequence_schema IN ('pg_catalog', 'information_schema')
									ORDER BY sequence_schema, sequence_name;",
									$this->newUser
									);
		if ($da->connect()) {
			$res = $da->execGetQuery($queryOwnerSeque);
		}
		$da->disconnect();
		return $res;	
	}

	public function getAlterView() {
		$da = new \aretha\dao\DataAccess();
		$res = false;
		$queryOwnerViews = sprintf("SELECT 'ALTER VIEW '|| table_schema || '.' || table_name ||' OWNER TO \"%s\";'
									FROM information_schema.views 
									WHERE NOT table_schema IN ('pg_catalog', 'information_schema')
									ORDER BY table_schema, table_name;",
									$this->newUser
									);
		if ($da->connect()) {
			$res = $da->execGetQuery($queryOwnerViews);
		}
		$da->disconnect();
		return $res;	
	}

	public function executeAlters($dataRow) {
		$da = new \aretha\dao\DataAccess();
		
		if ($da->connect()) {
			if ($dataRow != false) {
				foreach ($dataRow as $row) {
					$r = $da->execGetQuery($row[0]);
				}
			}
			$da->disconnect();
		}
	}

	public function assignInstance() {
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("INSERT INTO user_instances(username, instance, userrole, accessgranted, is_default, alias) 
						VALUES ('%s', '%s', 2, true, true, '%s');",
						$this->instanceUser,
						$this->newDatabase,
						$this->instanceAlias
						);
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			$da->disconnect();
		}
	}

	public function assignInstanceModules() {
		$da = new \aretha\dao\DataAccess();
		if ($da->connect()) {
			foreach ($this->modules as $module) {
				$query = sprintf("INSERT INTO user_modules_instance(id_user, id_module, instance) 
						VALUES (%s, %s, '%s');",
						$this->idUser,
						$module,
						$this->newDatabase
						);
				$result = $da->execGetQuery($query);
			}
			$da->disconnect();
		}
	}
		
}