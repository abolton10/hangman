<?php session_start(); /* Starts the session */
	
	/* Check Login form submitted */	
	if(isset($_POST['Submit'])){

		/* Define username and associated highscore array */
        // Read the file into an array of lines
        $lines = file('Leaderboard.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Initialize an empty associative array to store username-highscore pairs
        $logins = array();

        // Process each line and split it into username and highscore
        foreach ($lines as $line) {
            // Assuming the username and highscore are separated by a comma
            list($username, $highscore) = explode(',', $line, 2);

            // Trim whitespace from the username and highscore
            $username = trim($username);
            $highscore = trim($highscore);

            // Store the username-highscore pair in the array
            $logins[$username] = $highscore;
    }
		
		/* Check and assign submitted username and highscore to new variable */
		$username = isset($_POST['name']) ? $_POST['name'] : '';
		$highscore = isset($_POST['highscore']) ? $_POST['highscore'] : '';
		

		/* Check username and highscore existence in defined array */		
		if (isset($logins[$username]) && $logins[$username] == $highscore){
			/* Success: Set session variables and redirect to Protected page  */
			$_SESSION['UserData']['username']=$logins[$username];
			header("location:index.php");
			exit;
		} else {
			/*Unsuccessful attempt: Set error message */
			$msg="<span style='color:red'>Invalid Login Details</span>";
		}
        
	}
?>
<html>
    <head>
        <title>Login</title>
    </head>
    <body>
        <h1>Login</h1>
        <form action="celebration.php" method="post"> 
                username: <input name="name" type="text"> 
                <p> 
                highscore: <input type="text" name="highscore" value="<?php echo $_SESSION['highscore']; ?>" readonly>
                <p>
                <input type="submit" value="Sign Up"> 
        </form>
    </body>
</html>
