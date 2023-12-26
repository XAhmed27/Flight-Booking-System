<?php
require_once '../../vendor/autoload.php';
require_once '../../errorhandling.php';
require_once '../../connection.php';

$userId = $_GET['userId'] ?? 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        global $conn; // Assuming $conn is defined somewhere in your code

        $PassportImg = isset($_FILES['PassportImg']) ? $_FILES['PassportImg']["name"] : "";
        $photo = isset($_FILES['photo']) ? $_FILES['photo']["name"] : "";
        
        // Handling logoImg file upload
        if (isset($_FILES['photo']['tmp_name']) && empty($_FILES['photo']['tmp_name'])) {
            echo "<script> alert('Image Does Not Exist for logoImg');</script>";
        } else {
            $fileName = $_FILES["photo"]["name"];
            $fileSize = $_FILES["photo"]["size"];
            $tmpName = $_FILES["photo"]["tmp_name"];
        
            $validImagesExtension = ['jpg', 'jpeg', 'png'];
            $imageExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
            if (!in_array($imageExtension, $validImagesExtension)) {
                echo "<script> alert('Invalid Image Extension for photo');</script>";
            } else if ($fileSize > 1000000) {
                echo "<script> alert('Image Size is Too Large for photo');</script>";
            }else{
                $newImageName =uniqid();
                $newImageName .='.' . $imageExtension;
                move_uploaded_file($tmpName,'../../assets/'. $newImageName);
            }
        }
        
        // Handling passImge file upload
        if (isset($_FILES['PassportImg']['tmp_name']) && empty($_FILES['PassportImg']['tmp_name'])) {
            echo "<script> alert('Image Does Not Exist for PassportImg');</script>";
        } else {
            $passFileName = $_FILES["PassportImg"]["name"];
            $passFileSize = $_FILES["PassportImg"]["size"];
            $passTmpName = $_FILES["PassportImg"]["tmp_name"];
        
            $validPassExtensions = ['jpg', 'jpeg', 'png'];
            $passExtension = strtolower(pathinfo($passFileName, PATHINFO_EXTENSION));
        
            if (!in_array($passExtension, $validPassExtensions)) {
                echo "<script> alert('Invalid Image Extension for PassportImg');</script>";
            } else if ($passFileSize > 1000000) {
                echo "<script> alert('Image Size is Too Large for PassportImg');</script>";
            }else{
                $newPassName =uniqid();
                $newPassName .='.' . $passExtension;
                move_uploaded_file($tmpName,'../../assets/'. $newPassName);
            }
        }
        $insertPassengerQuery = "INSERT INTO passenger (userID, PassportImg, photo) VALUES (?, ?, ?)";
        $stmtPassenger = $conn->prepare($insertPassengerQuery);
        $stmtPassenger->bind_param("iss", $userId, $newPassName, $newImageName);
        $stmtPassenger->execute();

        // header("Location: ../../../PassengerHome.php");
         header("Location:../Login/login.ui.php");


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

<form id="passengerForm" action="" method="post" enctype="multipart/form-data">
        <label for='PassportImg'>Passport Img:</label>
        <input style="border-color: transparent; "type="file" name="PassportImg" accept=".jpg, .jpeg, .png" value=""><br><br>
        <label for='photo'>Passport Img:</label>
        <input style="border-color: transparent; "type="file" name="photo" accept=".jpg, .jpeg, .png" value=""><br><br>
    <input type="submit" value="Submit Passenger Info">
</form>

</body>
</html>
