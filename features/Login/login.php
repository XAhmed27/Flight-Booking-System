<?php
require_once 'vendor/autoload.php';
require_once 'errorhandling.php';
require_once 'connection.php';
use \Firebase\JWT\JWT;

global $conn;

// Initialize the response variable
$response = array('success' => false, 'message' => 'Unknown error', 'token' => null);

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Check if a user with the provided email exists
        $getUserQuery = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($getUserQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $userResult = $stmt->get_result();

        if ($userResult->num_rows === 0) {
            $response['success'] = false;
            $response['message'] = 'User not found';
        } else {
            // Fetch the user data
            $user = $userResult->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Password is correct, proceed with authentication

                // Generate JWT token with the user's email
                $jwtSecretKey = 'your_secret_key'; // Replace with your actual secret key
                $tokenPayload = array("email" => $email);
                $token = JWT::encode($tokenPayload, $jwtSecretKey);

                // You can store the token in a secure manner or send it to the client as needed

                $response['success'] = true;
                $response['message'] = 'Sign-in successful';
                $response['token'] = $token;

                // Send the Authorization header
                header("Authorization: Bearer " . $token);

            } else {
                $response['success'] = false;
                $response['message'] = 'Invalid password';
            }
        }

    } catch (Exception $exception) {
        // Include the specific exception message in the response
        $response['success'] = false;
        $response['message'] = $exception->getMessage();
    }
}

// Display the result on the same page
?>

<!DOCTYPE html>
<!--<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Sign In</title>
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
        }

        input {
            width: 100%;
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

        #result {
            margin-top: 20px;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
        }

        #result p {
            margin: 0;
            padding: 8px;
            font-weight: bold;
        }

        #result p.error {
            color: red;
        }

        #result p.success {
            color: green;
        }
    </style>
</head>
<body>

<form id="signInForm" action="" method="post">
    Email: <input type="text" name="email" required><br>
    Password: <input type="password" name="password" required><br>

    <input type="submit" value="Sign In">
</form>

<div id="result">
    <?php /*if ($response['success']): */?>
        <p class="success"><?php /*echo $response['message']; */?></p>
        <?php /*if ($response['token'] !== null): */?>
            <p>Token: <?php /*echo $response['token']; */?></p>
        <?php /*endif; */?>
    <?php /*else: */?>
        <p class="error"><?php /*echo $response['message']; */?></p>
    <?php /*endif; */?>
</div>

</body>
</html>






-->