<?php
require_once '../../vendor/autoload.php';
require_once '../../errorhandling.php';
require_once '../../connection.php';

$userId = $_GET['userId'] ?? 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        global $conn; // Assuming $conn is defined somewhere in your code

        // Retrieve data from the form
        $passportImg = $_POST['passportImg'];
        $photo = $_POST['photo'];

        // Insert passenger data
        $insertPassengerQuery = "INSERT INTO passenger (userID, PassportImg, photo) VALUES (?, ?, ?)";
        $stmtPassenger = $conn->prepare($insertPassengerQuery);
        $stmtPassenger->bind_param("iss", $userId, $passportImg, $photo);
        $stmtPassenger->execute();

        header("Location: ../../../PassengerHome.php");


        exit();
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('../../assets/flyy.jpg');
            background-size: cover;
            height: 100vh;
            width: 100vw;
        }

        form {
            background-color: rgba(255, 255, 255, 0.3);
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
            background-color: transparent; /* Transparent background for input */
            border: 1px solid #ccc; /* Rectangle border with a light gray color */
        }

        input[type="submit"] {
            background-color: #146C94;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #146C94;
        }
    </style>
</head>
<body>

<form id="passengerForm" action="" method="post">
    Passport Img: <input type="text" name="passportImg" required><br>
    Photo: <input type="text" name="photo" required><br>
    <input type="submit" value="Submit Passenger Info">
</form>

</body>
</html>
