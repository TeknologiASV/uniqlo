<?php
/*list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':' , base64_decode(substr($_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 6)));*/

abstract class API{
    protected $method = '';
    protected $endpoint = '';
    protected $verb = '';
    protected $args = array();
    protected $file = null;
    protected $token = null;

    public function __construct($request) {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        $this->args = explode('/', rtrim($request, '/'));
        //$this->args = array_slice($this->args, 2);
        $this->endpoint = array_shift($this->args);
        $this->method = $_SERVER['REQUEST_METHOD'];
        
        if (array_key_exists(0, $this->args) && !is_numeric($this->args[0])) {
            $this->verb = array_shift($this->args);
        }

        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new Exception("Unexpected Header");
            }
        }
    }
    
    public function getToken(){
        $token = null;
        
        if (isset($_SERVER['PHP_AUTH_USER'])) {
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];
            $token = base64_encode("$username:$password");
        } 
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            if (strpos(strtolower($_SERVER['REDIRECT_HTTP_AUTHORIZATION']),'basic')===0)
                $token = base64_decode(substr($_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 6));
        }
        
        if (is_null($token)) {
            header('HTTP/1.0 401 Unauthorized');
            return false;
        }
        
        return $token;
    }

    public function processAPI() {
        switch($this->method) {
            case 'DELETE':
            case 'POST':
                $this->request = $this->_cleanInputs($_POST);
                break;
            case 'GET':
                $this->request = $this->_cleanInputs($_GET);
                break;
            case 'PUT':
                $this->request = $this->_cleanInputs($_GET);
                $this->file = file_get_contents("php://input");
                break;
            default:
                return $this->_response('Invalid Method', 405);
                break;
        }

        if ((int)method_exists($this, $this->endpoint) > 0) {
            return $this->_response($this->{$this->endpoint}($this->args));
        }

        return $this->_response("No Endpoint: $this->endpoint", 404);
    }

    private function _response($data, $status = 200) {
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        return json_encode($data, JSON_PRETTY_PRINT); // JSON_PRETTY_PRINT only works in PHP >= 5.4
    }

    private function _cleanInputs($data) {
        $clean_input = Array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->_cleanInputs($v);
            }
        } 
        else {
            if(get_magic_quotes_gpc()){
	    	    $data = trim(stripslashes($data));
	    	}
            
            $clean_input = trim(strip_tags($data));
        }
        
        return $clean_input;
    }

    private function _requestStatus($code) {
        $status = array(  
            200 => 'OK',
            404 => 'Not Found',   
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        ); 
        
        return ($status[$code]) ? $status[$code] : $status[500]; 
    }
}

?>