<?php
require_once 'vendor/autoload.php';
require_once 'errorhandling.php';
require_once 'connection.php';
use \Firebase\JWT\JWT;

global $conn;

// Initialize variables
$email = $newEmail = $newTel = $password = $message = '';
$userData = array();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted email and password
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Check if a user with the provided email exists
        $getUserQuery = "SELECT u.*, p.PassportImg, p.photo FROM users u
                         JOIN passenger p ON u.userID = p.userID
                         WHERE u.email = ?";
        $stmt = $conn->prepare($getUserQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $userResult = $stmt->get_result();

        if ($userResult->num_rows === 0) {
            $message = 'User not found';
        } else {
            // Fetch the user and passenger data
            $userData = $userResult->fetch_assoc();

            // Verify the password
            if (password_verify($password, $userData['password'])) {
                // Password is correct, proceed with displaying user information

                // Redirect to another page after successful authentication
                header("Location: MyProfile.php?email=" . urlencode($userData['email']) . "&name=" . urlencode($userData['name']) . "&tel=" . urlencode($userData['tel']) . "&balance=" . urlencode($userData['accountBalance']) . "&PassportImg=" . urlencode($userData['PassportImg']) . "&photo=" . urlencode($userData['photo']));
                exit(); // Make sure to exit after sending the header
            } else {
                $message = 'Invalid email or password'; // Improve the error message
            }
        }
    } catch (Exception $e) {
        // Handle any exceptions
        $message = 'An error occurred: ' . $e->getMessage();
    } finally {
        // Close the database connection
        $conn = null;
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
            text-align: center;
        }

        input, select {
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

        p {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<h2>Welcome!</h2>

<!-- Display error message if any -->
<p><?php echo $message; ?></p>

<!-- Add a form to enter email and password -->
<form action="" method="post">
    <label for="email">Enter Email:</label>
    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

    <label for="password">Enter Password:</label>
    <input type="password" id="password" name="password" required>

    <input type="submit" value="Submit">
</form>

</body>
</html>
