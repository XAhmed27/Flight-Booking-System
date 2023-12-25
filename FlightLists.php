<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flights List</title>
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
            background-image: url('assets/flyy.jpg');
            background-size: cover;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        .button-link {
            background-color: #146C94;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px; /* Add margin-top for spacing */
            text-decoration: none; /* Remove underline from the link */
        }



        .widget, h1 {
            text-align: center;
            display: block;
            align-items: center;
            margin-right: 10px;
        }

        /* Hover effect for the button */
        .button-link:hover {
            background-color: #146C94;
        }
    </style>
</head>
<body>

<?php
require_once 'vendor/autoload.php';
require_once 'errorhandling.php';
require_once 'connection.php';

use \Firebase\JWT\JWT;

global $conn;

//  flights data from the database
function getFlightsData()
{
    global $conn;

    try {
        $query = "SELECT flightID, name, flight_from, flight_to, fees, startTime, endTime
                  FROM flight";

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $stmt->bind_result($flightID, $name, $flightFrom, $flightTo, $fees, $startTime, $endTime);

        // Fetch all rows
        $flights = [];
        while ($stmt->fetch()) {
            $flights[] = [
                'flightID' => $flightID,
                'name' => $name,
                'flight_from' => $flightFrom,
                'flight_to' => $flightTo,
                'fees' => $fees,
                'startTime' => $startTime,
                'endTime' => $endTime,
            ];
        }

        return $flights;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

// Call the function to get data
$flights = getFlightsData();
?>


<div class="widget">
    <h1>Flights</h1>
</div>


<div class="widget2">
    <table border="1">
        <tr>
            <th>Flight Name</th>
            <th>From</th>
            <th>To</th>
            <th>Fees</th>
            <th>Start Time</th>
            <th>End Time</th>
        </tr>
        <?php foreach ($flights ?? [] as $flightName => $flight) : ?>
            <tr>
                <td><?php echo $flight['name']; ?></td>
                <td><?php echo $flight['flight_from']; ?></td>
                <td><?php echo $flight['flight_to']; ?></td>
                <td><?php echo $flight['fees']; ?></td>
                <td><?php echo $flight['startTime']; ?></td>
                <td><?php echo $flight['endTime']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<!--link to go back -->
<p></p>

<a href="AddFlight.php" class="button-link">Add a New Flight</a>

</body>
</html>