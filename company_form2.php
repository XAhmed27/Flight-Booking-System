<?php
require_once 'vendor/autoload.php';
require_once 'errorhandling.php';
require_once 'connection.php';

use \Firebase\JWT\JWT;

$userId = $_GET['userId'] ?? 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        global $conn; // Assuming $conn is defined somewhere in your code

        // Retrieve data from the form
        $bio = $_POST['bio'];
        $address = $_POST['address'];
        $location = $_POST['location'];
        $username = $_POST['username'];
        $logoImg = $_POST['logoImg'];

        // Insert company data

        $insertCompanyQuery = "INSERT INTO company (userID, bio, username, address, location, logoImg) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtCompany = $conn->prepare($insertCompanyQuery);
        $stmtCompany->bind_param("isssss", $userId, $bio, $username, $address, $location, $logoImg);
        $stmtCompany->execute();

        //*Get CompanyID To set in cookies
        $getCompanyIdQuery = "SELECT companyID FROM company WHERE userID=?";
        $stmtCompany = $conn->prepare($getCompanyIdQuery);
        if (!$stmtCompany) {
            die("Error in preparing the statement: " . $conn->error);
        }

        $stmtCompany->bind_param("i", $userId);

        if (!$stmtCompany->execute()) {
            die("Error in executing the statement: " . $stmtCompany->error);
        }

        $stmtCompany->bind_result($companyId);
        $stmtCompany->fetch();
        $stmtCompany->close();

        //*Set cookies
        setcookie('id', $companyId, time() + 3600 *24,'/');
        $id = $_COOKIE['id'];

        header("Location: CompanyHome.php");
        echo json_encode(['Hola' => 'Metwally m3lm!']);
        exit();
    } catch (Exception $exception) {
        // Call handleGlobalError in case of an exception
        handleGlobalError($exception);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin-bottom: 20px;
        }

        input {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 8px 0;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>

</head>

<body>

    <form id="companyForm" action="" method="post">
        Bio: <input type="text" name="bio" required><br>
        Address: <input type="text" name="address" required><br>
        Location: <input type="text" name="location" required><br>
        Username: <input type="text" name="username" required><br>
        Logo Img: <input type="text" name="logoImg" required><br>
        <input type="submit" value="Submit Company Info">
    </form>

</body>

</html>