<?php
require_once '../../vendor/autoload.php';
require_once '../../errorhandling.php';
require_once '../../connection.php';

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

        // Generate and return token
        $secretKey = 'your_secret_key'; // Replace with a secure secret key
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600; // Token valid for 1 hour
        $tokenPayload = [
            'user_id' => $userId,
            'iat' => $issuedAt,
            'exp' => $expirationTime,
        ];

        $generatedToken = JWT::encode($tokenPayload, $secretKey, 'HS256');

        // Return the token as part of the response
        echo json_encode(['message' => 'Company information saved successfully!', 'token' => $generatedToken]);
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
            width: 100vw;
            background-image: url('../../assets/flyy.jpg');
            background-size: cover;
        }

        form {
            /*
            background-color: #fff;
            */
            background-color: rgba(255, 255, 255, 0.3);
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
            background-color: transparent; /* Transparent background for input */
            border: 1px solid #ccc; /* Rectangle border with a light gray color */
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #146C94;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #146C94;
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
