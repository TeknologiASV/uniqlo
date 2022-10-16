<?php
require_once "db_connect.php";

//session_start();

if(isset($_POST['userID'])){

}
else{
    echo json_encode(
        array(
            "status" => "failed",
            "message" => "Missing Parameter"
        )
    ); 
}