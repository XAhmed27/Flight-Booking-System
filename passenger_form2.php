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
        $passportImg = $_POST['passportImg'];
        $photo = $_POST['photo'];

        // Insert passenger data
        $insertPassengerQuery = "INSERT INTO passenger (userID, PassportImg, photo) VALUES (?, ?, ?)";
        $stmtPassenger = $conn->prepare($insertPassengerQuery);
        $stmtPassenger->bind_param("iss", $userId, $passportImg, $photo);
        $stmtPassenger->execute();

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
        echo json_encode(['message' => 'Passenger information saved successfully!', 'token' => $generatedToken]);
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
    <title>Passenger Form</title>
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

<form id="passengerForm" action="" method="post">
    Passport Img: <input type="text" name="passportImg" required><br>
    Photo: <input type="text" name="photo" required><br>
    <input type="submit" value="Submit Passenger Info">
</form>

</body>
</html>
