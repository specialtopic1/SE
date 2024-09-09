<?php
// database.php
$host = 'localhost';
$dbname = 'se';
$username = 'root'; // Adjust to your MySQL username
$password = '';     // Adjust to your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
