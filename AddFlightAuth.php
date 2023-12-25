<?php
require_once 'vendor/autoload.php';
require_once 'errorhandling.php';
require_once 'connection.php';

use \Firebase\JWT\JWT;

global $conn;

// Initialize variables
$email = $password = $message = '';
$passengerData = array();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted email and password
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Check if a user with the provided email exists in the company table
        $getPassengerQuery = "SELECT u.name, u.email, u.tel, u.accountBalance, u.password, c.*
            FROM users u
            JOIN company c ON u.userID = c.userID
            WHERE u.email = ?";

        $stmt = $conn->prepare($getPassengerQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $passengerResult = $stmt->get_result();

        if ($passengerResult->num_rows === 0) {
            $message = 'comapny not found';
        } else {
            // Fetch the user and company data
            $passengerData = $passengerResult->fetch_assoc();

            // Verify the password
            if (!empty($passengerData['password']) && password_verify($password, $passengerData['password'])) {
                // Log both entered password and stored password
                error_log('Entered Password: ' . $password);
                error_log('Stored Password: ' . $passengerData['password']);

                // Password is correct, proceed with displaying company information
                header("Location:AddFlight.php?company_id=" . urlencode($passengerData['companyID']));
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
            background-image: url('assets/air3.jpg');
            background-size: cover;
        }

        form {
            background-color: transparent;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 1);
            width: 400px;
            margin-bottom: 20px;
            text-align: center;
            opacity: 1;
            font-size: 25px;



        }

        h1 {
            display: block;
            text-align: start;
            margin-bottom: 20px;
            opacity: 1;
            font-size: 30px;
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

<h1 style="display: block;" >Welcome to Auth !</h1>


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