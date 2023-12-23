<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "hossam";

// $host = "b9mym6lmp5wv3nzpjfmb-mysql.services.clever-cloud.com";
// $username = "uk7hkgqjvow9yhzn";
// $password = "JOv8VQGhIMeLDK6zdolX";
// $database = "b9mym6lmp5wv3nzpjfmb";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



?>
