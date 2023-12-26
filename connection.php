<?php



$host = "buzwklw9ofzdxgzdmuyu-mysql.services.clever-cloud.com";
$username = "ultpruh6h5ibf7p3";
$password = "LZZ0R8tKWqmTMN9jZ5XH";
$database = "buzwklw9ofzdxgzdmuyu";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



?>
