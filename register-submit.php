<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input from the form
    $name = $_POST['name'];
    $highscore = $_POST['highscore'];

    // Create a line of user information
    $newUser = "$name,$highscore";

    // Append the new user information to the users.txt file
    file_put_contents("Leaderboard.txt", $newUser . PHP_EOL, FILE_APPEND);

    // Thank the user and provide a link to Login.php
    echo "<html>
    <head>
        <title>Thank You</title>
    </head>
    <body>
        <p>Thank you for signing up!</p>
        <p><a href='login.php'>Log in</a></p>
    </body>
    </html>";
} else {
    // If the request is not a POST request, display an error or redirect as needed
    echo "Invalid request method.";
}
?>
