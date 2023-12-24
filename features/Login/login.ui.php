<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-HcPzTq9ccBqylUouTkTYCElAgMnbQIlCQnOQxVlxeE0eLKOlBr35yB3gPZZ4nCC" crossorigin="anonymous">
    <script src="login.js" defer></script>


    <title>User Sign In</title>

</head>
<body>

<div class="form-container sign-in-container">
    <form action="#">
        <h1>Sign in</h1>
        <input type="email" placeholder="Email" />
        <input type="password" placeholder="Password" />
        <a href="#">Forgot your password?</a>
        <button>LOGIN IN</button>
    </form>
</div>

<div class="overlay-container">
    <div class="overlay">
        <img src="/assets/air3.jpg">
<!--        <div class="overlay-panel overlay-left">-->
<!--            <h1>Welcome back to our flight booking reservation</h1>-->
<!--            <p>To keep connected with us please login with your personal info</p>-->
<!--            <button class="ghost" id="signIn" onclick="window.location.href='../Login/login.ui.php'">LOGIN IN</button>-->
<!--        </div>-->

        <div class="overlay-panel overlay-right">
            <h1>Hello, Friend!</h1>
            <p>Enter your personal details and start journey with us</p>
            <button class="ghost" id="signUp" onclick="window.location.href='../Register/register.ui.php'">Register</button>
        </div>

    </div>
</div>


<div id="result">
    <?php /*if ($response['success']): */?>
        <p class="success"><?php /*echo $response['message']; */?></p>
        <?php /*if ($response['token'] !== null): */?>
            <p>Token: <?php /*echo $response['token']; */?></p>
        <?php /*endif; */?>
    <?php /*else: */?>
        <p class="error"><?php /*echo $response['message']; */?></p>
    <?php /*endif; */?>
</div>
<footer>
    <p>
        Created with <i class="fa fa-heart"></i> by
        <a target="_blank" href="https://florin-pop.com">Florin Pop</a>
        - Read how I created this and how you can join the challenge
        <a target="_blank" href="https://www.florin-pop.com/blog/2019/03/double-slider-sign-in-up-form/">here</a>.
    </p>
</footer>
</body>
</html>



    