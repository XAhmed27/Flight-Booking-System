<?php
require_once 'vendor/autoload.php';
require_once 'errorhandling.php';
require_once 'connection.php';


global $conn;

// Initialize variables
$email = $password = $message = '';
$companyData = array();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted email and password
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Check if a user with the provided email exists in the company table
        $getCompanyQuery = "SELECT u.name, u.email, u.tel, u.accountBalance, u.password , c.* FROM users u
        JOIN company c ON u.userID = c.userID
        WHERE u.email = ?";
        $stmt = $conn->prepare($getCompanyQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $companyResult = $stmt->get_result();

        if ($companyResult->num_rows === 0) {
            $message = 'Company not found';
        } else {
            // Fetch the user and company data
            $companyData = $companyResult->fetch_assoc();

            // Verify the password
            if (!empty($companyData['password']) && password_verify($password, $companyData['password'])) {
                // Log both entered password and stored password
                error_log('Entered Password: ' . $password);
                error_log('Stored Password: ' . $companyData['password']);

                // Password is correct, proceed with displaying company information
                header("Location: CompanyProfile.php?name=" . urlencode($companyData['name']) . "&bio=" . urlencode($companyData['bio']) . "&address=" . urlencode($companyData['address']) . "&username=" . urlencode($companyData['username']) . "&location=" . urlencode($companyData['location']) . "&tel=" . urlencode($companyData['tel']) . "&accountBalance=" . urlencode($companyData['accountBalance']));
                exit();
            } else {
                $message = 'Invalid email or password';
            }
        }
    } catch (Exception $e) {
        // Handle any exceptions
        $message = 'An error occurred: ' . $e->getMessage();
    } finally {
        // Close the database connection
        $conn = null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Authentication</title>
    <style>
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

        form {
            background: rgba(255, 255, 255, 0.5);
            font-size: 20px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin-bottom: 20px;
            text-align: center;
        }

        input,
        select {
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

        h2 {
            margin-right: 10px;
        }

        p {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>

<body>

<h2>Welcome to Company Login!</h2>

<!-- Display error message if any -->
<p><?php echo $message; ?></p>

<!-- Add a form to enter email and password -->
<form action="" method="post">
    <label for="email">Enter Email:</label>
    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

    <label for="password">Enter Password:</label>
    <input type="password" id="password" name="password" required>

    <input type="submit" value="Submit">
</form>

</body>

</html>