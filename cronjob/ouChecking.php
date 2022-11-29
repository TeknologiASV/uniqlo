<?php
//require_once "db_connect.php";
require_once(PATH . "db_connect.php");

$date = date('d-m-y H:i:s');
$startDate = date('Y-m-d H:i:s', strtotime(' -1 hours'));
$endDate = date('Y-m-d H:i:s', strtotime(' +1 hours'));
$mode = 'Ground';

if ($select_stmt = $db->prepare("SELECT COUNT(*) FROM uniqlo_1u WHERE Mode=? AND Date>=? AND Date<=? ORDER BY Date")) {
    $select_stmt->bind_param('sss', $mode, $startDate, $endDate);

    if ($select_stmt->execute()) {
        $result = $select_stmt->get_result();

        if($row = $result->fetch_assoc()) {
            if($row['COUNT(*)'] == '0'){
                $to = "adrianlim@t-asv.com";
                $subject = "Uniqlo 1U Sensors Issues";
                $txt = "For your information, the GROUND FLOOR Sensor of Uniqlo 1 Utama have problems. Please check the dashboard or sensors";
                $headers = "From: notification@t-asv.com" . "\r\n" . "CC: kinrong@t-asv.com,fathi.mahdi@t-asv.com";

                mail($to,$subject,$txt,$headers);
            }
        }
    }
}

$mode = 'Level 1';

if ($select_stmt2 = $db->prepare("SELECT COUNT(*) FROM uniqlo_1u WHERE Mode=? AND Date>=? AND Date<=? ORDER BY Date")) {
    $select_stmt2->bind_param('sss', $mode, $startDate, $endDate);

    if ($select_stmt2->execute()) {
        $result2 = $select_stmt2->get_result();

        if($row2 = $result2->fetch_assoc()) {
            if($row2['COUNT(*)'] == '0'){
                $to = "adrianlim@t-asv.com";
                $subject = "Uniqlo 1U Sensors Issues";
                $txt = "For your information, the LEVEL 1 Sensor of Uniqlo 1 Utama have problems. Please check the dashboard or sensors";
                $headers = "From: notification@t-asv.com" . "\r\n" . "CC: kinrong@t-asv.com,fathi.mahdi@t-asv.com";

                mail($to,$subject,$txt,$headers);
            }
        }
    }
}
?>