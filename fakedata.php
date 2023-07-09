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
$query = "SELECT * FROM uniqlo_1u WHERE Door = 'passing by' AND Mode = 'Level 1' AND Device = 'RPi-5' AND Date >= '2023-07-06 14:00:00' AND Date <= '2023-07-09 10:59:59'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$rpi5Data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If RPi-5 data exists for the past hour
if ($rpi5Data) {
    $i = 1;

    foreach ($rpi5Data as $row) {
        // Extract the required information from RPi-5 data
        echo $i;
        $rpi5Count = $row['Count'];
        $rpi5Date = $row['Date'];

        // Generate fake data for RPi-11, RPi-12, RPi-13, and RPi-14
        $rpi11Count = generateFakeData($rpi5Count); // Modify this as per your requirement
        $rpi12Count = generateFakeData($rpi5Count); // Modify this as per your requirement
        $rpi13Count = generateFakeData($rpi5Count); // Modify this as per your requirement
        $rpi14Count = generateFakeData($rpi5Count); // Modify this as per your requirement

        // Insert the fake data for RPi-11, RPi-12, RPi-13, and RPi-14 into the database with the same date and time as RPi-5 data
        $query = "INSERT INTO uniqlo_1u (Date, Count, Door, Mode, Device) VALUES ";
        $query .= "('$rpi5Date', $rpi11Count, 'passing by', 'Level 1', 'RPi-11'), ";
        $query .= "('$rpi5Date', $rpi12Count, 'passing by', 'Ground', 'RPi-12'), ";
        $query .= "('$rpi5Date', $rpi13Count, 'passing by', 'Ground', 'RPi-13'), ";
        $query .= "('$rpi5Date', $rpi14Count, 'passing by', 'Ground', 'RPi-14')";

        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $i++;
    }
}

// Generate fake data based on the given count
function generateFakeData($count)
{
    // Modify this as per your requirement
    // For simplicity, generating a random number between 80% and 150% of the given count
    $minCount = $count * 0.8;
    $maxCount = $count * 1.5;
    return mt_rand($minCount, $maxCount);
}
?>
