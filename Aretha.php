<?php
/**
 *
 * @author Cristian A. Rodriguez Enriquez
 * @version V2.16 14 May 2020  (c) 2012-2020 Cristian A. Rodriguez Enriquez (crodriguezen). All rights reserved.
 *
 * Latest version is available at https://github.com/crodriguezen/arethafw
 *
 */
define('ARETHA_DIRNAME', "arethafw");

define('POSTGRESQL', 1);
define('MYSQL'     , 2);
define('SQLSERVER' , 3);

define('AF_LOGIN'   , 0);
define('CUSTOM_PATH', 1);

$arethaPaths = array();

spl_autoload_register(
	function ($theclass) {
		$arethaPaths = array(
			"./",
			"../",
			"../../",
			"../../../",

			"classes/",
			"../classes/",
			"../../classes/",
			"../../../classes/",

			"models/",
			"../models/",
			"../../models/",
			"../../../models/",

			"entities/",
			"../entities/",
			"../../entities/",
			"../../../entities/",

			"plainObjects/",
			"../plainObjects/",
			"../../plainObjects/",
			"../../../plainObjects/",

			ARETHA_DIRNAME . "/lib/",
			"../" . ARETHA_DIRNAME . "/lib/",
			"../../" . ARETHA_DIRNAME . "/lib/",
			"../../../" . ARETHA_DIRNAME . "/lib/",

			ARETHA_DIRNAME . "/plugins/",
			"../" . ARETHA_DIRNAME . "/plugins/",
			"../../" . ARETHA_DIRNAME . "/plugins/",
			"../../../" . ARETHA_DIRNAME . "/plugins/",

			ARETHA_DIRNAME . "/vplugins/",
			"../" . ARETHA_DIRNAME . "/vplugins/",
			"../../" . ARETHA_DIRNAME . "/vplugins/",
			"../../../" . ARETHA_DIRNAME . "/vplugins/",

			ARETHA_DIRNAME . "/php/bootstrap/",
			"../" . ARETHA_DIRNAME . "/php/bootstrap/",
			"../../" . ARETHA_DIRNAME . "/php/bootstrap/",
			"../../../" . ARETHA_DIRNAME . "/php/bootstrap/",

			ARETHA_DIRNAME . "/php/util/",
			"../" . ARETHA_DIRNAME . "/php/util/",
			"../../" . ARETHA_DIRNAME . "/php/util/",
			"../../../" . ARETHA_DIRNAME . "/php/util/",

			ARETHA_DIRNAME . "/dao/",
			"../" . ARETHA_DIRNAME . "/dao/",
			"../../" . ARETHA_DIRNAME . "/dao/",
			"../../../" . ARETHA_DIRNAME . "/dao/"
		);

		$cpaths = count($arethaPaths);
		
		// Namespace handler ====
		$namespace      = "";
		$namespace_path = "";
		$_class         = "";
		$arrClass       = null;
		if (strpos($theclass, "\\") !== false) {
			// Replace backslash with slash
			$slashed   = str_replace("\\", "/", $theclass);
			// Split the quialified name in to an array
			$arrClass  = explode("/", $slashed);
			// The last position is the class name (the file to include)
			$_class    = $arrClass[count($arrClass) -1];
			// Obtain the namespace by sustitution of the class name
			$namespace = str_replace($_class, "", $slashed);
			// If the qualified name has not the "arethafw" word, replace aretha with ARETHA_DIRNAME constant
			if (strpos($theclass, ARETHA_DIRNAME) === false) {
				$namespace = str_replace("aretha", ARETHA_DIRNAME, $namespace);
			}
			
			// Check for double namespace (ie: use of class inside namespaced class)
			$shortened_namespace = "";
			if (substr_count($namespace, 'aretha') > 1) {
				$arethaPos = array();
				$position  = 0;
				$counter   = 0;
				foreach($arrClass as $fragment) {
					// If fragment equals to ARETHA_DIRNAME save position
					if ($fragment == "aretha") {
						$arethaPos[$counter] = $position;
						$counter++;
					}
					$position++;
				}

				$counter = 0;
				foreach($arrClass as $fragment) {
					//echo "FG: " . $fragment;
					if ( $counter > $arethaPos[ count($arethaPos) - 1 ] && $counter < (count($arrClass)-1) ) {
						$shortened_namespace .= $fragment . "/";
					}
					$counter++;
				}
			}
			
			if ($shortened_namespace != "") {
				$namespace = $shortened_namespace;
			}
			

			$arethaPaths[$cpaths]     = $namespace;
			$arethaPaths[$cpaths + 1] = "../"    . $namespace;
			$arethaPaths[$cpaths + 2] = "../../" . $namespace;
			$theclass = $_class;
		}

		// Include handler ===
		$found = false;
		foreach ($arethaPaths as $path) {
			$include_file = $path . $theclass . ".class.php";
			if (is_file($include_file)) {
				include_once $include_file;				
				$found = true;
			}
		}

		// Try with globals
		if (count($GLOBALS['arethaPaths']) > 0 && !$found) {
			foreach ($GLOBALS['arethaPaths'] as $path) {
				$include_file = $path . $theclass . ".class.php";
				if (is_file($include_file)) {
					include_once $include_file;				
					$found = true;
				}
			}
		}
		
		// Error handler ===
		if (!$found) {
			echo "ARETHA ERROR: Class << " . $theclass . " >> NOT FOUND";
		}
	}
);

