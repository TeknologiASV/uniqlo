<?php
//require_once "db_connect.php";
require_once(PATH . "db_connect.php");

$date = date('d-m-y H:i:s');
$startDate = date('Y-m-d H:i:s', strtotime(' -1 hours'));
$endDate = date('Y-m-d H:i:s', strtotime(' +1 hours'));
$today = date('Y-m-d');
$branch = 'OU';

$check_stmt = $db->prepare("SELECT COUNT(*) FROM notification WHERE outlet = 'OU' AND date LIKE ?");
$check_stmt->bind_param('s', $today);
$check_stmt->execute();
$check_result = $check_stmt->get_result();
$check_row = $check_result->fetch_assoc();
$alreadySent = ((int)$check_row['COUNT(*)'] > 0);

if (!$alreadySent) {
    if ($select_stmt = $db->prepare("SELECT COUNT(*) FROM uniqlo_1u WHERE Device IN ('RPi-4', 'RPi-6', 'RPi-8', 'RPi-12', 'RPi-13', 'RPi-14', 'RPi-9') AND Date>=? AND Date<=? ORDER BY Date")) {
        $select_stmt->bind_param('ss', $startDate, $endDate);

        if ($select_stmt->execute()) {
            $result = $select_stmt->get_result();

            if($row = $result->fetch_assoc()) {
                if((int)$row['COUNT(*)'] < 21){
                    $to = "kinrong@t-asv.com";
                    $subject = "Uniqlo 1U Sensors Issues";
                    $txt = "For your information, the GROUND FLOOR Sensor of Uniqlo 1 Utama have problems. Please check the dashboard or sensors";
                    //$headers = "From: notification@t-asv.com" . "\r\n" . "CC: kinrong@t-asv.com,ellatan@t-asv.com";
                    $headers = "From: notification@t-asv.com" . "\r\n";

                    if (mail($to,$subject,$txt,$headers)) {
                        $sent = "Y"; // Email successfully sent
                    } else {
                        $sent = "N"; // Email sending failed
                    }

                    // Insert the notification record into the 'notification' table
                    if ($insert_stmt = $db->prepare("INSERT INTO notification (date, sent, outlet) VALUES (?, ?, ?)")) {
                        $insert_stmt->bind_param('sss', $today, $sent, $branch);
                        $insert_stmt->execute();
                    }
                }
            }
        }
    }

    if ($select_stmt2 = $db->prepare("SELECT COUNT(*) FROM uniqlo_1u WHERE Device IN ('RPi-1', 'RPi-3', 'RPi-7', 'RPi-11', 'RPi-5') Date>=? AND Date<=? ORDER BY Date")) {
        $select_stmt2->bind_param('sss', $mode, $startDate, $endDate);

        if ($select_stmt2->execute()) {
            $result2 = $select_stmt2->get_result();

            if($row2 = $result2->fetch_assoc()) {
                if((int)$row2['COUNT(*)'] < 15){
                    $to = "kinrong@t-asv.com";
                    $subject = "Uniqlo 1U Sensors Issues";
                    $txt = "For your information, the LEVEL 1 Sensor of Uniqlo 1 Utama have problems. Please check the dashboard or sensors";
                    //$headers = "From: notification@t-asv.com" . "\r\n" . "CC: kinrong@t-asv.com,ellatan@t-asv.com";
                    $headers = "From: notification@t-asv.com" . "\r\n";

                    if (mail($to,$subject,$txt,$headers)) {
                        $sent = "Y"; // Email successfully sent
                    } else {
                        $sent = "N"; // Email sending failed
                    }

                    // Insert the notification record into the 'notification' table
                    if ($insert_stmt = $db->prepare("INSERT INTO notification (date, sent, outlet) VALUES (?, ?, ?)")) {
                        $insert_stmt->bind_param('sss', $today, $sent, $branch);
                        $insert_stmt->execute();
                    }
                }
            }
        }
    }
}
?>