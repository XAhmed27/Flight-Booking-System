<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Home</title>
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
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Style for the navigation buttons */
        .nav-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .nav-button {
            background-color: #4caf50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            font-size: 16px;
            width: 150px; /* Adjust the width as needed */
        }

        /* Hover effect for the navigation buttons */
        .nav-button:hover {
            background-color: #45a049;
        }

        /* Style for the forms (hidden by default) */
        .auth-form {
            display: none;
        }
    </style>
</head>
<body>

<h2>Passenger Home</h2>

<!-- Navigation buttons -->
<form action="auth.php" method="post" class="nav-buttons">
    <button type="submit" class="nav-button" name="name_button">Name</button>
    <button type="submit" class="nav-button" name="email_button">Email</button>
    <button type="submit" class="nav-button" name="tel_button">Tel</button>
    <a href="MyProfile.php" class="nav-button" style="width: 150px;">Profile</a>
    <!-- Add buttons for other pages as needed -->
</form>

<!-- Forms -->
<div id="name-form" class="auth-form">
    <h3>Name Authentication</h3>
    <form action="auth.php" method="post">
        <!-- Add name-specific form fields here -->
        <button type="submit" name="name_auth_button">Login</button>
    </form>
</div>

<div id="email-form" class="auth-form">
    <h3>Email Authentication</h3>
    <form action="auth.php" method="post">
        <!-- Add email-specific form fields here -->
        <button type="submit" name="email_auth_button">Login</button>
    </form>
</div>

<div id="tel-form" class="auth-form">
    <h3>Telephone Authentication</h3>
    <form action="auth.php" method="post">
        <!-- Add telephone-specific form fields here -->
        <button type="submit" name="tel_auth_button">Login</button>
    </form>
</div>

<!-- Add forms for other pages as needed -->

</body>
</html>