class Aretha {

	private static $iniFilePath        = "app.ini";
	private static $isIniFile          = false;
	private static $isDatabaseIniFile  = true;
	private static $confAretha         = null;
	private static $plainObjectPath    = "plainObjects";
	private static $entitiesPath       = "";

	private static $aretha_global_path = "";

	public function __construct() {}

	public static function allErrors() {
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}

	public static function init($iniFile = "") {
		if ( trim($iniFile) != "" && Aretha::endsWith($iniFile, ".ini") ) {
			if (is_file($iniFile)) {
				Aretha::$confAretha = parse_ini_file($iniFile, true);
				Aretha::$isIniFile = true;	
			}
		} else {
			if (is_file(Aretha::$iniFilePath)) {
				Aretha::$confAretha = parse_ini_file(Aretha::$iniFilePath, true);
				Aretha::$isIniFile = true;	
			}
		}

		if (Aretha::$isIniFile ) {
			if (isset(Aretha::$confAretha['database_settings']['default'])) {
				Aretha::$isDatabaseIniFile = true;

				Aretha::$databaseDefault = Aretha::$confAretha['database_settings']['default'];
				$default = Aretha::$databaseDefault;

				Aretha::$databaseEngine   = Aretha::$confAretha['database_settings']['database_' . $default]['engine'];
				Aretha::$databaseName     = Aretha::$confAretha['database_settings']['database_' . $default]['name'];
				Aretha::$databaseUser     = Aretha::$confAretha['database_settings']['database_' . $default]['user'];
				Aretha::$databaseHost     = Aretha::$confAretha['database_settings']['database_' . $default]['host'];
				Aretha::$databasePort     = Aretha::$confAretha['database_settings']['database_' . $default]['port'];
				Aretha::$databasePassword = Aretha::$confAretha['database_settings']['database_' . $default]['password'];
			}
		}

		Aretha::$aretha_global_path = dirname(__DIR__) . "/arethafw/";
	}

	public static function isIniFileLoaded() {
		return Aretha::$isIniFile;
	}

	public static function getGlobalPath() {
	    return Aretha::$aretha_global_path;
    }

    public static function reloadGlobalPath() {
	    Aretha::$aretha_global_path = dirname(__DIR__) . "/arethafw/";
    }

    //===================================================================================================
	//===================================================================================================	
	// Internationalization
	//===================================================================================================
	//===================================================================================================

    
	//===================================================================================================
	//===================================================================================================	
	// PlugIns
	//===================================================================================================
	//===================================================================================================

    public static function loadPlugin($name, $parameters = null) {
    	$pluginPath = ARETHA_DIRNAME . "/plugins/" . $name . "/" . $name . ".inc.php";
    	if (is_file($pluginPath)) {
    		include_once $pluginPath;
    		Aretha::import("lib.AFPlugin");
    		aretha\lib\AFPlugin::init($afPluginConf);
    	}
    }

	//===================================================================================================
	//===================================================================================================	
	// Session Helpers
	//===================================================================================================
	//===================================================================================================	
	
	public static function sessionStart() {
    	if (session_status() == PHP_SESSION_NONE) {
		    session_start();
		}
    }

    public static function sessionGranted() {
    	if (session_status() != PHP_SESSION_NONE) {
		    if (isset($_SESSION['af-granted'])) {
				if ($_SESSION['af-granted'] === true) {
					return true;
				}
			}
		}
		return false;
    }

