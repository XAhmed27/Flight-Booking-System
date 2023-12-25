<?php
require_once 'vendor/autoload.php';
require_once 'errorhandling.php';
require_once 'connection.php';

// Function to get flights data from the database based on search criteria
function searchFlights($from, $to)
{
    global $conn;

    try {
        $query = "SELECT flightID, name, flight_from, flight_to, fees, startTime, endTime
                  FROM flight
                  WHERE flight_from = ? AND flight_to = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $from, $to);
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

// Handle search form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchFrom = $_POST['search_from'];
    $searchTo = $_POST['search_to'];

    // Call the function to get data based on search criteria
    $flights = searchFlights($searchFrom, $searchTo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Flights</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            background-image: url('assets/air3.jpg');
            background-size: cover;
        }

        h3 {
            background: rgba(255, 255, 255, 0.5);
            font-size: 20px;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 80%;
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
            margin-top: 20px;
            text-decoration: none;
        }

        .button-link:hover {
            background-color: #146C94;
        }

        .search-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-input {
            padding: 10px;
            margin-right: 10px;
        }

        .search-btn {
            background-color: #146C94;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-btn:hover {
            background-color: #146C94;
        }
    </style>
</head>
<body>

<h3>Search Flights</h3>

<div class="search-container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input class="search-input" type="text" name="search_from" placeholder="From" required>
        <input class="search-input" type="text" name="search_to" placeholder="To" required>
        <button class="search-btn" type="submit">Search</button>
    </form>
</div>

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


<a href="AddFlight.php" class="button-link">Add a New Flight</a>
<a href="FlightLists.php" class="button-link">Flight Lists</a>

</body>
</html>