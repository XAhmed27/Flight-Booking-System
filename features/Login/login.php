<?php
require_once '../../vendor/autoload.php';
require_once '../../errorhandling.php';
require_once '../../connection.php';


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
                if ($user['role'] == 'company') {
                    $getRoleIdQuery = "SELECT companyID FROM company WHERE userID=?";
                } else {
                    $getRoleIdQuery = "SELECT passengerID FROM passenger WHERE userID=?";
                }
                //*Set cookies
                //*Get Role To set in cookies
                $stmtRole = $conn->prepare($getRoleIdQuery);
                if (!$stmtRole) {
                    die("Error in preparing the statement: " . $conn->error);
                }
                $userId= $user['userID'];
                $stmtRole->bind_param("i", $userId);

                if (!$stmtRole->execute()) {
                    die("Error in executing the statement: " . $stmtRole->error);
                }

                $stmtRole->bind_result($RoleId);
                $stmtRole->fetch();
                $stmtRole->close();
                // Using echo

                // Using print
                // print $RoleId;
                setcookie('id',$RoleId , time() + 3600 * 24, '/');
                echo " <h1>enter<h1>";
                echo  $_COOKIE['id'];


                if ($user['role'] == 'company') {
                    header("Location: ../Home-Company/CompanyHome.php");
                    exit();
                } else {
                    header("Location: ../../../PassengerHome.php");
                    exit();
                }
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
    <?php /*if ($response['success']): */ ?>
        <p class="success"><?php /*echo $response['message']; */ ?></p>
        <?php /*if ($response['token'] !== null): */ ?>
            <p>Token: <?php /*echo $response['token']; */ ?></p>
        <?php /*endif; */ ?>
    <?php /*else: */ ?>
        <p class="error"><?php /*echo $response['message']; */ ?></p>
    <?php /*endif; */ ?>
</div>

</body>
</html>






-->