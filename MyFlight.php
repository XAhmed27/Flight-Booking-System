<?php
require_once 'vendor/autoload.php';
require_once 'errorhandling.php';
require_once 'connection.php';

use \Firebase\JWT\JWT;

global $conn;
//var_dump($_GET);

// if (!isset($_GET['passenger_id'])) {
//     // Redirect to MyFlightAuth.php if passengerid or other parameters are missing
//     header("Location: MyFlightAuth.php");
//     exit();
// }


// $passengerID = isset($_GET['passenger_id']) ? $_GET['passenger_id'] : '';
$passengerID = $_COOKIE['id'];
$flightsData = array();
$message = '';

// Check if the passenger ID is provided in the URL
if (!empty($passengerID)) {
    try {
        // Retrieve flights for the given passenger ID
        $getFlightsQuery = "SELECT f.*
            FROM flight f
            JOIN passenger_flight pf ON f.flightID = pf.flightID
            WHERE pf.passengerID = ?";
        $stmt = $conn->prepare($getFlightsQuery);
        $stmt->bind_param("i", $passengerID);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch the flight data
        while ($row = $result->fetch_assoc()) {
            $flightID = $row['flightID'];
            $flightsData[$flightID]['flightID'] = $row['flightID'];
            $flightsData[$flightID]['name'] = $row['name'];
            $flightsData[$flightID]['flight_from'] = $row['flight_from'];
            $flightsData[$flightID]['flight_to'] = $row['flight_to'];
            $flightsData[$flightID]['startTime'] = $row['endTime'];
            // Add more flight-related fields as needed
        }

        // Close the statement
        $stmt->close();

    } catch (Exception $e) {
        // Handle any exceptions
        $message = 'An error occurred: ' . $e->getMessage();
    } finally {
        // Close the database connection
        $conn->close();
    }
} else {
    $message = 'Passenger ID is missing.';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Flights</title>
    <style>
    body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: rgba(255, 255, 255, 0.5);
            font-size: 20px;
        }

        div {
            background: rgba(255, 255, 255, 0.5);
            font-size: 20px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: left;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        p {
            color: red;
            margin-top: 10px;
        }

        .message {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>

<body>

<h2>My Flights</h2>

<!-- Display error or success message -->
<p class="message"><?php echo $message; ?></p>

<!-- Display flights information -->
<?php if (!empty($flightsData)): ?>
    <div>
        <ul>
            <?php foreach ($flightsData as $flight): ?>
                <li>
                    <strong>Flight ID:</strong> <?php echo $flight['flightID']; ?><br>
                    <strong>Name:</strong> <?php echo $flight['name']; ?><br>
                    <strong>Flight From:</strong> <?php echo $flight['flight_from']; ?><br>
                    <strong>Flight To:</strong> <?php echo $flight['flight_to']; ?><br>
                    <strong>Start Time:</strong> <?php echo $flight['startTime']; ?><br>
                    <!-- Add more flight-related fields as needed -->
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

</body>

</html>