	public static function generateToken($length, $upper = false){
	     $token    = "";
	     $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	     $max      = strlen($alphabet);

	    for ($i=0; $i < $length; $i++) {
	        $token .= $alphabet[random_int(0, $max-1)];
	    }
	    if ($upper) {
	    	return strtoupper($token);
		} else {
			return $token;
		}
	}

	//===================================================================================================
	//===================================================================================================	
	// HTML Form Helpers
	//===================================================================================================
	//===================================================================================================	
	
	public static function validateParams($fields, $prefix = "", $suffix = "") {
		$response = array(
			'status' 			=> "fail", 
			'code'              => "001",
			'message' 		    => "",
			'error_count'       => 0,
			'mandatory'         => array(),
			'type'              => array(),
			'range'             => array(),
			'fieldok'           => true
		);

		$fieldsVal = array();
		foreach ($fields as $field) {
			$fieldName = $prefix . $field['name'] . $suffix;
			if (isset($_REQUEST[$fieldName])) {
				$val = trim($_REQUEST[$fieldName]);
				$fieldsVal[$fieldName] = $val;
			}
		}

		foreach ($fields as $field) {
			$fieldName = $prefix . $field['name'] . $suffix;
			if (isset($_REQUEST[$fieldName])) {
				$val = trim($_REQUEST[$fieldName]);

				if ($field['mandatory'] == "Y") {
					if ($val == "") {
						$response['mandatory'][] = $fieldName;
						$response['fieldok']     = false;
						$errorType               = "incomplete";
						continue;
					}
				}

				// "mandatory_depends_on" => array(array("field" => "work_schedule_tipo", "values" => array("1")));
				if ($field['mandatory'] == "D") {
					if (isset($field['mandatory_depends_on'])) {
						$depends = $field['mandatory_depends_on'];
						if (is_array($depends)) {
							foreach ($depends as $depend) {
								$dependName = $prefix . $depend['field'] . $suffix;
								if (is_array($depend['values'])) {
									if (in_array($fieldsVal[$dependName], $depend['values']) ) {
										if ($val == "") {
											$response['mandatory'][] = $fieldName;
											$response['fieldok']     = false;
											$errorType               = "incomplete";
											break;
										}
									} // in_array()
								} // is_array(values)
							}
						} // is_array(depends)
					}
				}

				$isRangeError = false;
				switch ($field['type']) {
					case 'Integer':
						if (strlen($val) > 0) {
							if (!is_numeric($val)) {
								$response['type'][]  = array("name" => $fieldName, "detail" => "[Integer]");
								$response['fieldok'] = false;
								$response['error_count']++;
								$errorType           = "type";
							} else {
								$minValue = "N/A";
								$maxValue = "N/A";
								if (strlen($val) > 0) {
									if (isset($field['min_value'])) {
										$minValue = $field['min_value'];
									}
									if (isset($field['max_value'])) {
										$maxValue = $field['max_value'];
									}

									if (isset($field['min_value']) && $val < $field['min_value']) {
										$isRangeError = true;
									}
									if (isset($field['max_value']) && $val > $field['max_value']) {
										$isRangeError = true;
									}
								}
								if ($isRangeError) {
									$response['range'][] = array("name" => $fieldName, "detail" => "[Min: " . $minValue . " Max: " . $maxValue . "]");
									$response['fieldok'] = false;
									$errorType           = "range";
								}
							}
						}
						break;
					case 'Date':
						if (strlen($val) > 0) {
							if (!Aretha::isDate($val)) {
								$response['type'][]  = array("name" => $fieldName, "detail" => "[Date]");
								$response['fieldok'] = false;
								$errorType           = "type";
							}
						}
						break;
					case 'Email':
						$isTypeError = false;
						if (strlen($val) > 0) {
							if (strlen($val) < 5) {
								$response['type'][]  = array("name" => $fieldName, "detail" => "[E-mail Min: 5]");
								$isTypeError = true;
							}

							if (!strstr($val, "@")) {
								$response['type'][]  = array("name" => $fieldName, "detail" => "[E-mail must have an @]");
								$isTypeError = true;
							} else {
								$arrEmail = explode("@", $val);
								$before = ""; 
								$after  = "";
								if (is_array($arrEmail)) {
									if (count($arrEmail) == 2) {
										$before = $arrEmail[0];
										$after  = $arrEmail[1];

										if (strlen($before) == 0) {
											$response['type'][]  = array("name" => $fieldName, "detail" => "[E-mail empty before @]");
											$isTypeError = true;
										}

										if (strlen($after) == 0) {
											$response['type'][]  = array("name" => $fieldName, "detail" => "[E-mail empty after @]");
											$isTypeError = true;
										} else {
											if (!strstr($after, ".")) {
												$response['type'][]  = array("name" => $fieldName, "detail" => "[E-mail must have a .]");
												$isTypeError = true;
											} else {
												$arrDomain = explode(".", $after);
												if (is_array($arrDomain)) {
													if (count($arrDomain) == 2) {
														$before = $arrDomain[0];
														$after  = $arrDomain[1];
														if (strlen($before) == 0) {
															$response['type'][]  = array("name" => $fieldName, "detail" => "[E-mail empty before .]");
															$isTypeError = true;
														}

														if (strlen($after) == 0) {
															$response['type'][]  = array("name" => $fieldName, "detail" => "[E-mail empty after .]");
															$isTypeError = true;
														}
													}

													if (count($arrDomain) == 3) {
														$before = $arrDomain[1];
														$after  = $arrDomain[2];
														if (strlen($before) == 0) {
															$response['type'][]  = array("name" => $fieldName, "detail" => "[E-mail empty before .]");
															$isTypeError = true;
														}

														if (strlen($after) == 0) {
															$response['type'][]  = array("name" => $fieldName, "detail" => "[E-mail empty after .]");
															$isTypeError = true;
														}
													}
												}
												
											}
										}


									} else {
										$response['type'][]  = array("name" => $fieldName, "detail" => "[E-mail must have two parts]");
										$isTypeError = true;
									}
								}
							}

							
							if ($isTypeError) {
								$response['fieldok'] = false;
								$errorType           = "type";
							} 
						}
						break;
					case 'String':
						$minLength = "N/A";
						$maxLength = "N/A";
						if (strlen($val) > 0) {
							if (isset($field['min_length'])) {
								$minLength = $field['min_length'];
							}
							if (isset($field['max_length'])) {
								$maxLength = $field['max_length'];
							}

							if (isset($field['min_length']) && strlen($val) < $field['min_length']) {
								$isRangeError = true;
							}
							if (isset($field['max_length']) && strlen($val) > $field['max_length']) {
								$isRangeError = true;
							}

							if ($isRangeError) {
								$response['error_count']++;
								$response['range'][] = array("name" => $fieldName, "detail" => "[Min: " . $minLength . " Max: " . $maxLength . "]");
								$response['fieldok'] = false;
								$errorType           = "range";
							}
						}
						break;
					case 'Phone':
						$minLength = "N/A";
						$maxLength = "N/A";
						if (strlen($val) > 0) {
							if (isset($field['min_length'])) {
								$minLength = $field['min_length'];
							}
							if (isset($field['max_length'])) {
								$maxLength = $field['max_length'];
							}

							if (isset($field['min_length']) && strlen($val) < $field['min_length']) {
								$isRangeError = true;
							}
							if (isset($field['max_length']) && strlen($val) > $field['max_length']) {
								$isRangeError = true;
							}

							if ($isRangeError) {
								$response['error_count']++;
								$response['range'][] = array("name" => $fieldName, "detail" => "[Min: " . $minLength . " Max: " . $maxLength . "]");
								$response['fieldok'] = false;
								$errorType           = "range";
							}

							if (!is_numeric($val)) {
								$response['error_count']++;
								$response['type'][]  = array("name" => $fieldName, "detail" => "[Phone is numeric]");
								$response['fieldok'] = false;
								$errorType = "type";
							}
						}
						break;
					case 'Zipcode':
						$minLength = "N/A";
						$maxLength = "N/A";
						if (strlen($val) > 0) {
							if (isset($field['min_length'])) {
								$minLength = $field['min_length'];
							}
							if (isset($field['max_length'])) {
								$maxLength = $field['max_length'];
							}

							if (isset($field['min_length']) && strlen($val) < $field['min_length']) {
								$isRangeError = true;
							}
							if (isset($field['max_length']) && strlen($val) > $field['max_length']) {
								$isRangeError = true;
							}

							if ($isRangeError) {
								$response['error_count']++;
								$response['range'][] = array("name" => $fieldName, "detail" => "[Min: " . $minLength . " Max: " . $maxLength . "]");
								$response['fieldok'] = false;
								$errorType           = "range";
							}

							if (!is_numeric($val)) {
								$response['error_count']++;
								$response['type'][]  = array("name" => $fieldName, "detail" => "[Zipcode is numeric]");
								$response['fieldok'] = false;
								$errorType = "type";
							}
						}
						break;
					case 'Catalog':
						if (strlen($val) > 0) {
							if (isset($field['catalog'])) {
								if (!in_array($val, $field['catalog'])) {
									$response['type'][]  = array("name" => $fieldName, "detail" => "[Catalog: ". implode("|", $field['catalog']) . "]");
									$response['fieldok'] = false;
									$errorType           = "catalog";
								}
							} else {
								$response['type'][]  = array("name" => $fieldName, "detail" => "[Unknow Catalog]");
								$response['fieldok'] = false;
								$errorType = "unknow_catalog";
							}
						}
						break;
				} // END EVALUATION OF TYPE
			} else {
				$response['status']  = "fail";
				$response['code']    = "P001";
				$response['message'] = "Parámetros incompletos, intente recargar la aplicación.";
				break;
			}
		} // end foreach

		if (!$response['fieldok']) {
			$response['status']      = "fail";

			switch ($errorType) {
				case 'incomplete':
					$response['code']    = "P002";
					$response['message'] = "Parámetros obligatorios incompletos";
					break;
				case 'type':
					$response['code']    = "P003";
					$response['message'] = "Parámetros con el tipo incorrecto.";
					break;
				case 'unknow_catalog':
					$response['code']    = "P004";
					$response['message'] = "Catálogo no definido.";
					break;
				case 'catalog':
					$response['code']    = "P005";
					$response['message'] = "Valor no corresponde a catálogo.";
					break;
				case 'range':
					$response['code']    = "P006";
					$response['message'] = "Valor fuera de rango.";
					break;
			}
		}

		return $response;
	}

