<?php
require_once 'vendor/autoload.php';
require_once 'errorhandling.php';
require_once 'connection.php';

use \Firebase\JWT\JWT;

global $conn;
if (!isset($_GET['passenger_id'])) {
    // check auth
    header("Location: passengerFlightAuth.php");
    exit();
}
// Initialize variables
$passengerID = isset($_GET['passenger_id']) ? $_GET['passenger_id'] : '';
$flightFrom = isset($_POST['flight_from']) ? $_POST['flight_from'] : '';
$flightTo = isset($_POST['flight_to']) ? $_POST['flight_to'] : '';
$passengerStatus = isset($_POST['status']) ? $_POST['status'] : 'pending';
$message = '';

// Check if the passenger ID is provided in the URL
if (!empty($passengerID)) {
    try {
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($flightFrom) && !empty($flightTo)) {
            // Search for the flight flight table
            $searchFlightQuery = "SELECT flightID FROM flight WHERE flight_to = ? AND flight_from = ?";
            $stmt = $conn->prepare($searchFlightQuery);
            $stmt->bind_param("ss", $flightTo, $flightFrom);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {

                $row = $result->fetch_assoc();
                $flightID = $row['flightID'];

                // Store the information in the passenger_flight table
                $insertPassengerFlightQuery = "INSERT INTO passenger_flight (passengerID, flightID, passengerStatus) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($insertPassengerFlightQuery);
                $stmt->bind_param("iss", $passengerID, $flightID, $passengerStatus);
                $stmt->execute();

                $message = 'Flight registration successful!';
            } else {
                $message = 'No matching flight found.';
            }

            // Close the statement
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Registration</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('assets/air3.jpg'); /* Replace with your background image path */
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: rgba(0, 0, 0, 0.3);
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
            border: none; /* Remove default border */
            border-radius: 5px; /* Add border-radius for rounded corners */
            background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent white background */
        }

        input[type="submit"] {
            background-color: #146C94; /* Blue color */
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #12516E; /* Darker blue on hover */
        }

        p {
            color: red;
            margin-top: 10px;
        }

        .message {
            color: green;
            margin-top: 10px;
        }

        h2 {
            display: block;
            text-align: center;
        }
    </style>
</head>

<body>

<h2>Flight Registration</h2>

<p><?php echo $message; ?></p>

<form action="" method="post">
    <input type="hidden" name="passenger_id" value="<?php echo htmlspecialchars($passengerID); ?>">

    <label for="flight_from">Flight From:</label>
    <input type="text" id="flight_from" name="flight_from" required>

    <label for="flight_to">Flight To:</label>
    <input type="text" id="flight_to" name="flight_to" required>

    <label for="status">Status:</label>
    <select id="status" name="status">
        <option value="pending">Pending</option>
        <option value="registered">Registered</option>
    </select>

    <input type="submit" value="Register for Flight">
</form>

</body>

</html>
