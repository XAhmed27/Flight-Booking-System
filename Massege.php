<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger List</title>
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
    $passengerID = isset($_GET['passengerID']) ? $_GET['passengerID'] : null;

    if (!$passengerID) {
        // at2kd el awl 
        header("Location: MassegeAuth.php");
        exit();
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];

        try {
            
            $getCompanyIdQuery = "SELECT companyID FROM company WHERE username = ?";
            $stmt = $conn->prepare($getCompanyIdQuery);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                // User found, fetch companyId
                $row = $result->fetch_assoc();
                $companyId = $row['companyID'];

               
                // Store the companyId, passengerID, and message in the message table
                $messageText = $_POST['message'];
                $insertMessageQuery = "INSERT INTO message (companyID, passengerID, text) VALUES (?, ?, ?)";
                $insertStmt = $conn->prepare($insertMessageQuery);
                $insertStmt->bind_param('iis', $companyId, $passengerID, $messageText);

                if ($insertStmt->execute()) {
                    echo '<p class="success">Message stored successfully!</p>';
                } else {
                    echo '<p class="error">Error storing message.</p>';
                }
            } else {
                echo '<p class="error">User not found.</p>';
            }

            $stmt->close();
            $insertStmt->close();
        } catch (PDOException $e) {
            echo '<p class="error">Error: ' . $e->getMessage() . '</p>';
        }
    }
    ?>

    <!--  form to enter username and message -->
    <form action="" method="post">
        <label for="username">Enter compayname:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="message">Enter Message:</label>
        <input type="text" id="message" name="message" required><br>

        <!-- a hidden field for passengerID -->
        <input type="hidden" name="passengerID" value="<?php echo isset($_GET['passengerID']) ? $_GET['passengerID'] : ''; ?>">

        <input type="submit" value="Submit">
    </form>

</body>

</html>
