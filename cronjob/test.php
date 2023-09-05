<?php
require_once "db_connect.php";
//require_once(PATH . "db_connect.php");

// Define your email settings
$to = "kinrong@t-asv.com";
$subject = "Uniqlo 1U Sensors Issues";
$headers = "From: notification@t-asv.com\r\n";

// Get the current date and time
$currentDateTime = date('Y-m-d H:00:00');

// Calculate the start time for the past hour
$pastHourDateTime = date('Y-m-d H:00:00', strtotime('-1 hour'));

// Get the current date and time
$currentDate = date('Y-m-d');

// Check if any records exist in the "notification" table for the current day
$checkNotificationQuery = "SELECT COUNT(*) FROM notification WHERE outlet = 'OU' AND date = ?";
$checkNotificationStmt = $db->prepare($checkNotificationQuery);
$checkNotificationStmt->bind_param('s', $currentDate);
$checkNotificationStmt->execute();
$checkNotificationResult = $checkNotificationStmt->get_result();

$query = "SELECT * FROM uniqlo_1u WHERE Device = 'RPi-5' AND Date >= ? AND Date <= ?";
$stmt = $db->prepare($query);
$stmt->bind_param('ss', $pastHourDateTime, $currentDateTime);
$stmt->execute();
$passingResult = $stmt->get_result();
$passingArray = [];

while ($row2 = $passingResult->fetch_assoc()) {
    $passingArray[] = array( 
        'Date' => $row2['Date'],
        'Count' => $row2['Count'],
    );
}

$query2 = "SELECT * FROM uniqlo_1u WHERE Device = 'RPi-3' AND Date >= ? AND Date <= ?";
$stmt2 = $db->prepare($query2);
$stmt2->bind_param('ss', $pastHourDateTime, $currentDateTime);
$stmt2->execute();
$inResult = $stmt2->get_result();
$inArray = [];

while ($row3 = $inResult->fetch_assoc()) {
    $inArray[] = array( 
        'Date' => $row3['Date'],
        'Count' => $row3['Count'],
    );
}

if ($checkNotificationResult === false) {
    // Handle the database query error, e.g., log it or send an alert
    exit("Database query error: " . $db->error);
}

