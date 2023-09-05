<?php
require_once "db_connect.php";
//require_once(PATH . "db_connect.php");

// Define your email settings
$to = "kinrong@t-asv.com";
$subject = "Uniqlo DAS Sensors Issues";
$headers = "From: notification@t-asv.com\r\n";

// Get the current date and time
$currentDateTime = date('Y-m-d H:00:00');

// Calculate the start time for the past hour
$pastHourDateTime = date('Y-m-d H:00:00', strtotime('-1 hour'));

// Get the current date and time
$currentDate = date('Y-m-d');

// Check if any records exist in the "notification" table for the current day
$checkNotificationQuery = "SELECT COUNT(*) FROM notification WHERE outlet = 'DAS' AND date = ?";
$checkNotificationStmt = $db->prepare($checkNotificationQuery);
$checkNotificationStmt->bind_param('s', $currentDate);
$checkNotificationStmt->execute();
$checkNotificationResult = $checkNotificationStmt->get_result();

if ($checkNotificationResult === false) {
    // Handle the database query error, e.g., log it or send an alert
    exit("Database query error: " . $db->error);
}

$row = $checkNotificationResult->fetch_assoc();
if ((int)$row['COUNT(*)'] > 0) {
    exit();
}
else{
    $checkSensorsQuery = "SELECT devices.Device FROM ( SELECT DISTINCT Device FROM uniqlo_DA ) AS devices LEFT JOIN ( SELECT DISTINCT Device FROM uniqlo_DA WHERE Date BETWEEN ? AND ? ) AS d ON devices.Device = d.Device WHERE d.Device IS NULL;";
    $checkSensorsStmt = $db->prepare($checkSensorsQuery);
    $checkSensorsStmt->bind_param('ss', $pastHourDateTime, $currentDateTime);
    $checkSensorsStmt->execute();
    $checkSensorsResult = $checkSensorsStmt->get_result();

    if ($checkSensorsResult === false) {
        // Handle the database query error, e.g., log it or send an alert
        exit("Database query error: " . $db->error);
    }

    $groundFloorSensorIssues = [];

    while ($row = $checkSensorsResult->fetch_assoc()) {
        $device = $row['Device'];
        $groundFloorSensorIssues[] = $device;
    }

    // Send email notifications if there are sensor issues
    if(!empty($groundFloorSensorIssues)){
        $message = "For your information, the following sensors of Uniqlo Damansara have problems and have no data for the past hour:\n\n";
        
        foreach ($groundFloorSensorIssues as $device) {
            $message .= "- " . $device . "\n";
        }

        if(mail($to, $subject, $message, $headers)){
            // Insert a notification record into the "notification" table
            $insertNotificationQuery = "INSERT INTO notification (date, sent, outlet) VALUES (?, 'Y', 'DAS')";
            $insertNotificationStmt = $db->prepare($insertNotificationQuery);
            $insertNotificationStmt->bind_param('s', $currentDate);
            $insertNotificationStmt->execute();
        }
    }
}

// Close the database connection
$db->close();
?>
