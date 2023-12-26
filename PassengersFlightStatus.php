<?php
require_once 'vendor/autoload.php';
require_once 'errorhandling.php';
require_once 'connection.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    global $conn;
    $companyId = $_COOKIE['id'];
    try {
        $flightID = $_POST['flightID'];
        // Prepare the SQL query to retrieve flight and passenger details
        $getFlightDetailsQuery = "SELECT f.flightID, f.name AS flightName, p.passengerID, pf.passengerStatus
        FROM flight f
        LEFT JOIN passenger_flight pf ON f.flightID = pf.flightID
        LEFT JOIN passenger p ON pf.passengerID = p.passengerID
        WHERE f.flightID =?";
        

 
        $stmt = $conn->prepare($getFlightDetailsQuery);
        $stmt->bind_param("i", $flightID);
        $stmt->execute();
        $stmt->bind_result($flightID, $flightName, $passengerID, $passengerStatus);
        // Fetch the results
        $result = [];
        while ($stmt->fetch()) {
            $result[] = [
                'flightID' => $flightID,
                'flightName' => $flightName,
                'passengerID' => $passengerID,
                'passengerStatus' => $passengerStatus,
            ];
        }
        $stmt->close();
    } catch (Exception $exception) {
        // Call handleGlobalError in case of an exception
        handleGlobalError($exception);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Information</title>
</head>

<body>
    <form method="POST" action="">
        <label for="flightID">Flight ID:</label>
        <input type="text" id="flightID" name="flightID" required>
        <button type="submit">Get Flight Information</button>
    </form>
</body>

</html>


<div class="widget">
    <h1>Details</h1>
</div>

<div class="widget2">
    <table border="1">
        <tr>
            <th>Flight ID</th>
            <th>flight Name</th>
            <th>Passenger ID</th>
            <th>Passenger Status</th>
        </tr>
        <?php foreach ($result ?? [] as $text => $result) : ?>
            <tr>
                <td><?php echo $result['flightID']; ?></td>
                <td><?php echo $result['flightName']; ?></td>
                <td><?php echo $result['passengerID']; ?></td>
                <td><?php echo $result['passengerStatus']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<p></p>

<a href="/features/Home-Company/CompanyHome.php" class="button-link">Back</a>

</body>

</html>