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
            background-image: url('assets/air3.jpg');
            background-size: cover;
        }

        form {
            background: rgba(255, 255, 255, 0.5);
            font-size: 20px;
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
            background-color: #146C94;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #146C94;
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


    global $conn;

    // Handle form submission
    function getMessages()
    {
        global $conn;

        $companyID = $_COOKIE['id'];
        //1 //1
        try {
            // Retrieve pending passengers for the specified company
            $getPendingPassengersQuery = "SELECT u.name AS passengerName
                    FROM users u
                    INNER JOIN passenger p ON u.userID = p.userID
                    INNER JOIN passenger_flight pf ON p.passengerID = pf.passengerID
                    WHERE   pf.passengerStatus = 'registered'";

            $getPendingPassengersStmt = $conn->prepare($getPendingPassengersQuery);
            $getPendingPassengersStmt->execute();
            $getPendingPassengersStmt->bind_result($passengerName);

            $registered = [];
            while ($getPendingPassengersStmt->fetch()) {
                $registered[] = [
                    'passengerName' => $passengerName,
                ];
            }
            return $registered;
        } catch (PDOException $e) {
            echo '<p class="error">Error: ' . $e->getMessage() . '</p>';
        }
    }
    $registered = getMessages();
    ?>
    
    
    <div class="widget">
        <h1>Registered</h1>
    </div>
    
    <div class="widget2">
        <table border="1">
            <tr>
                <th>Passenger Name</th>
            </tr>
            <?php foreach ($Registered ?? [] as $passengerName => $Registered) : ?>
                <tr>
                    <td><?php echo $messages ['passengerName']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <p></p>
    
    <a href="/features/Home-Company/CompanyHome.php" class="button-link">Back</a>
    
    </body>
    </html>