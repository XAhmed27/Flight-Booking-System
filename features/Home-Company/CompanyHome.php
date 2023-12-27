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
            color: #0C4160;
            opacity: 1;
            font-size: 30px;
        }

        .navbar {
            background-color: transparent;
            padding: 10px;
            box-sizing: border-box;
            display: flex;
            align-items: center; /* Align items vertically */
            justify-content: center; /* Center the items horizontally */
            position: fixed;
            top: 0;
            z-index: 1000;
            opacity: 0;
            animation: fadeIn 1s forwards;
            width: 100%;
        }

        .nav-button {
            background-color: transparent;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            margin: 0 10px;
        }

        .nav-button:hover {
            background-color: #146C94;
        }

        .company-name {
            margin-left: 10px; /* Add margin to separate the logo and company name */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
        .letter {
            display: inline-block;
            opacity: 0;
            transform: translateY(1em);
            animation: fadeInLetter 1s forwards, slideUp 0.5s forwards;
        }

        @keyframes fadeInLetter {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(1em);
            }

            to {
                transform: translateY(0);
            }
        }

        .img-circle-small {
            width: 53px;
            height: 55px;
            border-top-left-radius: 50% 50%;
            border-top-right-radius: 50% 50%;
            border-bottom-right-radius: 50% 50%;
            border-bottom-left-radius: 50% 50%;
            border: 2px solid #CCC;
            margin-bottom: 2px;
        }

        .status {
            width: 16px;
            height: 16px;
            border-top-left-radius: 50% 50%;
            border-top-right-radius: 50% 50%;
            border-bottom-right-radius: 50% 50%;
            border-bottom-left-radius: 50% 50%;
            border: 2px solid #CCC;
            margin-bottom: 2px;
            background-color: green;

            position: absolute;

        }

        .temp {
            position: fixed;
            top: 15px;
            left: 15px;
            display: inline-block;
        }

        .topRight {
            top: 0;
            right: 0;
        }
        
    </style>
</head>

<body>

    <?php
    require_once '../../vendor/autoload.php';
    require_once '../../errorhandling.php';
    require_once '../../connection.php';


    global $conn;
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    try {
        //* GET COMPANY LOGO AND USER ID.
        $companyID = $_COOKIE['id'];
        
        $getCompanyInfo = "SELECT logoImg, userID FROM company WHERE companyID=?";
        $stmtCompany = $conn->prepare($getCompanyInfo);
    
        if (!$stmtCompany) {
            throw new Exception("Error in preparing the statement: " . $conn->error);
        }
    
        $stmtCompany->bind_param("i", $companyID);
    
        if (!$stmtCompany->execute()) {
            throw new Exception("Error in executing the statement: " . $stmtCompany->error);
        }
    
        $stmtCompany->bind_result($logo, $userID);
        $stmtCompany->fetch();
        $stmtCompany->close();
    
        $getCompanyName = "SELECT name FROM users WHERE userID=?";
        $stmtUser = $conn->prepare($getCompanyName);
        
        if (!$stmtUser) {
            throw new Exception("Error in preparing the statement: " . $conn->error);
        }
    
        $stmtUser->bind_param("i", $userID);
    
        if (!$stmtUser->execute()) {
            throw new Exception("Error in executing the statement: " . $stmtUser->error);
        }
    
        $stmtUser->bind_result($CompanyName);
        $stmtUser->fetch();
        $stmtUser->close();
    } catch (Exception $e) {
        echo "Caught exception: " . $e->getMessage();
    }
    

    echo <<<HTML
 <!-- Navigation bar -->
<div class="navbar">
    <a href="../../AddFlight.php" class="nav-button">Add Flight</a>
    <a href="../../FlightLists.php" class="nav-button">#Flights List</a>
    <a href="../../CompanyProfile.php" class="nav-button">Profile</a>
    <a href="../../CompanyMessages.php" class="nav-button">Messages</a>
    <a href="../../PassengersFlightStatus.php" class="nav-button">Flight Status</a>
</div>
HTML;
    ?>

    <!-- Welcome message -->
    <h2 class="welcome-message"><span class="company-name">Welcome to our <?php echo $CompanyName?> Company</span>
</h2>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
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
            $(".letter").each(function(index) {
                $(this).delay(100 * index).animate({
                    opacity: 1
                }, 300);
            });

        });
    </script>
    <div class="temp">
        <img src="../../assets/<?php echo $logo; ?>" alt="avatar" class="img-circle-small">
        <span class="status topRight">&nbsp</span>
    </div>
</body>

</html>