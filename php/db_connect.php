<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
$db = mysqli_connect("tasv-db.czjdmxerbddi.ap-southeast-1.rds.amazonaws.com", "tasv", "PublikaTASV", "uniqlo");

if(mysqli_connect_errno()){
    echo 'Database connection failed with following errors: ' . mysqli_connect_error();
    die();
}
?>
