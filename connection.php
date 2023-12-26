<?php

$host = "bzj5oyxzfkwxl2feoiqs-mysql.services.clever-cloud.com";
$username = "ulq7cveqek2mdfvr";
$password = "P9J73zhXFvWX2O57Wy1s";
$database = "bzj5oyxzfkwxl2feoiqs";

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
