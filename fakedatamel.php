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
/*$query .= "('2023-09-13 03:00:00', 0, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-13 04:00:00', 0, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-13 05:00:00', 0, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-13 06:00:00', 0, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-13 07:00:00', 5, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-13 08:00:00', 63, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-13 09:00:00', 134, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-13 10:00:00', 201, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-13 11:00:00', 233, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-13 12:00:00', 191, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-13 13:00:00', 223, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-13 14:00:00', 251, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-13 15:00:00', 301, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-13 16:00:00', 268, 'PPL-in', 'redhouse', 'e2'), ";*/
$query .= "('2023-09-13 17:00:00', 234, 'PPL-in', 'redhouse', 'e2'); ";
/*$query .= "('2023-09-13 18:00:00', 222, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-13 19:00:00', 250, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-13 20:00:00', 291, 'PPL-in', 'redhouse', 'e2'); ";
$query .= "('2023-09-13 21:00:00', 203, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-13 22:00:00', 159, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-13 23:00:00', 93, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-13 00:00:00', 57, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-13 01:00:00', 1, 'PPL-in', 'redhouse', 'e2'), ";
$query .= "('2023-09-13 02:00:00', 0, 'PPL-in', 'redhouse', 'e2'); ";*/

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
