<?php
namespace aretha\dao;

class PostgreSQL {
    private $connection = false;
	private $persistent = false;
	private $lastErrorNumber = 0;
	private $lastError = "";

	public function connect($host, $port, $databaseName = "", $user, $password) {
    	
	    if ($databaseName == "") {
		    return false;
	    }
	    
		try {
		    if ($this->persistent) {
		        $this->connection = pg_pconnect("host=" . $host . " port=" . $port . " dbname=" . $databaseName . " user=" . $user . " password=" . $password);
		    } else {
	            $this->connection = pg_connect("host=" . $host . " port=" . $port . " dbname=" . $databaseName . " user=" . $user . " password=" . $password);
	        }
	        pg_set_error_verbosity($this->connection, PGSQL_ERRORS_VERBOSE);
	        return true;
	    } catch (Exception $e) {
		    //$e->getMessage();
	    }
	    
		if (!$this->connection) {
		    return false;
		}
	}

	public function execGetQuery($query) {
	    if (trim($query) == "") {
         	return PostgreSQL::QUERY_EMPTY;
        } else if (strlen($query) < 5) {
	        return PostgreSQL::QUERY_INVALID;
        }
	    
	    // Si no hay una conexion establecida, intentar crear una
        if (!$this->connection) {
            if(!$this->connect()) {
     	        return false;
     	    }
        }
	    
		$result =  pg_query($this->connection, $query);
		
		$error = "";
		$error = \aretha\php\util\Text::replaceSpecialChars(pg_last_error($this->connection), " ");
        if ($error !== "") {
        	
        	// ERROR 42601: Syntax Error
	        if (strpos($error, "42601")) {
		        $this->lastErrorNumber = "42601";
		        $this->lastError = $error;
		        return false;
	        }

        	// ERROR P0001: RAISE EXCEPTION
	        if (strpos($error, "P0001")) {
		        $this->lastErrorNumber = "P0001";
		        $this->lastError = $error;
		        return false;
	        }

        	$this->lastError = $error;
	        return false;
        }

		$resultSet = false;
		while ($row = pg_fetch_row($result)) {
            $resultSet[] = $row;
        }
		
		return $resultSet;
	}
	
	public function execSetQuery($query) {
		if (trim($query) == "") {
         	//return PostgreSQL::QUERY_EMPTY;
         	return false;
        } else if (strlen($query) < 5) {
	        //return PostgreSQL::QUERY_INVALID;
	        return false;
        }
        
	    // Si no hay una conexion establecida, intentar crear una
        if (!$this->connection) {
            if(!$this->connect()) {
     	        return false;
     	    }
        }
        
        $result = false;
        $affected_rows = 0;
        try {
            $result = pg_query($this->connection, $query);
            if ($result == false) {
                //throw new PostgreSQLQueryException();
            } else {
	            $affected_rows = pg_affected_rows($result);
            }
        } catch(Exception $e) {
	         echo "Exception: " . $e->getMessage();
        }
        
        if ($affected_rows === 0) {
            $error = "";
            $error = \aretha\php\util\Text::replaceSpecialChars(pg_last_error($this->connection), " ");
            
            if ($error !== "") {
            	
            	echo "" . $error;

                // ERROR 23505: Duplicate key value
	            if (strpos($error, "23505")) {
		            $this->lastErrorNumber = "23505";
		            $this->lastError = $error;
		            return false;
	            }
	            
	            // ERROR 42601: Syntax Error
	            if (strpos($error, "42601")) {
		            $this->lastErrorNumber = "42601";
		            $this->lastError = $error;
		            return false;
	            }
	            
	            // ERROR 42P01: Relation 'table_name' does not exist
	            if (strpos($error, "42P01")) {
		            $this->lastErrorNumber = "42P01";
		            $this->lastError = $error;
		            return false;
	            }

	            // ERROR 42501: Must be superuser to COPY to or from a file 
	            if (strpos($error, "42501")) {
		            $this->lastErrorNumber = "42501";
		            $this->lastError = $error;
		            return false;
	            }

	            $this->lastError = $error;
	            return false;
            }
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
        pg_close($this->connection);
    }
	
	const QUERY_EMPTY = -1;
	const QUERY_INVALID = -2;
	
	const ERROR_NONE = 100;
	const ERROR_DUPLICATE_KEY = 23505;
}
?>