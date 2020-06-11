<?php
/**
 *
 * @author Cristian A. Rodriguez Enriquez
 * @version V2.16 14 May 2020
 *
 * Latest version is available at https://github.com/crodriguezen/arethafw
 * 
 * Distributed under the MIT License (license terms are at http://opensource.org/licenses/MIT).
 *
 */
namespace aretha\php\ws;

class RESTWS {

	public $httpCode = "";

	public static $CURL_OPTS = array(
        CURLOPT_USERAGENT      => "ARETHA-2.16", 
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_CONNECTTIMEOUT => 10, 
        CURLOPT_RETURNTRANSFER => 1, 
        CURLOPT_TIMEOUT        => 60
    );

    public function getHTTPCode() {
    	return $this->httpCode;
    }

    /**
     * Execute a POST Request
     * 
     * @param string $body
     * @param array $params
     * @return mixed
     */
    public function post($uri, $body = null, $params = array(), $buildUrl = false) {
        $body = json_encode($body);
        $opts = array(
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_POST       => true, 
            CURLOPT_POSTFIELDS => $body
        );
        
        $exec = $this->execute($uri, $opts, $params);
        return $exec;
    }

	/**
     * Execute a GET Request
     * 
     * @param string $uri
     * @param array $params
     * @param boolean $assoc
     * @return mixed
     */
    public function get($uri, $params = null, $assoc = false, $buildUrl = false) {
        $exec = $this->execute($uri, null, $params, $assoc, true);
        return $exec;
    }

    /**
     * Execute a DELETE Request
     * 
     * @param string $path
     * @param array $params
     * @return mixed
     */
    public function delete($uri, $params) {
        $opts = array(
            CURLOPT_CUSTOMREQUEST => "DELETE"
        );
        $exec = $this->execute($uri, $opts, $params);
        return $exec;
    }

    /**
     * Execute a PUT Request
     * 
     * @param string $path
     * @param string $body
     * @param array $params
     * @return mixed
     */
    public function put($uri, $body = null, $params = array()) {
        $body = json_encode($body);
        $opts = array(
            CURLOPT_HTTPHEADER    => array('Content-Type: application/json'),
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS    => $body
        );
        $exec = $this->execute($uri, $opts, $params);
        return $exec;
    }

    /**
     * Execute a OPTION Request
     * 
     * @param string $path
     * @param array $params
     * @return mixed
     */
    public function options($uri, $params = null) {
        $opts = array(
            CURLOPT_CUSTOMREQUEST => "OPTIONS"
        );
        $exec = $this->execute($uri, $opts, $params);
        return $exec;
    }

    /**
     * Execute a curl requests and returns the json body and headers of the response
     * 
     * @param string $uri
     * @param array $options
     * @param array $params
     * @param boolean $assoc
     * @return mixed
     */
	public function execute($uri, $options = array(), $params = null, $assoc = false, $buildUrl = false) {
		if ($buildUrl) {
			$uri = $this->makePath($uri, $params);
		}

		$ch = curl_init($uri);
		curl_setopt_array($ch, self::$CURL_OPTS);	

		if (!empty($options)) {
			curl_setopt_array($ch, $options);
		}

		$return = json_decode(curl_exec($ch), $assoc);
        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        
        return $return;
	}

	/**
     * Construct an URL to make requests
     * 
     * @param string $uri
     * @param array $params
     * @return string
     */
    public function makePath($uri, $params = array()) {
        if(!empty($params)) {
            $paramsJoined = array();

            foreach($params as $param => $value) {
               $paramsJoined[] = "$param=$value";
            }
            $params = '?'.implode('&', $paramsJoined);
            $uri = $uri . $params;
        }
        return $uri;
    }

}
?>