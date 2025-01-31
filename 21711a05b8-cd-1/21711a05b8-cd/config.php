<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "21711a0579-db";
$port = 3307;

try {
    // Create a new MySQLi object to establish a connection
    $conn = new mysqli($servername, $db_username, $db_password, $dbname, $port);

    // Check if the connection was successful
    if ($conn->connect_error) {
        // If connection fails, throw an Exception with an error message
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Connection successful, you can proceed with database operations here
} catch (Exception $e) {
    // If an Exception is caught (connection failure), terminate execution and display an error message
    die("Connection failed: " . $e->getMessage());
}
?>
