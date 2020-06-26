<?php
/**
 *
 * @author Cristian A. Rodriguez Enriquez
 * @version V2.10 14 Oct 2019   (c) 2012-2019 Cristian A. Rodriguez Enriquez (crodriguezen). All rights reserved.
 *
 * Latest version is available at https://arethafw.cloutek.com
 *
 */

namespace aretha\dao;

class DataAccess {
    private $connection     = null;
	private $selectedEngine = false;
	private $engine         = 0;
	private $objectEngine   = null;
	
	private $databaseName     = "";
	private $databaseUser     = "";
	private $databasePassword = "";
	
	// Connection Settings
	private $host     = "";
	private $port     = "";
	private $protocol = "";
    
	private $options  = "";

	public function __construct($engine = 0) {
        if (\Aretha::isIniFileLoaded()) {
	        if (\Aretha::isDatabaseIniFileLoaded()) {
		        $this->engine           = \Aretha::getDatabaseEngine();
		        $this->host             = \Aretha::getDatabaseHost();
		        $this->port             = \Aretha::getDatabasePort();
		        $this->databaseName     = \Aretha::getDatabaseName();
		        $this->databaseUser     = \Aretha::getDatabaseUser();
		        $this->databasePassword = \Aretha::getDatabasePassword();
	        }
        } else {
		    $this->engine = $engine;
		}
		
		$selectedEngine = true;
		
		switch($this->engine) {
			case 1:
                $this->objectEngine = new PostgreSQL();
			    break;
			case 2: 
				$this->objectEngine = new MySQL();
				break;
			default: break;
		}
	}

	public function setUserName($databaseUser) {
	    $this->databaseUser = $databaseUser;
	}
	
	public function setPassword($databasePassword) {
	    $this->databasePassword = $databasePassword;
	}
	
	public function setHost($host) {
	    $this->host = $host;
	}
	
	public function setPort($port) {
	    $this->port = $port;
	}
	
	public function setDatabaseName($databaseName) {
	    $this->databaseName = $databaseName;
	}
	
	public function setEngine($engine = 0) {
	    $this->engine = $engine;
	}
	
	public function getEngine() {
	    return $this->engine;
	}

	/**
	 * Opens a connection to a database
	 * specified by the configuration parameters
	 */
	public function connect() {
	    
	    if ($this->databaseName == "") {
			return false;
		}
		
		if ($this->port == "") {
			return false;
		}
		
		if ($this->host == "") {
			return false;
		}
		
		if ($this->databasePassword == "") {
			return false;
		}
		
	    switch($this->engine) {
			case 1:
                $this->objectEngine = new PostgreSQL();
				if ($this->port == "") {
				    $this->port = self::POSTGRESQL_DEFAULT_PORT;
				}
				$this->objectEngine->connect($this->host, $this->port, $this->databaseName, $this->databaseUser, $this->databasePassword);
			    break;
			case 2: 
			    $this->objectEngine = new MySQL();
			    if ($this->port == "") {
				    $this->port = self::MYSQL_DEFAULT_PORT;
				}
			    $this->objectEngine->connect($this->host, $this->port, $this->databaseName, $this->databaseUser, $this->databasePassword);
				break;
			default: break;
		}
		return true;
	}

	public function disconnect() {
        $this->objectEngine->disconnect();
	}
	
	public function execGetQuery($query) {
	    $result = $this->objectEngine->execGetQuery($query);
		return $result;
	}
	
	public function execSetQuery($query) {
	    $result = $this->objectEngine->execSetQuery($query);
		return $result;
	}
	
	public function createTable() {
	    
	}
    
	public function isConnected() {
    	return !empty($this->connection);
	}

	//==============================================================================================	
	// String Handler
	//==============================================================================================	
	
	public function escape_string($string) {
	    switch($this->engine) {
		    case DataAccess::ENGINE_POSTGRESQL: $string = pg_escape_string($string); break;
		    case DataAccess::ENGINE_MYSQL: 
		    	if ($this->objectEngine->getConnection() != false) {
		    		$string = mysqli_real_escape_string($this->objectEngine->getConnection(), $string); 
		    	}
		    	break;
		    default: break;
	    }
		return $string;
	}

	//==============================================================================================	
	// Error Handler
	//==============================================================================================	
	
	public function getLastErrorNumber() {
		return $this->objectEngine->getLastErrorNumber();
	}
	
	public function getLastError() {
		return $this->objectEngine->getLastError();
	}
	
	//==============================================================================================	
	// CONSTANT DEFINITIONS
	//==============================================================================================	
    const POSTGRESQL_DEFAULT_PORT = "5432";
    const MYSQL_DEFAULT_PORT = "3306";
    const ENGINE_MYSQL = 2;	
	const ENGINE_POSTGRESQL = 1;
	
	const QUERY_EMPTY = 10;
	const QUERY_INVALID = 11;
	
	const ERROR_DATABASE_NAME_EMPTY = 200;
	const ERROR_NONE = 100;
	const ERROR_DUPLICATE_KEY = 23505;
}
?>