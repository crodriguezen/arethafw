<?php
namespace aretha\dao;

class MySQL {
    private $connection = false;
	private $persistent = false;
	private $lastErrorNumber = 0;
	private $lastError = "";

	public function connect($host, $port, $databaseName = "", $user, $password) {
    	
	    if ($databaseName == "") {
		    return false;
	    }
	    
		try {
	        $this->connection = mysqli_connect($host, $user, $password, $databaseName);
	        if (mysqli_connect_errno()) {
	        	echo mysqli_connect_error();
	        	return false;
	        }
	        return true;
	    } catch (Exception $e) {
	    	return false;
	    }
	}

	public function getConnection() {
		return $this->connection;
	}

	public function execGetQuery($query) {
	    if (trim($query) == "") {
         	return MySQL::QUERY_EMPTY;
        } else if (strlen($query) < 5) {
	        return MySQL::QUERY_INVALID;
        }
	    
	    // Si no hay una conexion establecida, intentar crear una
        if (!$this->connection) {
            if(!$this->connect()) {
     	        return false;
     	    }
        }
	    
		$result =  mysqli_query($this->connection, $query);
		
		$error = "";
		$error = \aretha\php\util\Text::replaceSpecialChars(mysqli_error($this->connection), " ");
        if ($error !== "") {
	        $this->lastErrorNumber = "M0001";
        	$this->lastError = $error;
	        return false;
        }

        $resultSet = false;
        if (mysqli_num_rows($result) > 0) {
        	while($row = $result->fetch_object()){ 
            	$resultSet[] = $row;
        	}
        }

        $result->close();
		
		return $resultSet;
	}
	
	public function execSetQuery($query) {
		if (trim($query) == "") {
         	return false;
        } else if (strlen($query) < 5) {
	        return false;
        }
        
        if (!$this->connection) {
            if(!$this->connect()) {
     	        return false;
     	    }
        }
        
        $result = false;
        $affected_rows = 0;
        
        $result = mysqli_query($this->connection, $query);

        $error = "";
		$error = \aretha\php\util\Text::replaceSpecialChars(mysqli_error($this->connection), " ");
        if ($error !== "") {
	        $this->lastErrorNumber = "M0001";
        	$this->lastError = $error;
	        return false;
        }

        if ($result == false) {
        	$affected_rows = 0;
        } else {
            $affected_rows = mysqli_affected_rows($this->connection);
        }
        
        return $affected_rows;
	}
	
	public function getLastErrorNumber() {
		return $this->lastErrorNumber;
	}
	
	public function getLastError() {
		return $this->lastError;
	}
	
    public function disconnect() {
        mysqli_close($this->connection);
    }
	
	const QUERY_EMPTY = -1;
	const QUERY_INVALID = -2;
	
	const ERROR_NONE = 100;
	const ERROR_DUPLICATE_KEY = 23505;
}
?>