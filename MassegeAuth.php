<?php
require_once 'vendor/autoload.php';
require_once 'errorhandling.php';
require_once 'connection.php';

use \Firebase\JWT\JWT;

global $conn;

// Initializinf variables
$email = $password = $message = '';
$passengerData = array();

// check if form submited
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted email and password
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        
        $getPassengerQuery = "SELECT u.name, u.email, u.tel, u.accountBalance, u.password, p.*
            FROM users u
            JOIN passenger p ON u.userID = p.userID
            WHERE u.email = ?";
    
        $stmt = $conn->prepare($getPassengerQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $passengerResult = $stmt->get_result();

        if ($passengerResult->num_rows === 0) {
            $message = 'passenger not found';
        } else {
            // Fetch the user and company data
            $passengerData = $passengerResult->fetch_assoc();

            // Verify the password
            if (!empty($passengerData['password']) && password_verify($password, $passengerData['password'])) {
                // Log both entered password and stored password
                error_log('Entered Password: ' . $password);
                error_log('Stored Password: ' . $passengerData['password']);
                header("Location:Massege.php?passengerID=" . urlencode($passengerData['passengerID']));
                exit();
            } else {
                $message = 'Invalid email or password';
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
    <title>Company Authentication</title>
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

        input,
        select {
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

    <h2>Welcome to Company Login!</h2>

    <!-- Display error message if any -->
    <p><?php echo $message; ?></p>

    <form action="" method="post">
        <label for="email">Enter Email:</label>
        <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

        <label for="password">Enter Password:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Submit">
    </form>

</body>

</html>
