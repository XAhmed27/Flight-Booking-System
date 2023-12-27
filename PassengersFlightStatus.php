<?php
require_once 'vendor/autoload.php';
require_once 'errorhandling.php';
require_once 'connection.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    global $conn;
    $companyId = $_COOKIE['id'];
    try {
        $flightID = $_POST['flightID'];
        $getFlightDetailsQuery = "SELECT f.flightID, f.name AS flightName, p.passengerID, pf.passengerStatus
        FROM flight f
        LEFT JOIN passenger_flight pf ON f.flightID = pf.flightID
        LEFT JOIN passenger p ON pf.passengerID = p.passengerID
        WHERE f.flightID =?";



        $stmt = $conn->prepare($getFlightDetailsQuery);
        $stmt->bind_param("i", $flightID);
        $stmt->execute();
        $stmt->bind_result($flightID, $flightName, $passengerID, $passengerStatus);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            background-image: url('assets/air3.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .widget {
            background-color: rgba(255, 255, 255, 0.8); 
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
            text-align: center;
            margin-bottom: 30px;
            animation: fadeInUp 1s ease-in-out;
        }

        .widget h1 {
            font-weight: bold;
            margin-bottom: 10px;
        }

        form input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        form button {
            background-color: #146C94;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #f5f5f5;
            border-radius: 10px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 1s ease-in-out;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        th {
            background-color: #146C94;
            color: white;
        }

        .button-link {
            background-color: transparent;
            border: 1px solid #146C94;
            color: #146C94;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            animation: fadeInUp 1s ease-in-out;
        }

        .button-link:hover {
            background-color: #146C94;
            color: white;
        }
    </style>
</head>

<body>
<div class="widget">
    <h1>Flight Search</h1>
    <form method="POST" action="">
        <label for="flightID">Flight ID:</label>
        <input type="text" id="flightID" name="flightID" required>
        <button type="submit">Get Flight Information</button>
    </form>
</div>

<div class="widget">
    <h1>Details</h1>
    <table border="1">
        <tr>
            <th>Flight ID</th>
            <th>Flight Name</th>
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
<a href="features/Home-Company/CompanyHome.php" class="button-link">Back</a>
</body>

</html>


