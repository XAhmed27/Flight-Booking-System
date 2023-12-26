<?php
require_once 'vendor/autoload.php';
require_once 'errorhandling.php';
require_once 'connection.php';

use \Firebase\JWT\JWT;

global $conn;
$passengerID = $_COOKIE['id'];
// if (!isset($_GET['passenger_id'])) {
//     // check auth
//     header("Location: passengerFlightAuth.php");
//     exit();
// }

// Initialize variables
// $passengerID = isset($_GET['passenger_id']) ? $_GET['passenger_id'] : '';
$flightName = isset($_POST['flight_name']) ? $_POST['flight_name'] : '';
$passengerStatus = 'cancel';
$message = '';

// Check if the passenger ID is provided in the URL
if (!empty($passengerID)) {
    try {
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($flightName)) {
            // Search for the flight in the flight table
            $searchFlightQuery = "SELECT flightID, fees FROM flight WHERE name = ?";
            $stmt = $conn->prepare($searchFlightQuery);
            $stmt->bind_param("s", $flightName);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $flightID = $row['flightID'];
                $fees = $row['fees'];

                // Update passenger flight status to 'cancel'
                $updateStatusQuery = "UPDATE passenger_flight SET passengerStatus = ? WHERE passengerID = ? AND flightID = ?";
                $stmt = $conn->prepare($updateStatusQuery);
                $stmt->bind_param("ssi", $passengerStatus, $passengerID, $flightID);
                $stmt->execute();

                // Get user account balance
                $getBalanceQuery = "SELECT u.accountBalance FROM users u INNER JOIN passenger p ON u.userID = p.userID WHERE p.passengerID = ?";
                $stmt = $conn->prepare($getBalanceQuery);
                $stmt->bind_param("i", $passengerID);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $accountBalance = $row['accountBalance'];

                    // Calculate new balance
                    $newBalance = $accountBalance + $fees;

                    // Update user balance
                    $updateBalanceQuery = "UPDATE users u INNER JOIN passenger p ON u.userID = p.userID SET u.accountBalance = ? WHERE p.passengerID = ?";
                    $stmt = $conn->prepare($updateBalanceQuery);
                    $stmt->bind_param("di", $newBalance, $passengerID);
                    $stmt->execute();

                    $message = 'Successfully cancelled! Your money has been returned. New balance: ' . $newBalance;
                }
            } else {
                $message = 'You are not registered for the provided flight.';
            }

            // Close the statements
            $stmt->close();
        }
    } catch (Exception $e) {
        // Handle any exceptions
        $message = 'An error occurred: ' . $e->getMessage();
    } finally {
        // Close the database connection
        $conn->close();
    }
} else {
    $message = 'Passenger ID is missing in the URL.';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

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
            background-image: url('assets/air3.jpg');
            background-size: cover;
        }

        form {
            align-items: center;
            font-size: 20px;
            background: rgba(255, 255, 255, 0.5);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin-bottom: 300px;
        }

        input {
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

    </style>

</head>

<body>

    <h2>Cancel Flight</h2>

    <p><?php echo $message; ?></p>

    <form action="" method="post">
        <input type="hidden" name="passenger_id" value="<?php echo htmlspecialchars($passengerID); ?>">

        <label for="flight_name">Flight Name:</label>
        <input type="text" id="flight_name" name="flight_name" required>

        <input type="submit" value="Cancel Flight">
    </form>

</body>

</html>
