<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center; /* Center content vertically */
            height: 100vh;
            background-image: url('assets/air3.jpg'); /* Replace with your background image path */
            background-size: cover;
        }

        .navbar {
            background-color: transparent;
            padding: 10px;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        h2 {

            text-align: center;
            margin-top: 60px; /* Adjust margin as needed */
            margin-bottom: 20px;
            color:slategrey ; /* Text color */
        }

        .nav-buttons {
            display: flex;
            gap: 10px;
        }

        .nav-button {
            background-color: transparent;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            font-size: 16px;
            width: 150px;
        }

        .nav-button:hover {
            background-color: #146C94;
        }

        .auth-form {
            display: none;
        }
    </style>
</head>

<body>

<!-- Navigation bar -->
<div class="navbar">
    <a href="auth.php" class="nav-button" name="name_button">Name</a>
    <a href="auth.php" class="nav-button" name="email_button">Email</a>
    <a href="auth.php" class="nav-button" name="tel_button">Tel</a>
    <!--<button type="submit" class="nav-button" name="email_button">Email</button>
    <button type="submit" class="nav-button" name="tel_button">Tel</button>-->
    <a href="MyProfile.php" class="nav-button">Profile</a>
    <a href="SearchFlight.php" class="nav-button">SearchFlight</a>
    <a href="MyFlight.php" class="nav-button">Current Flight</a>
</div>

<h2>Passenger Home</h2>

<!-- Forms -->
<div id="name-form" class="auth-form">
    <h3>Name Authentication</h3>
    <form action="auth.php" method="post">
        <button type="submit" name="name_auth_button">Login</button>
    </form>
</div>

<div id="email-form" class="auth-form">
    <h3>Email Authentication</h3>
    <form action="auth.php" method="post">
        <button type="submit" name="email_auth_button">Login</button>
    </form>
</div>

<div id="tel-form" class="auth-form">
    <h3>Telephone Authentication</h3>
    <form action="auth.php" method="post">
        <button type="submit" name="tel_auth_button">Login</button>
    </form>
</div>

</body>

</html>