	//===================================================================================================
	//===================================================================================================
	// Data Validators
	//===================================================================================================
	//===================================================================================================

	public static function isDate($date, $format = 'Y-m-d H:i:s') {
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}

	public static function endsWith($haystack, $needle) {
		return substr($haystack, -strlen($needle)) === $needle;
	}

	//===================================================================================================
	//===================================================================================================
	// Import Handlers
	//===================================================================================================
	//===================================================================================================

	public static function import($package = "") {
		if (trim($package) == "") {
			return false;
		}

		$thePath     = "";
		$targetClass = "";
		$arrPath     = null;
		$mid_path    = "";
		$path        = "";
		$pathClass   = "";
		$countPath   = 0;

		$targetClass = "UndefinedClassDummyName";
		$mid_path    = str_replace (".", "/", $package);
		$path        = $mid_path . ".php";
		$pathFW      = ARETHA_DIRNAME . "/" . $mid_path . ".php";
		$pathClass   = $mid_path . ".class.php";
		$pathClassFW = ARETHA_DIRNAME . "/" . $mid_path . ".class.php";
		$arrPath     = explode(".", $package);
		$countPath   = count($arrPath);

		if (is_file($path)) {
			$thePath     = $path;
			$targetClass = $arrPath[$countPath-1];
		} 

		if (is_file($pathFW)) {
			$thePath     = $pathFW;
			$targetClass = $arrPath[$countPath-1];
		} 

		if (is_file($pathClass)) {
			$thePath     = $pathClass;
			$targetClass = $arrPath[$countPath-1];
		}

		if (is_file($pathClassFW)) {
			$thePath     = $pathClassFW;
			$targetClass = $arrPath[$countPath-1];
		}

		if ($thePath != "") {
			if (Aretha::isPlainObject($thePath)) {
				$targetClass .= "PO";
			}

			if (!in_array($targetClass, get_declared_classes()) ) {
				require_once $thePath;
			} else {
				
			}
		}
	}

