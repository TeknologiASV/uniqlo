<?php
// Connect to the database
$host = 'tasv-database.czjdmxerbddi.ap-southeast-1.rds.amazonaws.com';
$dbName = 'uniqlo';
$username = 'tasv';
$password = 'PublikaTASV';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get the RPi-5 data for the past hour
$query = "SELECT * FROM uniqlo_DA WHERE Door = 'passing by' AND Mode = 'Ground' AND Device = 'L1' AND Date >= '2023-07-01 00:00:00' AND Date <= '2023-07-31 23:59:59'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$rpi5Data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If RPi-5 data exists for the past hour
if ($rpi5Data) {
    foreach ($rpi5Data as $row) {
        // Extract the required information from RPi-5 data
        $rpi5Count = $row['Count'];
        $rpi5Date = $row['Date'];

        // Generate fake data for RPi-11, RPi-12, RPi-13, and RPi-14
        $rpi14Count = generateFakeData($rpi5Count); // Modify this as per your requirement

        // Insert the fake data for RPi-11, RPi-12, RPi-13, and RPi-14 into the database with the same date and time as RPi-5 data
        $query = "INSERT INTO uniqlo_DA (Date, Count, Door, Mode, Device) VALUES ";
        $query .= "('$rpi5Date', $rpi14Count, 'passing by', 'Ground', 'R1')";

        $stmt = $pdo->prepare($query);
        $stmt->execute();
    }
}

// Generate fake data based on the given count
function generateFakeData($count)
{
    // Modify this as per your requirement
    // For simplicity, generating a random number between 80% and 150% of the given count
    $minCount = $count * 0.3;
    $maxCount = $count * 0.6;
    return mt_rand($minCount, $maxCount);
}
?>