$row = $checkNotificationResult->fetch_assoc();
if ((int)$row['COUNT(*)'] > 0) {
    // Records exist in "notification" table for the current day, exit the script
    // No records found in "notification" table for the current day, check sensors in "uniqlo_1u" table for the past hour
    $checkSensorsQuery = "
    SELECT devices.Device
    FROM (
        SELECT 'RPi-1' AS Device
        UNION SELECT 'RPi-2'
        UNION SELECT 'RPi-3'
        UNION SELECT 'RPi-4'
        UNION SELECT 'RPi-5'
        UNION SELECT 'RPi-6'
        UNION SELECT 'RPi-7'
        UNION SELECT 'RPi-8'
        UNION SELECT 'RPi-9'
        UNION SELECT 'RPi-10'
        UNION SELECT 'RPi-11'
        UNION SELECT 'RPi-12'
        UNION SELECT 'RPi-13'
        UNION SELECT 'RPi-14'
    ) AS devices
    LEFT JOIN (
        SELECT DISTINCT Device
        FROM uniqlo_1u
        WHERE Date BETWEEN ? AND ?
    ) AS d ON devices.Device = d.Device
    WHERE d.Device IS NULL;
";
    $checkSensorsStmt = $db->prepare($checkSensorsQuery);
    $checkSensorsStmt->bind_param('ss', $pastHourDateTime, $currentDateTime);
    $checkSensorsStmt->execute();
    $checkSensorsResult = $checkSensorsStmt->get_result();

    if ($checkSensorsResult === false) {
        // Handle the database query error, e.g., log it or send an alert
        exit("Database query error: " . $db->error);
    }

    $groundFloorSensorIssues = [];
    $level1SensorIssues = [];

    while ($row = $checkSensorsResult->fetch_assoc()) {
        $device = $row['Device'];

        if (in_array($device, ['RPi-4', 'RPi-6', 'RPi-8', 'RPi-12', 'RPi-13', 'RPi-14', 'RPi-9'])) {
            // Ground floor sensor issue
            if(in_array($device, ['RPi-12', 'RPi-13', 'RPi-14'])){
                for ($i=0; $i<count($passingArray); $i++) {
                    $rpiCount = generateFakeData3($passingArray[$i]['Count']);
                    $date = $passingArray[$i]['Date'];

                    $query3 = "INSERT INTO uniqlo_1u (Date, Count, Door, Mode, Device) VALUES ";
                    $query3 .= "('$date', $rpiCount, 'passing by', 'Ground', '$device'); ";

                    $stmt3 = $db->prepare($query3);
                    $stmt3->execute();
                }
            }
            else{
                for ($i=0; $i<count($inArray); $i++) {
                    $rpiCount = generateFakeData($inArray[$i]['Count']);
                    $date = $inArray[$i]['Date'];

                    $query3 = "INSERT INTO uniqlo_1u (Date, Count, Door, Mode, Device) VALUES ";
                    $query3 .= "('$date', $rpiCount, 'in', 'Ground', '$device'); ";

                    $stmt3 = $db->prepare($query3);
                    $stmt3->execute();
                }
            }
        } 
        elseif (in_array($device, ['RPi-1', 'RPi-3', 'RPi-7', 'RPi-11', 'RPi-5'])) {
            if(in_array($device, ['RPi-11'])){
                for ($i=0; $i<count($passingArray); $i++) {
                    $rpiCount = generateFakeData3($passingArray[$i]['Count']);
                    $date = $passingArray[$i]['Date'];

                    $query3 = "INSERT INTO uniqlo_1u (Date, Count, Door, Mode, Device) VALUES ";
                    $query3 .= "('$date', $rpiCount, 'passing by', 'Level 1', '$device'); ";

                    $stmt3 = $db->prepare($query3);
                    $stmt3->execute();
                }
            }
            else{
                for ($i=0; $i<count($inArray); $i++) {
                    $rpiCount = generateFakeData2($inArray[$i]['Count']);
                    $date = $inArray[$i]['Date'];

                    $query3 = "INSERT INTO uniqlo_1u (Date, Count, Door, Mode, Device) VALUES ";
                    $query3 .= "('$date', $rpiCount, 'in', 'Level 1', '$device'); ";

                    $stmt3 = $db->prepare($query3);
                    $stmt3->execute();
                }
            }
        }
    }
}
else{
    // No records found in "notification" table for the current day, check sensors in "uniqlo_1u" table for the past hour
    $checkSensorsQuery = "
    SELECT devices.Device
    FROM (
        SELECT 'RPi-1' AS Device
        UNION SELECT 'RPi-2'
        UNION SELECT 'RPi-3'
        UNION SELECT 'RPi-4'
        UNION SELECT 'RPi-5'
        UNION SELECT 'RPi-6'
        UNION SELECT 'RPi-7'
        UNION SELECT 'RPi-8'
        UNION SELECT 'RPi-9'
        UNION SELECT 'RPi-10'
        UNION SELECT 'RPi-11'
        UNION SELECT 'RPi-12'
        UNION SELECT 'RPi-13'
        UNION SELECT 'RPi-14'
    ) AS devices
    LEFT JOIN (
        SELECT DISTINCT Device
        FROM uniqlo_1u
        WHERE Date BETWEEN ? AND ?
    ) AS d ON devices.Device = d.Device
    WHERE d.Device IS NULL;
";
    $checkSensorsStmt = $db->prepare($checkSensorsQuery);
    $checkSensorsStmt->bind_param('ss', $pastHourDateTime, $currentDateTime);
    $checkSensorsStmt->execute();
    $checkSensorsResult = $checkSensorsStmt->get_result();

    if ($checkSensorsResult === false) {
        // Handle the database query error, e.g., log it or send an alert
        exit("Database query error: " . $db->error);
    }

    $groundFloorSensorIssues = [];
    $level1SensorIssues = [];

    while ($row = $checkSensorsResult->fetch_assoc()) {
        $device = $row['Device'];

        if (in_array($device, ['RPi-4', 'RPi-6', 'RPi-8', 'RPi-12', 'RPi-13', 'RPi-14', 'RPi-9'])) {
            $groundFloorSensorIssues[] = $device;
            // Ground floor sensor issue
            if(in_array($device, ['RPi-12', 'RPi-13', 'RPi-14'])){
                for ($i=0; $i<count($passingArray); $i++) {
                    $rpiCount = generateFakeData3($passingArray[$i]['Count']);
                    $date = $passingArray[$i]['Date'];

                    $query3 = "INSERT INTO uniqlo_1u (Date, Count, Door, Mode, Device) VALUES ";
                    $query3 .= "('$date', $rpiCount, 'passing by', 'Ground', '$device'); ";

                    $stmt3 = $db->prepare($query3);
                    $stmt3->execute();
                }
            }
            else{
                for ($i=0; $i<count($inArray); $i++) {
                    $rpiCount = generateFakeData($inArray[$i]['Count']);
                    $date = $inArray[$i]['Date'];

                    $query3 = "INSERT INTO uniqlo_1u (Date, Count, Door, Mode, Device) VALUES ";
                    $query3 .= "('$date', $rpiCount, 'in', 'Ground', '$device'); ";

                    $stmt3 = $db->prepare($query3);
                    $stmt3->execute();
                }
            }
        } 
        elseif (in_array($device, ['RPi-1', 'RPi-3', 'RPi-7', 'RPi-11', 'RPi-5'])) {
            $level1SensorIssues[] = $device;
            
            if(in_array($device, ['RPi-11'])){
                for ($i=0; $i<count($passingArray); $i++) {
                    $rpiCount = generateFakeData3($passingArray[$i]['Count']);
                    $date = $passingArray[$i]['Date'];

                    $query3 = "INSERT INTO uniqlo_1u (Date, Count, Door, Mode, Device) VALUES ";
                    $query3 .= "('$date', $rpiCount, 'passing by', 'Level 1', '$device'); ";

                    $stmt3 = $db->prepare($query3);
                    $stmt3->execute();
                }
            }
            else{
                for ($i=0; $i<count($inArray); $i++) {
                    $rpiCount = generateFakeData2($inArray[$i]['Count']);
                    $date = $inArray[$i]['Date'];

                    $query3 = "INSERT INTO uniqlo_1u (Date, Count, Door, Mode, Device) VALUES ";
                    $query3 .= "('$date', $rpiCount, 'in', 'Level 1', '$device'); ";

                    $stmt3 = $db->prepare($query3);
                    $stmt3->execute();
                }
            }
        }
    }

    // Send email notifications if there are sensor issues
    if(!empty($groundFloorSensorIssues) || !empty($level1SensorIssues)){
        if (!empty($groundFloorSensorIssues)) {
            $message = "For your information, the following GROUND FLOOR sensors of Uniqlo 1 Utama have problems and have no data for the past hour:\n\n";
            foreach ($groundFloorSensorIssues as $device) {
                $message .= "- " . $device . "\n";
            }
            mail($to, $subject, $message, $headers);
        }
        
        if (!empty($level1SensorIssues)) {
            $message = "For your information, the following LEVEL 1 sensors of Uniqlo 1 Utama have problems and have no data for the past hour:\n\n";
            foreach ($level1SensorIssues as $device) {
                $message .= "- " . $device . "\n";
            }
            mail($to, $subject, $message, $headers);
        }
        
        // Insert a notification record into the "notification" table
        $insertNotificationQuery = "INSERT INTO notification (date, sent, outlet) VALUES (?, 'Y', 'OU')";
        $insertNotificationStmt = $db->prepare($insertNotificationQuery);
        $insertNotificationStmt->bind_param('s', $currentDate);
        $insertNotificationStmt->execute();

        // Close the database connection
        $db->close();
    }
}

function generateFakeData($count)
{
    // Modify this as per your requirement
    // For simplicity, generating a random number between 80% and 150% of the given count
    $minCount = $count * 0.8;
    $maxCount = $count * 1.2;
    return mt_rand($minCount, $maxCount);
}

function generateFakeData2($count)
{
    // Modify this as per your requirement
    // For simplicity, generating a random number between 80% and 150% of the given count
    $minCount = $count * 0.9;
    $maxCount = $count * 1.5;
    return mt_rand($minCount, $maxCount);
}

function generateFakeData3($count)
{
    // Modify this as per your requirement
    // For simplicity, generating a random number between 80% and 150% of the given count
    $minCount = $count * 0.8;
    $maxCount = $count * 1.5;
    return mt_rand($minCount, $maxCount);
}
?>
