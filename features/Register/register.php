<?php
require_once '../../vendor/autoload.php';
require_once '../../errorhandling.php';
require_once '../../connection.php';
use \Firebase\JWT\JWT;

global $conn;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $name = $_POST['name'];
        $role = $_POST['role'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $tel = $_POST['tel'];
        $accountBalance = $_POST['accountBalance'];

        // Check if a user with the same email already exists
        $existingUserQuery = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($existingUserQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $existingUserResult = $stmt->get_result();

        if ($existingUserResult->num_rows > 0) {
            throw new Exception('User with the same email already exists', 400);
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $insertUserQuery = "INSERT INTO users (name, password, email, tel, accountBalance, role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertUserQuery);
        $stmt->bind_param("ssssss", $name, $hashedPassword, $email, $tel, $accountBalance, $role);
        $stmt->execute();

        // Get the last inserted user ID
        $newUserId = $stmt->insert_id;

        // Redirect to the corresponding form based on the role
        if ($role === 'passenger') {
            header("Location: ../Form-Info-Passenger/passenger_form2.php?userId=$newUserId");
            exit();
        } elseif ($role === 'company') {
            header("Location: ../Form-Info-Company/company_form2.php?userId=$newUserId");
            exit();
        }
    } catch (Exception $exception) {
        // Call handleGlobalError in case of an exception
        handleGlobalError($exception);
    }
}
?>

<!DOCTYPE html>
<!--<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
</head>
<body>

<form id="registrationForm" action="" method="post">
    Name: <input type="text" name="name" required><br>
    Role:
    <select name="role" id="role" required>
        <option value="passenger">Passenger</option>
        <option value="company">Company</option>
    </select><br>
    Password: <input type="password" name="password" required><br>
    Email: <input type="text" name="email" required><br>
    Tel: <input type="text" name="tel" required><br>
    Account Balance: <input type="text" name="accountBalance" required><br>

    <input type="submit" value="Submit">
</form>

</body>
</html>
-->