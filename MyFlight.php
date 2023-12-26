<?php
require_once 'vendor/autoload.php';
require_once 'errorhandling.php';
require_once 'connection.php';

use \Firebase\JWT\JWT;

global $conn;

$passengerID = $_COOKIE['id'];
$flightsData = array();
$message = '';

if (!empty($passengerID)) {
    try {
        $getFlightsQuery = "SELECT f.*, pf.passengerStatus
            FROM flight f
            JOIN passenger_flight pf ON f.flightID = pf.flightID
            WHERE pf.passengerID = ?";
        $stmt = $conn->prepare($getFlightsQuery);
        $stmt->bind_param("i", $passengerID);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $flightID = $row['flightID'];
            $flightsData[$flightID]['flightID'] = $row['flightID'];
            $flightsData[$flightID]['name'] = $row['name'];
            $flightsData[$flightID]['flight_from'] = $row['flight_from'];
            $flightsData[$flightID]['flight_to'] = $row['flight_to'];
            $flightsData[$flightID]['startTime'] = $row['endTime'];
            $flightsData[$flightID]['passengerStatus'] = $row['passengerStatus'];
        }

        $stmt->close();

    } catch (Exception $e) {
        $message = 'An error occurred: ' . $e->getMessage();
    } finally {
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
            background-image: url('assets/air3.jpg');
            background-size: cover;
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

        h2{
            margin-right: 10px;
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

<p class="message"><?php echo $message; ?></p>

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
                    <strong>Status:</strong> <?php echo $flight['passengerStatus']; ?><br>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

</body>

</html>
