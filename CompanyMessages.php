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
            background-image: url('assets/flyy.jpg');
            background-size: cover;
        }

        table {
            width: 100%;
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
            margin-top: 20px; /* Add margin-top for spacing */
            text-decoration: none; /* Remove underline from the link */
        }



        .widget, h1 {
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


function getMessages()
{
        global $conn;
        $companyID = $_COOKIE['id'];
        try {
            // Retrieve messages for the company from the message table
            $getMessagesQuery = "SELECT m.text, u.name AS senderName
                                    FROM message m
                                    INNER JOIN passenger p ON m.passengerID = p.passengerID
                                    INNER JOIN users u ON p.userID = u.userID
                                    WHERE m.companyID = ?";
            $getMessagesStmt = $conn->prepare($getMessagesQuery);
            $getMessagesStmt->bind_param("i", $companyID);
            $getMessagesStmt->execute();
            $getMessagesStmt->bind_result($text, $senderName);

            // Fetch all rows
            $messages = [];
            while ($getMessagesStmt->fetch()) {
                $messages[] = [
                    'senderName' => $senderName,
                    'text' => $text,
                ];
            }
            return $messages;

        } catch (PDOException $e) {
            echo '<p class="error">Error: ' . $e->getMessage() . '</p>';
        }
}
$messages = getMessages();
?>


<div class="widget">
    <h1>Messages</h1>
</div>

<div class="widget2">
    <table border="1">
        <tr>
            <th>text</th>
            <th>sender Name</th>
        </tr>
        <?php foreach ($messages ?? [] as $text => $messages) : ?>
            <tr>
                <td><?php echo $messages ['text']; ?></td>
                <td><?php echo $messages ['senderName']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<p></p>

<a href="/features/Home-Company/CompanyHome.php" class="button-link">Back</a>

</body>
</html>