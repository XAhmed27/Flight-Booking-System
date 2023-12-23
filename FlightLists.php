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
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
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
            background-color: #4caf50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px; /* Add margin-top for spacing */
            text-decoration: none; /* Remove underline from the link */
        }

        /* Hover effect for the button */
        .button-link:hover {
            background-color: #45a049;
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

// Function to get flights data from the database
function getFlightsData() {
    global $conn;

    try {
        $query = "SELECT flightID, name, itinerary, fees, startTime, endTime
                  FROM flight"; 
    
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $stmt->bind_result($flightID, $name, $itinerary, $fees, $startTime, $endTime);

        // Fetch all rows
        $flights = [];
        while ($stmt->fetch()) {
            $flights[] = [
                'flightID' => $flightID,
                'name' => $name,
                'itinerary' => $itinerary,
                'fees' => $fees,
                'startTime' => $startTime,
                'endTime' => $endTime,
            ];
        }

        return $flights;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];  // Return an empty array on error
    }
}

// Call the function to get data
$flights = getFlightsData();
?>

<h3>Flights</h3>
<table border="1">
    <tr>
        <th>Flight Name</th>
        <th>Itinerary</th>
        <th>Fees</th>
        <th>Start Time</th>
        <th>End Time</th>
    </tr>
    <?php foreach ($flights ?? [] as $flightName => $flight) : ?>
        <tr>
            <td><?php echo $flight['name']; ?></td>
            <td><?php echo $flight['itinerary']; ?></td>
            <td><?php echo $flight['fees']; ?></td>
            <td><?php echo $flight['startTime']; ?></td>
            <td><?php echo $flight['endTime']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- Add a link to go back to the add flight page -->
<!-- <a href="AddFlight.php">Add a New Flight</a> -->
<!-- <a href="AddFlight.php" class="button-link">Add a New Flight</a> -->

</body>
</html>
