<?php
require_once "my_api.php";
require_once '../php/db_connect.php';

// Requests from the same server don't have a HTTP_ORIGIN header
if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

try {
    $API = new MyAPI($_REQUEST['request'], $_SERVER['HTTP_ORIGIN'], $db);
    $token = $API->getToken();
    
    if($API->authorizeToken($token)) {
       echo $API->processAPI();
    }
    else{
        header('HTTP/1.0 401 Unauthorized');
        throw new Exception('Unauthorized');
    }
} 
catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}

?>