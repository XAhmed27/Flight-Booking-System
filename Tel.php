<?php
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit();
}

$userData = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Telephone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            background-image: url('assets/flyy.jpg');
            background-size: cover;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Style for the navigation buttons */
        .nav-button {
            background-color: #146C94;
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

        /* Hover effect for the navigation buttons */
        .nav-button:hover {
            background-color: #146C94;
        }
    </style>
</head>
<body>

<h2>User Telephone</h2>

<!-- Display user data -->
<p>Telephone: <?php echo htmlspecialchars($userData['tel']); ?></p>

<!-- Navigation button to go back to Passenger Home -->
<a href="PassengerHome.php" class="nav-button">Back to Passenger Home</a>

</body>
</html>