	/**
	 * Check Path and Determine if an import 
	 * it is a "Plain Object Import"
	 */
	private static function isPlainObject($path) {
		if (strpos($path, Aretha::$plainObjectPath) !== false) {
			return true;
		}
		return false;
	}

	//===================================================================================================
	//===================================================================================================
	// BBDD Handlers
	//===================================================================================================
	//===================================================================================================
	
	// Database Settings from .ini file
    private static $databaseEngine   = "";
    private static $databaseName     = "";
    private static $databaseUser     = "";
    private static $databasePassword = "";
    private static $databasePort     = "";
    private static $databaseHost     = "";
	private static $databaseDefault  = "";
	
	private static $databaseEngineTemp   = "";
    private static $databaseNameTemp     = "";
    private static $databaseUserTemp     = "";
    private static $databasePasswordTemp = "";
    private static $databasePortTemp     = "";
    private static $databaseHostTemp     = "";
	private static $databaseDefaultTemp  = "";

	public static function isDatabaseIniFileLoaded() { return Aretha::$isDatabaseIniFile; }
	public static function getDatabaseEngine()       { return Aretha::$databaseEngine;    }
	public static function getDatabaseName()         { return Aretha::$databaseName;      }
	public static function getDatabaseUser()         { return Aretha::$databaseUser;      }
	public static function getDatabasePassword()     { return Aretha::$databasePassword;  }
	public static function getDatabaseHost()         { return Aretha::$databaseHost;      }
	public static function getDatabasePort()         { return Aretha::$databasePort;      }

