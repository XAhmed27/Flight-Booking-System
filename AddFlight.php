<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Flight</title>
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

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin-bottom: 20px;
        }

        input {
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

        h2, h3 {
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
            margin-top: 20px; 
            text-decoration: none; 
        }

        
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

// Handle form submission to add a new flight
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newFlightName = $_POST['flight_name'];
    $newFlightItinerary = $_POST['flight_itinerary'];
    $newFlightFees = $_POST['flight_fees'];
    $newFlightStartTime = $_POST['flight_start_time'];
    $newFlightEndTime = $_POST['flight_end_time'];

    // Add the new flight to the database
    try {
        $insertQuery = "INSERT INTO flight (name, itinerary, fees, startTime, endTime)
                        VALUES (?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param('sssss', $newFlightName, $newFlightItinerary, $newFlightFees, $newFlightStartTime, $newFlightEndTime);
        $insertStmt->execute();
        $insertStmt->close();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Call the functions to get data
$flights = getFlightsData();
?>
<!-- Display add flight form -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="flight_name">Flight Name:</label>
    <input type="text" id="flight_name" name="flight_name" required><br>

    <label for="flight_itinerary">Itinerary:</label>
    <input type="text" id="flight_itinerary" name="flight_itinerary" required><br>

    <label for="flight_fees">Fees:</label>
    <input type="text" id="flight_fees" name="flight_fees" required><br>

    <label for="flight_start_time">Start Time:</label>
    <input type="text" id="flight_start_time" name="flight_start_time" required><br>

    <label for="flight_end_time">End Time:</label>
    <input type="text" id="flight_end_time" name="flight_end_time" required><br>

    <input type="submit" value="Add Flight">
</form>

<!-- Redirect to the flights list page -->
<!-- <a href="FlightLists.php">View Flights List</a> -->

</body>
</html>
