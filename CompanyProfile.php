<?php
require_once 'vendor/autoload.php';
require_once 'errorhandling.php';
require_once 'connection.php';


global $conn;



$companyId = $_COOKIE['id'];

// Check if required parameters are present in the URL
if (!isset($_GET['name']) || !isset($_GET['bio']) || !isset($_GET['address']) || !isset($_GET['username']) || !isset($_GET['location']) || !isset($_GET['tel']) || !isset($_GET['accountBalance'])) {
    // Redirect to companyinfo.php if parameters are missing
    header("Location: companyinfo.php");
    exit();
}

// Initialize variables
$nameFromUrl = $_GET['name'];
$bioFromUrl = $_GET['bio'];
$addressFromUrl = $_GET['address'];
$usernameFromUrl = $_GET['username'];
$locationFromUrl = $_GET['location'];
$telFromUrl = $_GET['tel'];
$accountBalanceFromUrl = $_GET['accountBalance'];
$companyData = array();

$newName = $newBio = $newAddress = $newUsername = $newLocation = $newPassword = '';
$message = $passwordMessage = '';

$updateCompanyQuery = $updateUserQuery = $updatePasswordQuery = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $newName = $_POST['newName'];
    $newBio = $_POST['newBio'];
    $newAddress = $_POST['newAddress'];
    $newUsername = $_POST['newUsername'];
    $newLocation = $_POST['newLocation'];
    $newPassword = $_POST['newPassword'];

    try {
        $selectQuery = "SELECT userID FROM company WHERE bio = ? AND address = ? AND username = ?";
        $stmtSelect = $conn->prepare($selectQuery);
        $stmtSelect->bind_param('sss', $bioFromUrl, $addressFromUrl, $usernameFromUrl);
        $stmtSelect->execute();
        $resultSelect = $stmtSelect->get_result();

        $companyData = $resultSelect->fetch_assoc();

        if ($companyData) {
            $updateCompanyQuery = "UPDATE company SET bio = ?, username = ?, address = ?, location = ? WHERE userID = ?";
            $stmtCompany = $conn->prepare($updateCompanyQuery);
            $stmtCompany->bind_param('ssssi', $newBio, $newUsername, $newAddress, $newLocation, $companyData['userID']);
            $stmtCompany->execute();
            if ($stmtCompany->execute() === FALSE) {
                error_log("Company Update Error: " . $stmtCompany->error);
            }

            $updateUserQuery = "UPDATE users SET name = ?, tel = ?, accountBalance = ? WHERE userID = ?";
            $stmtUser = $conn->prepare($updateUserQuery);
            $stmtUser->bind_param('sdsi', $newName, $telFromUrl, $accountBalanceFromUrl, $companyData['userID']);
            $stmtUser->execute();
            if ($stmtUser->execute() === FALSE) {
                error_log("User Update Error: " . $stmtUser->error);
            }

            if (!empty($newPassword)) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updatePasswordQuery = "UPDATE users SET password = ? WHERE userID = ?";
                $stmtPassword = $conn->prepare($updatePasswordQuery);
                $stmtPassword->bind_param('si', $hashedPassword, $companyData['userID']);
                $stmtPassword->execute();
                $passwordMessage = 'Password updated successfully.';
            }


            header("Location: CompanyProfile.php?name=" . urlencode($newName) . "&bio=" . urlencode($newBio) . "&address=" . urlencode($newAddress) . "&username=" . urlencode($newUsername) . "&location=" . urlencode($newLocation) . "&tel=" . urlencode($telFromUrl) . "&accountBalance=" . urlencode($accountBalanceFromUrl));
            exit();
        } else {
            $message = 'Failed to update company information. User not found.';
        }
    } catch (Exception $e) {
        $message = 'Failed to update company information. Error: ' . $e->getMessage();
        error_log("Error: " . $e->getMessage());
    } finally {
        error_log("Update Company Query: $updateCompanyQuery");
        error_log("Update User Query: $updateUserQuery");
        error_log("Update Password Query: $updatePasswordQuery");
        error_log("Error: " . $conn->error);

        $conn->close();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Profile</title>
    <style>
        /* Add your styles here */
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('assets/air3.jpg');
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        div {
            background: rgba(255, 255, 255, 0.5);
            font-size: 20px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin: 20px auto;
            text-align: center;
        }

        h2 {
            color: #333;
        }

        p {
            margin: 8px 0;
            color: #555;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input,
        textarea {
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

        a.nav-button {
            display: block;
            background-color: #2196F3;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            text-decoration: none;
            width: 200px;
            text-align: center;
            font-size: 16px;
        }

        h2{
            margin-right: 10px;
        }
        a.nav-button:hover {
            background-color: #0b7dda;
        }

        .password-message {
            color: green;
        }

        .error-message {
            color: red;
        }
    </style>
</head>

<body>

<div>
    <h2>Company Information</h2>
    <p>Name: <?php echo htmlspecialchars($nameFromUrl); ?></p>
    <p>Bio: <?php echo htmlspecialchars($bioFromUrl); ?></p>
    <p>Address: <?php echo htmlspecialchars($addressFromUrl); ?></p>
    <p>Username: <?php echo htmlspecialchars($usernameFromUrl); ?></p>
    <p>Location: <?php echo htmlspecialchars($locationFromUrl); ?></p>
    <p>Tel: <?php echo htmlspecialchars($telFromUrl); ?></p>
    <p>Account Balance: <?php echo htmlspecialchars($accountBalanceFromUrl); ?></p>

    <p class="password-message"><?php echo $passwordMessage; ?></p>

    <p class="error-message"><?php echo $message; ?></p>

</div>

<form action="" method="post">
    <label for="newName">Update Name:</label>
    <input type="text" id="newName" name="newName" value="<?php echo htmlspecialchars($nameFromUrl); ?>" required>

    <label for="newBio">Update Bio:</label>
    <textarea id="newBio" name="newBio" required><?php echo htmlspecialchars($bioFromUrl); ?></textarea>

    <label for="newAddress">Update Address:</label>
    <input type="text" id="newAddress" name="newAddress" value="<?php echo htmlspecialchars($addressFromUrl); ?>" required>

    <label for="newUsername">Update Username:</label>
    <input type="text" id="newUsername" name="newUsername" value="<?php echo htmlspecialchars($usernameFromUrl); ?>" required>

    <label for="newLocation">Update Location:</label>
    <input type="text" id="newLocation" name="newLocation" value="<?php echo htmlspecialchars($locationFromUrl); ?>" required>

    <label for="newPassword">Update Password:</label>
    <input type="password" id="newPassword" name="newPassword">

    <input type="submit" name="update" value="Update">
</form>

<a href="/features/Home-Company/CompanyHome.php" style="margin-right: 8px;" class="nav-button">Back to Company Home</a>
<a href="FlightLists.php" class="nav-button">flight list</a>

</body>

</html>