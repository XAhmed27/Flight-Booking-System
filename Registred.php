<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Register</title>
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

        .error {
            color: red;
            margin-top: 10px;
        }

        .success {
            color: green;
            margin-top: 10px;
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

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];

        try {
            // Check if a  username exists in the company table
            $getCompanyIdQuery = "SELECT companyID FROM company WHERE username = ?";
            $stmtCompany = $conn->prepare($getCompanyIdQuery);
            $stmtCompany->bind_param("s", $username);
            $stmtCompany->execute();
            $resultCompany = $stmtCompany->get_result();

            if ($resultCompany->num_rows === 1) {
            
                $rowCompany = $resultCompany->fetch_assoc();
                $companyID = $rowCompany['companyID'];

                // Retrieve pending passengers for the specified company
                $getPendingPassengersQuery = "SELECT u.name AS passengerName
                    FROM users u
                    INNER JOIN passenger p ON u.userID = p.userID
                    INNER JOIN passenger_flight pf ON p.passengerID = pf.passengerID
                    WHERE   pf.passengerStatus = 'registered'";

                $getPendingPassengersStmt = $conn->prepare($getPendingPassengersQuery);
                $getPendingPassengersStmt->execute();
                $pendingPassengersResult = $getPendingPassengersStmt->get_result();

                if ($pendingPassengersResult->num_rows > 0) {
                    // Display pending passengers
                    echo '<div class="success">';
                    echo '<h3>Pending Passengers for Company Name ' . $username . '</h3>';
                    while ($pendingPassenger = $pendingPassengersResult->fetch_assoc()) {
                        echo '<div>';
                        echo '<strong>Passenger Name:</strong> ' . $pendingPassenger['passengerName'] . '<br>';
                        echo '</div><br>';
                    }
                    echo '</div>';
                } else {
                    echo '<p class="success">No Registred passengers found for this company.</p>';
                }

                $getPendingPassengersStmt->close();
            } else {
                echo '<p class="error">Company not found.</p>';
            }

            $stmtCompany->close();
        } catch (PDOException $e) {
            echo '<p class="error">Error: ' . $e->getMessage() . '</p>';
        }
    }
    ?>

   
    <form action="" method="post">
        <label for="username">Enter Username:</label>
        <input type="text" id="username" name="username" required><br>

        <input type="submit" value="Submit">
    </form>

</body>


</html>
