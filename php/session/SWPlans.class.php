<?php
namespace aretha\php\session;

class SWPlans {
	private $bbdd        = "aretha";
	private $table       = "user_instances";
	private $username    = "username";
	private $instance    = "instance";
	private $returnArray = true;

	private $startDate   = "";
	private $endDate     = "";

	private $credits     = 0;

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

	public function setCredits($credits) {
		$this->credits = $credits;
	}

	public function setStartDate($startDate) {
		$this->startDate = $startDate;
	}

	public function setEndDate($endDate) {
		$this->endDate = $endDate;
	}

	public function setIdSWPlan($idSWPlan) {
		$this->idSWPlan = $idSWPlan;
	}

	public function setSmallCredits($smallCredits) {
		$this->smallCredits = $smallCredits;
	}

	public function setBigCredits($bigCredits) {
		$this->bigCredits = $bigCredits;
	}

	public function addInstancePlan() {
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("INSERT INTO instance_plan (instance, id_sw_plan, start_date, end_date, small_credits, big_credits, used_small_credits, used_big_credits) 
						VALUES ('%s', %s, '%s', '%s', %s, %s, 0, 0)", 
			$this->instance,
			$this->idSWPlan,
			$this->startDate,
			$this->endDate,
			$this->smallCredits,
			$this->bigCredits
		);
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			return $result[0][0];
			$da->disconnect();
		}
	}

	public function getInstancePlan() {
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("SELECT sw_plans.name, sw_plans.upgradeable, instance_plan.start_date, instance_plan.end_date, instance_plan.small_credits, instance_plan.big_credits, instance_plan.used_small_credits, instance_plan.used_big_credits FROM instance_plan INNER JOIN sw_plans ON id_sw_plan = id WHERE instance_plan.instance = '%s';", 
			$this->instance
		);
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			$da->disconnect();

			if ($result != false) {
				return array($result[0][0], $result[0][1], $result[0][2], $result[0][3], $result[0][4], $result[0][5], $result[0][6], $result[0][7]);
			}
		}
		return false;
	}

	public function getInstanceCredits() {
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("SELECT instance_plan.small_credits, instance_plan.big_credits FROM instance_plan INNER JOIN sw_plans ON id_sw_plan = id WHERE instance_plan.instance = '%s';", 
			$this->instance
		);
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			$da->disconnect();

			if ($result != false) {
				return array($result[0][0], $result[0][1]);
			}
		}
		return false;
	}

	public function redeemSmallCredits() {
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("SET TRANSACTION ISOLATION LEVEL SERIALIZABLE; BEGIN;
						  UPDATE instance_plan SET small_credits = small_credits - %s 
						  WHERE instance = '%s';
						  COMMIT;", 
			$this->credits,
			$this->instance
		);
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			$da->disconnect();
			if ($result != false) {
				return $result[0][0];
			}
		}
		return false;
	}

	public function redeemBigCredits() {
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("SET TRANSACTION ISOLATION LEVEL SERIALIZABLE; BEGIN;
						  UPDATE instance_plan SET big_credits = big_credits - %s 
						  WHERE instance = '%s';
						  COMMIT;", 
			$this->credits,
			$this->instance
		);
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			$da->disconnect();
			if ($result != false) {
				return $result[0][0];
			}
		}
		return false;
	}

	public function addSmallCredits() {
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("SET TRANSACTION ISOLATION LEVEL SERIALIZABLE; BEGIN;
						  UPDATE instance_plan SET small_credits = small_credits + %s 
						  WHERE instance = '%s';
						  COMMIT;", 
			$this->credits,
			$this->instance
		);
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			$da->disconnect();
			if ($result != false) {
				return $result[0][0];
			}
		}
		return false;
	}

	public function addBigCredits() {
		$da = new \aretha\dao\DataAccess();
		$query = sprintf("SET TRANSACTION ISOLATION LEVEL SERIALIZABLE; BEGIN;
						  UPDATE instance_plan SET big_credits = big_credits + %s 
						  WHERE instance = '%s';
						  COMMIT;", 
			$this->credits,
			$this->instance
		);
		if ($da->connect()) {
			$result = $da->execGetQuery($query);
			$da->disconnect();
			if ($result != false) {
				return $result[0][0];
			}
		}
		return false;
	}

}
?>