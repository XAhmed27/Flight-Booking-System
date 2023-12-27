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

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
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
            /* Add margin-top for spacing */
            text-decoration: none;
            /* Remove underline from the link */
        }



        .widget,
        h1 {
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

    global $conn;

    $companyId = $_COOKIE['id'];

    if (isset($_POST['flightID'], $_POST['fees'])) {
        $flightID = $_POST['flightID'];
        $fees = $_POST['fees'];

        try {
            // Begin transaction
            $conn->begin_transaction();

            // Update the flight status
            $updateFlightQuery = "UPDATE flight SET status = 'off' WHERE flightID = ?";
            $stmtUpdateFlight = $conn->prepare($updateFlightQuery);
            $stmtUpdateFlight->bind_param("i", $flightID);
            $stmtUpdateFlight->execute();

            // Check for errors
            if ($stmtUpdateFlight->errno) {
                throw new Exception("Error updating flight status: " . $stmtUpdateFlight->error);
            }

            // Update user account balances
            $sql = "UPDATE users
            SET accountBalance = accountBalance + ?
            WHERE userID IN (SELECT p.userID
                            FROM passenger p
                            JOIN passenger_flight pf ON p.passengerID = pf.passengerID
                            WHERE pf.flightID = ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("di", $fees, $flightID);
            $stmt->execute();

            // Check for errors
            if ($stmt->errno) {
                throw new Exception("Error updating account balance: " . $stmt->error);
            }

            // Commit the transaction
            $conn->commit();

            // Close the statement and connection
            $stmt->close();
            $stmtUpdateFlight->close();
            $conn->close();

            // Redirect to the flights page
            header("Location: FlightLists.php");
            exit();
        } catch (Exception $e) {
            // Rollback the transaction in case of an error
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }
    }


    function getFlightsData()
    {
        global $conn;
        $companyId = $_COOKIE['id'];

        try {
            $query = "SELECT flightID, name, flight_from, flight_to, fees, startTime, endTime , status
              FROM flight WHERE companyID=?";

            $stmt = $conn->prepare($query);

            $stmt->bind_param("i", $companyId);

            $stmt->execute();
            $stmt->bind_result($flightID, $name, $flightFrom, $flightTo, $fees, $startTime, $endTime, $status);

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
                    'status' => $status,
                ];
            }

            return $flights;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    $flights = getFlightsData();
    ?>


    <div class="widget">
        <h1>Flights</h1>
    </div>


    <table border="1">
        <tr>
            <th>Flight ID</th>
            <th>Flight Name</th>
            <th>From</th>
            <th>To</th>
            <th>Fees</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Status</th>
            <th>Action</th> <!-- New column for Cancel action -->
        </tr>
        <?php foreach ($flights ?? [] as $flightName => $flight) : ?>
            <tr>
                <td><?php echo $flight['flightID']; ?></td>
                <td><?php echo $flight['name']; ?></td>
                <td><?php echo $flight['flight_from']; ?></td>
                <td><?php echo $flight['flight_to']; ?></td>
                <td><?php echo $flight['fees']; ?></td>
                <td><?php echo $flight['startTime']; ?></td>
                <td><?php echo $flight['endTime']; ?></td>
                <td><?php echo $flight['status']; ?></td>
                <td>
                    <!-- Add a form with a button to cancel the flight -->
                    <form method="post" action="FlightLists.php">
                        <input type="hidden" name="flightID" value="<?php echo $flight['flightID']; ?>">
                        <input type="hidden" name="fees" value="<?php echo $flight['fees']; ?>">
                        <button type="submit">Cancel</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p></p>


    <a href="features/Home-Company/CompanyHome.php" style="margin-left: 15px; " class="button-link">Back</a>

</body>

</html>