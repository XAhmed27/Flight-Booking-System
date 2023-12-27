<?php
require_once 'vendor/autoload.php';
require_once 'errorhandling.php';
require_once 'connection.php';


global $conn;
$passengerID = $_COOKIE['id'];
$flightFrom = isset($_POST['flight_from']) ? $_POST['flight_from'] : '';
$flightTo = isset($_POST['flight_to']) ? $_POST['flight_to'] : '';
$passengerStatus = isset($_POST['status']) ? $_POST['status'] : 'pending';
$message = '';

// Check if the passenger ID is provided in the URL
if (!empty($passengerID)) {
    try {
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($flightFrom) && !empty($flightTo)) {
            // Search for the flight in the flight table
            $searchFlightQuery = "SELECT flightID, companyID, fees FROM flight WHERE flight_to = ? AND flight_from = ? AND status = 'on'";
            $stmtFlight = $conn->prepare($searchFlightQuery);
            $stmtFlight->bind_param("ss", $flightTo, $flightFrom);
            $stmtFlight->execute();
            $resultFlight = $stmtFlight->get_result();
    
            if ($resultFlight->num_rows > 0) {
                $row = $resultFlight->fetch_assoc();
                $flightID = $row['flightID'];
                $fees = $row['fees'];
                $companyID = $row['companyID'];
    
                // Get account balance
                $getBalanceQuery = "SELECT u.accountBalance
                                    FROM users u
                                    INNER JOIN passenger p ON u.userID = p.userID
                                    WHERE p.passengerID = ?";
                $stmtBalance = $conn->prepare($getBalanceQuery);
                $stmtBalance->bind_param("i", $passengerID);
                $stmtBalance->execute();
                $resultBalance = $stmtBalance->get_result();
    
                if ($resultBalance->num_rows > 0) {
                    $row = $resultBalance->fetch_assoc();
                    $accountBalance = $row['accountBalance'];
    
                    // Check balance
                    if ($accountBalance >= $fees) {
                        // Deduct fees from the account balance
                        $newBalance = $accountBalance - $fees;
    
                        // Update passenger balance
                        $updateBalanceQuery = "UPDATE users u
                                              INNER JOIN passenger p ON u.userID = p.userID
                                              SET u.accountBalance = ?
                                              WHERE p.passengerID = ?";
                        $stmtUpdateBalance = $conn->prepare($updateBalanceQuery);
                        $stmtUpdateBalance->bind_param("di", $newBalance, $passengerID);
                        $stmtUpdateBalance->execute();
    
                        // Store passenger flight record
                        $insertPassengerFlightQuery = "INSERT INTO passenger_flight (passengerID, flightID, passengerStatus) VALUES (?, ?, ?)";
                        $stmtInsertPassengerFlight = $conn->prepare($insertPassengerFlightQuery);
                        $stmtInsertPassengerFlight->bind_param("iss", $passengerID, $flightID, $passengerStatus);
                        $stmtInsertPassengerFlight->execute();
    
                        // Get company balance
                        $getCompanyBalanceQuery = "SELECT u.accountBalance
                                                FROM users u
                                                INNER JOIN company c ON u.userID = c.userID
                                                WHERE c.companyID = ?";
                        $stmtCompanyBalance = $conn->prepare($getCompanyBalanceQuery);
                        $stmtCompanyBalance->bind_param("i", $companyID);
                        $stmtCompanyBalance->bind_result($cBalance);
                        $stmtCompanyBalance->execute();
                        $stmtCompanyBalance->fetch();
                        $stmtCompanyBalance->close();
    
                        // Update company balance
                        $newCompanyBalance = $cBalance + $fees;
                        $updateCompanyBalanceQuery = "UPDATE users u
                                                      INNER JOIN company c ON u.userID = c.userID
                                                      SET u.accountBalance = ?
                                                      WHERE c.companyID = ?";
                        $stmtUpdateCompanyBalance = $conn->prepare($updateCompanyBalanceQuery);
                        $stmtUpdateCompanyBalance->bind_param("di", $newCompanyBalance, $companyID);
                        $stmtUpdateCompanyBalance->execute();
    
                        $message = 'Flight registration successful! New balance: ' . $newBalance;
                    } else {
                        $message = 'Sorry, not enough money in the account.';
                    }
                } else {
                    $message = 'Passenger not found.';
                }
            } else {
                $message = 'No matching flight found.';
            }
    
            // Close the statements
            $stmtFlight->close();
        }
    } catch (Exception $e) {
        // Handle any exceptions
        $message = 'An error occurred: ' . $e->getMessage();
    }
} else {
    $message = 'Passenger ID is missing in the URL.';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Registration</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('assets/air3.jpg');
            /* Replace with your background image path */
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: rgba(0, 0, 0, 0.3);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin-bottom: 20px;
            text-align: center;
        }

        input,
        select {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 8px 0;
            box-sizing: border-box;
            border: none;
            /* Remove default border */
            border-radius: 5px;
            /* Add border-radius for rounded corners */
            background-color: rgba(255, 255, 255, 0.5);
            /* Semi-transparent white background */
        }

        input[type="submit"] {
            background-color: #146C94;
            /* Blue color */
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #12516E;
            /* Darker blue on hover */
        }

        p {
            color: red;
            margin-top: 10px;
        }

        .message {
            color: green;
            margin-top: 10px;
        }
        .button-link:hover {
            background-color: #146C94;
        }
        h2 {
            display: block;
            text-align: center;
        }

    </style>
</head>

<body>

    <h2>Flight Registration</h2>

    <p><?php echo $message; ?></p>

    <form action="" method="post">
        <input type="hidden" name="passenger_id" value="<?php echo htmlspecialchars($passengerID); ?>">

        <label for="flight_from">Flight From:</label>
        <input type="text" id="flight_from" name="flight_from" required>

        <label for="flight_to">Flight To:</label>
        <input type="text" id="flight_to" name="flight_to" required>

        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="pending">Pending</option>
            <option value="registered">Registered</option>
        </select>

        <input type="submit" value="Register for Flight">
        <a href="PassengerHome.php" style="margin-left: 15px; " class="button-link">Back</a>

    </form>

</body>

</html>