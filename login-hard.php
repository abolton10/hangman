<?php session_start(); /* Starts the session */
	
	/* Check Login form submitted */	
	if(isset($_POST['Submit'])){

		/* Define username and associated highscore array */
        // Read the file into an array of lines
        $lines = file('HardLeaderboard.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Initialize an empty associative array to store username-highscore pairs
        $logins = array();

        // Process each line and split it into username and highscore
        foreach ($lines as $line) {
            // Assuming the username and highscore are separated by a comma
            list($Username, $Highscore) = explode(',', $line, 2);

            // Trim whitespace from the username and highscore
            $Username = trim($Username);
            $Highscore = trim($Highscore);

            // Store the username-highscore pair in the array
            $logins[$Username] = $Highscore;
    }
		
		/* Check and assign submitted Username and Highscore to new variable */
		$Username = isset($_POST['Username']) ? $_POST['Username'] : '';
		$Highscore = isset($_POST['Highscore']) ? $_POST['Highscore'] : '';
		
		/* Check Username and Highscore existence in defined array */		
		if (isset($logins[$Username]) && $logins[$Username] == $Highscore){
			/* Success: Set session variables and redirect to Protected page  */
			$_SESSION['UserData']['Username']=$logins[$Username];
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
        <form action="signup-submit.php" method="post"> 
                Username: <input name="name" type="text" id="name"> 
                <p> 
                Highscore: <input name="name" type="text" id="name"> 
                <p> 
                <input type="submit" highscore="Sign Up"> 
        </form>
    </body>
</html>