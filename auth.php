<?php
session_start();
require_once 'vendor/autoload.php';
require_once 'errorhandling.php';
require_once 'connection.php';
use \Firebase\JWT\JWT;

global $conn;

// ghzvariables
$email = $password = $message = '';

// Check if submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['email'], $_POST['password'])) {
        // Retrieve the submitted email and password
        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
            // Validation
            $getUserQuery = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($getUserQuery);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $userResult = $stmt->get_result();

            if ($userResult->num_rows === 0) {
                $message = 'Invalid credentials';
            } else {
                // Fetch 
                $userData = $userResult->fetch_assoc();

                // Verify the password
                if (password_verify($password, $userData['password'])) {
                    // Password is correct
                    // Destroy the existing session
                    session_unset();
                    session_destroy();
                    session_start();


                    $_SESSION['user'] = $userData;

                    if (isset($_POST['name_button'])) {
                        header("Location: Name.php");
                        exit();
                    } elseif (isset($_POST['email_button'])) {
                        header("Location: Email.php");
                        exit();
                    } elseif (isset($_POST['tel_button'])) {
                        header("Location: Tel.php");
                        exit();

                    }
                } else {
                    $message = 'Invalid credentials';
                }
            }
        } catch (Exception $e) {
            // Handle any exceptions
            $message = 'An error occurred: ' . $e->getMessage();
        } finally {
            // Close the database connection
            $conn->close();
        }
    } else {

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('assets/flyy.jpg');
            background-size: cover;
        }

        form {
            background: rgba(255, 255, 255, 0.5);
            font-size: 20px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin-bottom: 20px;
            text-align: center;
        }

        input {

            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            background-color: #146C94;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            width: 200px;
            font-size: 16px;
        }

        button:hover {
            background-color: #146C94;
        }

        p {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>



<!-- Display error message if any -->
<p><?php echo $message; ?></p>


<form action="" method="post">
    <input type="email" name="email" placeholder="Enter your email" required>
    <input type="password" name="password" placeholder="Enter your password" required>
    <button type="submit" name="name_button">Login and go to Name</button>
    <button type="submit" name="email_button">Login and go to Email</button>
    <button type="submit" name="tel_button">Login and go to Tel</button>

</form>

</body>
</html>