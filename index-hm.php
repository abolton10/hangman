<?php
session_start();

// Resets game session if requested
if (isset($_GET['reset']) && $_GET['reset'] === 'true') {
    session_unset();
}

// Redirect to the current level if it exists in the session
if (isset($_SESSION['currentLevel'])) {
    $currentLevel = $_SESSION['currentLevel'];
    if ($currentLevel > 0) {
        header("Location: level{$currentLevel}.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Hangman!</title>
    <link rel="stylesheet" type="text/css" href="project2.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>W_lcom_ to H_ngm_n</h1>
        </div>
        <p>Do you think you have what it takes to save Mr. Stick Man before it's too late?</p>
        <img src="stickman-hm1.png" class="index-image">

        <div class="difficulty-options">
            <p>Select Difficulty:</p>
            <ul>
                <li><a href="level1.php">Lightweight</a></li>
                <li><a href="level2.php">Hard Hitter</a></li>
                <li><a href="level3.php">Heavy Hitter</a></li>    
            </ul>
        </div>
    </div>
</body>
</html>

</html>
