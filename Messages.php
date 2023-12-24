<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
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

        .messages {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        .message-item {
            margin-bottom: 10px;
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

    // Handle submittion
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];

        try {
           
            $getCompanyIdQuery = "SELECT companyID FROM company WHERE username = ?";
            $stmtCompany = $conn->prepare($getCompanyIdQuery);
            $stmtCompany->bind_param("s", $username);
            $stmtCompany->execute();
            $resultCompany = $stmtCompany->get_result();

            if ($resultCompany->num_rows === 1) {
                // if found, return companyID
                $rowCompany = $resultCompany->fetch_assoc();
                $companyID = $rowCompany['companyID'];

                // Retrieve messages for the company from the message table 
                $getMessagesQuery = "SELECT m.text, u.name AS senderName
                                    FROM message m
                                    INNER JOIN passenger p ON m.passengerID = p.passengerID
                                    INNER JOIN users u ON p.userID = u.userID
                                    WHERE m.companyID = ?";
                $getMessagesStmt = $conn->prepare($getMessagesQuery);
                $getMessagesStmt->bind_param("i", $companyID);
                $getMessagesStmt->execute();
                $messagesResult = $getMessagesStmt->get_result();

                if ($messagesResult->num_rows > 0) {
                    // Display messages
                    echo '<div class="messages">';
                    while ($message = $messagesResult->fetch_assoc()) {
                        echo '<div class="message-item">';
                        echo '<strong>Sender Name:</strong> ' . $message['senderName'] . '<br>';
                        echo '<strong>Message:</strong> ' . $message['text'];
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<p class="success">No messages for this company.</p>';
                }

                $getMessagesStmt->close();
            } else {
                echo '<p class="error">Company not found.</p>';
            }

            $stmtCompany->close();
        } catch (PDOException $e) {
            echo '<p class="error">Error: ' . $e->getMessage() . '</p>';
        }
    }
    ?>

    <!-- Display form to enter username -->
    <form action="" method="post">
        <label for="username">Enter Username:</label>
        <input type="text" id="username" name="username" required><br>

        <input type="submit" value="Submit">
    </form>

</body>

</html>
