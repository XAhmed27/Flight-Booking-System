<?php



$host = "bpf2vgyopgi1oqletjnm-mysql.services.clever-cloud.com";
$username = "ubn8xnohq45tmupo";
$password = "8pob8vVPkA5lRN6TVTS2";
$database = "bpf2vgyopgi1oqletjnm";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



?>
