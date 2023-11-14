<html>
    <head>
        <title>Register</title>
    </head>
    <body>
        <h1>Congratulations!</h1>

        <p>Save your score to the leaderboard by registering here</p>

        <form action="register-submit.php" method="post"> 
                Username: <input name="name" type="text"> 
                <p> 
                Highscore: <input type="text" name="highscore" value="<?php echo $HighScore; ?>" readonly>
                <p>
                <input type="submit" value="Sign Up"> 
        </form>
        <p><a href="./login.php">Already Registered? Log in Here</a></p> 
    </body>
</html>
