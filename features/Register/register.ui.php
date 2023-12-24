<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="register.ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-HcPzTq9ccBqylUouTkTYCElAgMnbQIlCQnOQxVlxeE0eLKOlBr35yB3gPZZ4nCC" crossorigin="anonymous">
    <script src="register.ui.js" defer></script>

</head>

<body>

<div class="container" id="container">

    <div class="form-container sign-up-container">
        <form action="#">
            <h1>Create Account</h1>

            <input type="text" name="name" placeholder="Name" required/>
            <input type="text" name="tel"  placeholder="Phone" required />
            <input type="email" name="email" placeholder="Email" required/>
            <input type="text" name="accountBalance" placeholder="Account" required>
            <input type="password" name="password" placeholder="Password" required/>


            <div class="row" id = "typeSele">
                <label for="role">user:</label>
                <select id="role" user="user" >
                    <option value="Compnay">compnay</option>
                    <option value="Passenger">passenger</option>
                </select>
            </div>


            <p></p>

            <button >Register</button>
        </form>
    </div>

    <div class="form-container sign-in-container">
        <form action="#">
            <h1>LOGIN IN</h1>
            <input type="email" placeholder="Email" />
            <input type="password" placeholder="Password"/>
            <a href="#">Forgot your password?</a>
            <button>LOGIN IN</button>

        </form>
    </div>

    <div class="overlay-container">
        <div class="overlay">
            <img src="/assets/air3.jpg">
            <div class="overlay-panel overlay-left">
                <h1>Welcome back to our flight booking reservation</h1>
                <p>To keep connected with us please login with your personal info</p>
                <button class="ghost" id="signIn" onclick="window.location.href='../Login/login.ui.php'">LOGIN IN</button>
            </div>

            <div class="overlay-panel overlay-right">
                <h1>Hello, Friend!</h1>
                <p>Enter your personal details and start journey with us</p>
                <button class="ghost" id="signUp" >Register</button>
            </div>

        </div>
    </div>

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