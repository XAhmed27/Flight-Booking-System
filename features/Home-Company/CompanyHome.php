<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Home</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-image: url('../../assets/air3.jpg');
            background-size: cover;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: grey; /* Change font color to grey */
            opacity: 1;
            font-size: 30px;
        }

        /* Style for the navigation bar */
        .navbar {
            background-color: transparent;
            padding: 10px;
            box-sizing: border-box;
            display: flex;
            justify-content: center; /* Center the items horizontally */
            position: fixed;
            top: 0;
            z-index: 1000;
            opacity: 0;
            animation: fadeIn 1s forwards;
            width: 100%;
        }

        /* Style for the navigation buttons */
        .nav-button {
            background-color: transparent;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            margin: 0 10px; /* Add margin between buttons */
        }

        /* Hover effect for the navigation buttons */
        .nav-button:hover {
            background-color: #146C94;
        }

        /* Keyframe animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Additional style for letter animation */
        .letter {
            display: inline-block;
            opacity: 0;
            transform: translateY(1em);
            animation: fadeInLetter 1s forwards, slideUp 0.5s forwards;
        }

        /* Keyframe animations for letter */
        @keyframes fadeInLetter {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Additional keyframe animation for sliding up */
        @keyframes slideUp {
            from {
                transform: translateY(1em);
            }
            to {
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

<?php
// Include the navigation bar using PHP
echo <<<HTML
    <!-- Navigation bar -->
    <div class="navbar">
        <a href="../../AddFlight.php" class="nav-button">Add Flight</a>
        <a href="../../FlightLists.php" class="nav-button">#Flights List</a>
        <a href="Profile.php" class="nav-button">Profile</a>
        <a href="Messages.php" class="nav-button">Messages</a>
    </div>
HTML;
?>

<!-- Welcome message -->
<h2 class="welcome-message">Welcome to Our Flight Booking System</h2>

<!-- The rest of your content goes here -->

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function () {
        // Split the sentence into letters
        var letters = $(".welcome-message").text().split("");
        // Clear the content of the h2 element
        $(".welcome-message").empty();

        // Add each letter in a span with a class for animation
        for (var i = 0; i < letters.length; i++) {
            if (letters[i] === ' ') {
                $(".welcome-message").append("<span>&nbsp;</span>"); // Add a non-breaking space
            } else {
                $(".welcome-message").append("<span class='letter'>" + letters[i] + "</span>");
            }
        }

        // Animate each letter
        $(".letter").each(function (index) {
            $(this).delay(100 * index).animate({ opacity: 1 }, 300);
        });

    });
</script>

</body>

</html>
