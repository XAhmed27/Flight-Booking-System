<?php



$host = "bzj5oyxzfkwxl2feoiqs-mysql.services.clever-cloud.com";
$username = "ulq7cveqek2mdfvr";
$password = "P9J73zhXFvWX2O57Wy1s";
$database = "bzj5oyxzfkwxl2feoiqs";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



?>
