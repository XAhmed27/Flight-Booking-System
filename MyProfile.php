<?php
require_once 'vendor/autoload.php';
require_once 'errorhandling.php';
require_once 'connection.php';
use \Firebase\JWT\JWT;

global $conn;


// Check if required parameters are present in the URL
if (!isset($_GET['email']) || !isset($_GET['name']) || !isset($_GET['tel']) || !isset($_GET['balance'])) {
    // Redirect to passengerinfo.php if parameters are missing
    header("Location: passengerinfo.php");
    exit();
}

// Initialize variables
$emailFromUrl = isset($_GET['email']) ? urldecode($_GET['email']) : '';
$nameFromUrl = isset($_GET['name']) ? urldecode($_GET['name']) : '';
$telFromUrl = isset($_GET['tel']) ? urldecode($_GET['tel']) : '';
$balanceFromUrl = isset($_GET['balance']) ? urldecode($_GET['balance']) : '';
$userData = array();

// Check if the form is submitted for updating user information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $newName = $_POST['newName'];
    $newTel = $_POST['newTel'];
    $newBalance = $_POST['newBalance'];
    $newPassword = $_POST['newPassword'];

    try {
        // Update user information
        $updateQuery = "UPDATE users SET name = ?, tel = ?, accountBalance = ? WHERE email = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param('ssds', $newName, $newTel, $newBalance, $emailFromUrl);
        $stmt->execute();

        // Update password if a new password is provided
        if (!empty($newPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updatePasswordQuery = "UPDATE users SET password = ? WHERE email = ?";
            $stmtPassword = $conn->prepare($updatePasswordQuery);
            $stmtPassword->bind_param('ss', $hashedPassword, $emailFromUrl);
            $stmtPassword->execute();
        }

        // Redirect to the same page with updated parameters
        header("Location: MyProfile.php?email=" . urlencode($emailFromUrl) . "&name=" . urlencode($newName) . "&tel=" . urlencode($newTel) . "&balance=" . urlencode($newBalance) . "&bankAccount=" . urlencode($bankAccountFromUrl));
        exit();
    } catch (Exception $e) {
        // Handle any exceptions
        $message = 'An error occurred: ' . $e->getMessage();
    } finally {
        // Close the database connection
        $conn->close();
    }
}

// Fetch user data from the database based on the entered email
$selectQuery = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($selectQuery);
$stmt->bind_param('s', $emailFromUrl);
$stmt->execute();

// Fetch user data
$userData = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Another Page</title>
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
        .nav-button {
            background-color: #4caf50;
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

        div {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin-bottom: 20px;
        }

        p {
            margin: 8px 0;
        }

        form {
            margin-top: 20px;
        }

        input, select {
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

        input[type="button"] {
            background-color: #f44336;
        }

        input[type="button"]:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>

<!-- Display user data if available in MyProfile.php -->
<div>
    <h2>User Information</h2>
    <p>Name: <?php echo htmlspecialchars($nameFromUrl); ?></p>
    <p>Email: <?php echo htmlspecialchars($emailFromUrl); ?></p>
    <p>Tel: <?php echo htmlspecialchars($telFromUrl); ?></p>
    <?php if ($userData): ?>
        <p>Balance: $<?php echo number_format($userData['accountBalance'], 2); ?></p>
    <?php else: ?>
        <p>Balance: Not available</p>
    <?php endif; ?>
</div>

<!-- Form to update user information -->
<form action="" method="post">
    <label for="newName">Update Name:</label>
    <input type="text" id="newName" name="newName" value="<?php echo htmlspecialchars($nameFromUrl); ?>" required>

    <label for="newTel">Update Telephone:</label>
    <input type="text" id="newTel" name="newTel" value="<?php echo htmlspecialchars($telFromUrl); ?>" required>

    <label for="newBalance">Update Balance:</label>
    <input type="text" id="newBalance" name="newBalance" value="<?php echo htmlspecialchars($userData['accountBalance']); ?>" required>

    <label for="newPassword">Update Password:</label>
    <input type="password" id="newPassword" name="newPassword">

    <input type="submit" name="update" value="Update">

   
</form>
<a href="PassengerHome.php" class="nav-button">Back to Passenger Home</a>

</body>
</html>
