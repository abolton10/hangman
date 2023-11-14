<?php

?>
<html>
    <head>
        <title>Register</title>
    </head>
    <body>
        <h1>Congratulations!</h1>

        <p>Save your score to the leaderboard by registering here</p>

        <form action="register-submit-easy.php" method="post"> 
                Username: <input name="name" type="text" id="name"> 
                <p> 
                Highscore: <input type="hidden" name="highscore" value="<?php echo $HighScore; ?>">
                <p>
                <input type="submit" value="Sign Up"> 
        </form>
        <p><a href="./login.php">Already Registered? Log in Here</a></p> 
    </body>
</html>