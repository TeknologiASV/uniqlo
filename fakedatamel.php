<?php
// Connect to the database
$host = 'tasv-database.czjdmxerbddi.ap-southeast-1.rds.amazonaws.com';
$dbName = 'Melaka';
$username = 'tasv';
$password = 'PublikaTASV';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get the RPi-5 data for the past hour
$query = "INSERT INTO `Melaka_traffic`(`Date`, `Count`, `Condition`, `Place`, `Device`) VALUES ";
/*$query .= "('2023-09-06 02:00:00', 0, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-06 03:00:00', 0, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-06 04:00:00', 0, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-06 05:00:00', 0, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-06 06:00:00', 0, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-06 07:00:00', 0, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-06 08:00:00', 5, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-06 09:00:00', 23, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-06 10:00:00', 111, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-06 11:00:00', 133, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-06 12:00:00', 160, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-06 13:00:00', 203, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-06 14:00:00', 200, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-06 15:00:00', 207, 'PPL-in', 'redhouse', 'e2'), ";*/
$query .= "('2023-09-06 16:00:00', 189, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-06 17:00:00', 174, 'PPL-in', 'redhouse', 'e2'); ";
/*$query .= "('2023-09-06 18:00:00', 122, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-06 19:00:00', 100, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-06 20:00:00', 110, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-06 21:00:00', 93, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-06 22:00:00', 69, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-06 23:00:00', 44, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-07 00:00:00', 12, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-07 01:00:00', 3, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-07 02:00:00', 0, 'PPL-in', 'redhouse', 'e2'); ";*/

//echo $query;
$stmt = $pdo->prepare($query);
$stmt->execute();

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