	public static function loadDatabase($databaseName) {
		Aretha::$databaseEngineTemp   = Aretha::$databaseEngine;
		Aretha::$databaseNameTemp     = Aretha::$databaseName;
		Aretha::$databaseUserTemp     = Aretha::$databaseUser;
		Aretha::$databaseHostTemp     = Aretha::$databaseHost;
		Aretha::$databasePortTemp     = Aretha::$databasePort;
		Aretha::$databasePasswordTemp = Aretha::$databasePassword;
		
		Aretha::$databaseEngine   = Aretha::$confAretha['database_settings']['database_' . $databaseName]['engine'];
		Aretha::$databaseName     = Aretha::$confAretha['database_settings']['database_' . $databaseName]['name'];
		Aretha::$databaseUser     = Aretha::$confAretha['database_settings']['database_' . $databaseName]['user'];
		Aretha::$databaseHost     = Aretha::$confAretha['database_settings']['database_' . $databaseName]['host'];
		Aretha::$databasePort     = Aretha::$confAretha['database_settings']['database_' . $databaseName]['port'];
		Aretha::$databasePassword = Aretha::$confAretha['database_settings']['database_' . $databaseName]['password'];
	}

	public static function reloadLastDatabase() {
		// Save Current Database 
		$engine           = Aretha::$databaseEngine;
		$databaseName     = Aretha::$databaseName;
		$databaseUser     = Aretha::$databaseUser;
		$databaseHost     = Aretha::$databaseHost;
		$databasePort     = Aretha::$databasePort;
		$dataBasePassword = Aretha::$databasePassword;
		// Set Last Database
		Aretha::$databaseEngine   = Aretha::$databaseEngineTemp;
		Aretha::$databaseName     = Aretha::$databaseNameTemp;
		Aretha::$databaseUser     = Aretha::$databaseUserTemp;
		Aretha::$databaseHost     = Aretha::$databaseHostTemp;
		Aretha::$databasePort     = Aretha::$databasePortTemp;
		Aretha::$databasePassword = Aretha::$databasePasswordTemp;
		// "Current" Database is now "Last Database"
		Aretha::$databaseEngineTemp   = $engine;
		Aretha::$databaseNameTemp     = $databaseName;
		Aretha::$databaseUserTemp     = $databaseUser;
		Aretha::$databaseHostTemp     = $databaseHost;
		Aretha::$databasePortTemp     = $databasePort;
		Aretha::$databasePasswordTemp = $databasePassword;
	}


	public static function createINIFile($path, $path_t, $engine, $name, $user, $password, $port, $host = "localhost", $persistent = "false") {
		if ($path == "" || $path_t == "" || $engine == "" || $name == "" || $user == "" || $port == "") {
			return false;
		}

		$template = file_get_contents($path_t);
		$content  = sprintf($template, $engine, $name, $user, $password, $port, $host, $persistent);
		if (file_put_contents($path, $content) === FALSE) {
			return false;
		}
		return true;
	}

	//==============================================================================================	
	// PostgreSQL Helpers
	//==============================================================================================	
	
	/**
	 * @param array
	 * @return PostgreSQL Param Array
	 * 
	 * Convierte un array de PHP en un 'param array' de PostgreSQL
	 */
	public static function toPostgreSQLFunctionArray($arrTemp){
		$retorno = '{';
		foreach($arrTemp as $d){
			$retorno .= (!is_string($d)) ? $d."," : '"'.$d.'",';
		}
		if($retorno != '{'){
			$retorno = substr($retorno, 0, -1);
		}
		$retorno .= '}';
		return $retorno;
	}

	//==============================================================================================	
	// String Handler
	//==============================================================================================	
	
	public function escape_string($string) {
	    switch($this->engine) {
		    case DataAccess::ENGINE_POSTGRESQL : $string = pg_escape_string($string); break;
		    case DataAccess::ENGINE_MYSQL      : $string = mysql_real_escape_string($string); break;
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
}
?>