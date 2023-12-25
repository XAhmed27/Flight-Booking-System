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
            background-image: url('assets/air3.jpg');
            background-size: cover;
        }

        form {
            background-color: rgba(255, 255, 255, 0.8); /* Set background color with alpha transparency */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin-bottom: 20px;
            backdrop-filter: blur(5px); /* Apply backdrop filter for a blurred effect */
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input {
            height: 3vh;
            width: 100%;
            margin: 8px 0;
            box-sizing: border-box;
            padding: 8px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 16px;
            font-size: 16px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: black;
            transition: border-color 0.3s;
        }

        input[type="submit"] {
            background-color: #146C94;
            color: white;
            cursor: pointer;
            height: 50px;
        }

        input[type="submit"]:hover {
            background-color: #146C94;
        }

        h2, h3 {
            text-align: center;
            margin-bottom: 20px;
            color: white; /* Set text color to white */
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        .success {
            color: green;
            margin-top: 10px;
            margin-left: 10px;
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

$companyId = isset($_GET['company_id']) ? $_GET['company_id'] : null;

if (!$companyId) {
    // go to auth first
    header("Location: AddFlightAuth.php");
    exit();
}

// get flights
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
        return [];  // Return an empty array on error
    }
}

// Handle form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyId = $_POST['companyID'];
    $newFlightName = $_POST['flight_name'];
    $newFlightFrom = $_POST['flight_from'];
    $newFlightTo = $_POST['flight_to'];
    $newFlightFees = $_POST['flight_fees'];
    $newFlightStartTime = $_POST['flight_start_time'];
    $newFlightEndTime = $_POST['flight_end_time'];

    // Add the new flight
    try {
        $insertQuery = "INSERT INTO flight (name, flight_from, flight_to, fees, startTime, endTime, companyID)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param('ssssssi', $newFlightName, $newFlightFrom, $newFlightTo, $newFlightFees, $newFlightStartTime, $newFlightEndTime, $companyId);

        if ($insertStmt->execute()) {
            echo '<p class="success">Flight added successfully!</p>';
        } else {
            echo '<p class="error">Error adding flight.</p>';
        }

        $insertStmt->close();
    } catch (PDOException $e) {
        echo '<p class="error">Error: ' . $e->getMessage() . '</p>';
    }
}

// bnady el functions
$flights = getFlightsData();
?>

<!-- Display add flight form -->
<form action="" method="post">
    <input type="hidden" name="companyID" value="<?php echo htmlspecialchars($companyId); ?>">

    <label for="flight_name">Flight Name:</label>
    <input type="text" id="flight_name" name="flight_name" required><br>

    <label for="flight_from">From:</label>
    <input type="text" id="flight_from" name="flight_from" required><br>

    <label for="flight_to">To:</label>
    <input type="text" id="flight_to" name="flight_to" required><br>

    <label for="flight_fees">Fees:</label>
    <input type="text" id="flight_fees" name="flight_fees" required><br>

    <label for="flight_start_time">Start Time:</label>
    <input type="text" id="flight_start_time" name="flight_start_time" required><br>

    <label for="flight_end_time">End Time:</label>
    <input type="text" id="flight_end_time" name="flight_end_time" required><br>

    <input type="submit" value="Add Flight">
</form>

</body>

</html>