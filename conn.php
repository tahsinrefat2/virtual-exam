<?php
// Database configuration
$servername = "localhost"; // Change this if your database server is different
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$database = "virtualexam"; // Change this to your database name

try {
    // Establish database connection
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set utf8 charset
    $conn->exec("SET NAMES utf8mb4");
    
    // Display a success message if connected
    //echo "Connected successfully";
} catch(PDOException $e) {
    // Display error message if connection fails
    echo "Connection failed: " . $e->getMessage();
}
?>
