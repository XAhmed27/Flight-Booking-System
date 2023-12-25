<?php
require_once 'vendor/autoload.php';
require_once 'errorhandling.php';
require_once 'connection.php';

use \Firebase\JWT\JWT;

global $conn;

// Initialize variables
$userEmail = $userPassword = $message = '';
$passengerData = array();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted email and password
    $userEmail = $_POST['email'];
    $userPassword = $_POST['password'];

    try {
        // Check if a user with the provided email exists in the users table
        $getUserQuery = "SELECT u.*, p.*
            FROM users u
            LEFT JOIN passenger p ON u.userID = p.userID
            WHERE u.email = ?";
        $stmt = $conn->prepare($getUserQuery);
        $stmt->bind_param("s", $userEmail);
        $stmt->execute();
        $userResult = $stmt->get_result();



        if ($userResult->num_rows === 0) {
            $message = 'User not found';
        } else {
            // Fetch the user data
            $userData = $userResult->fetch_assoc();

            // Verify the password
            if (!empty($userData['password']) && password_verify($userPassword, $userData['password'])) {
                // Log both entered password and stored password
                error_log('Entered Password: ' . $userPassword);
                error_log('Stored Password: ' . $userData['password']);

                // Password is correct, proceed with displaying user information
                header("Location: MyFlight.php?passenger_id=" . urlencode($userData['passengerID']));
                exit();
            } else {
                $message = 'Invalid email or password';
            }
        }
    } catch (Exception $e) {
        // Handle any exceptions
        $message = 'An error occurred: ' . $e->getMessage();
    } finally {
        // Close the prepared statement
        $stmt->close();

        // Close the database connection
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Authentication</title>
    <style>
       body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('assets/air3.jpg');
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

        input,
        select {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 8px 0;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #146C94;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #146C94;
        }

        p {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>

<body>

<h2>Welcome to User Login!</h2>

<!-- Display error message if any -->
<p><?php echo $message; ?></p>

<!-- Add a form to enter email and password -->
<form action="" method="post">
    <label for="email">Enter Email:</label>
    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($userEmail); ?>" required>

    <label for="password">Enter Password:</label>
    <input type="password" id="password" name="password" required>

    <input type="submit" value="Submit">

</form>

</body>

</html>
