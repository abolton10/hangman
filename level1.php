<?php
session_start();

// Set the level to 1 if it's not set
$_SESSION['currentLevel'] = $_SESSION['currentLevel'] ?? 1;

// Check if the reset parameter is set in the URL and reset the session
if (isset($_GET['reset']) && $_GET['reset'] === 'true') {
    unset($_SESSION['word']);
    unset($_SESSION['guessed']);
    unset($_SESSION['attempts']);
    unset($_SESSION['isGameOver']);
    unset($_SESSION['currentscore']);
    $_SESSION['currentLevel'] = 1; // Set the level to 1 when resetting
    $_SESSION['wins'] = 1; // Reset the wins counter
    header('Location: level1.php');
    exit;
}

// Initialize the wins counter if it's not set
$_SESSION['wins'] = $_SESSION['wins'] ?? 1;

// Initialize the high score if it doesn't exist in the session
if (!isset($_SESSION['highscore'])) {
    $_SESSION['highscore'] = 0;
}

// Update the high score if the current score is higher
if ($currentScore > $_SESSION['highscore']) {
    $_SESSION['highscore'] = $currentScore;
}

function getRandomWordList($count = 3) {
    $filename = 'easy.txt';
    if (!file_exists($filename)) {
        echo "Word file not found!";
        exit;
    }
    $wordList = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Shuffle the word list and select the first $count words
    shuffle($wordList);
    return array_slice($wordList, 0, $count);
}

if (!isset($_SESSION['word'])) {
    $wordList = getRandomWordList();

    // Set the word for the current level
    $selectedWord = $wordList[$_SESSION['currentLevel'] - 1];

    $_SESSION['word'] = str_split(strtoupper($selectedWord));
    $_SESSION['guessed'] = [];
    $_SESSION['attempts'] = 6;
    $_SESSION['isGameOver'] = false;
}

// Process the guess
$shakingClass = ''; // Initialize the variable

if (isset($_POST['guess']) && !$_SESSION['isGameOver']) {
    $letter = strtoupper($_POST['guess']);
    if (!in_array($letter, $_SESSION['guessed'])) {
        array_push($_SESSION['guessed'], $letter);
        if (!in_array($letter, $_SESSION['word'])) {
            $_SESSION['attempts']--;
            $shakingClass = 'shake'; // Add this class for the shaking animation
        }
    }
}

// Check if the word is guessed
$wordGuessed = true;
foreach ($_SESSION['word'] as $letter) {
    if (!in_array($letter, $_SESSION['guessed'])) {
        $wordGuessed = false;
        break;
    }
}

if ($_SESSION['attempts'] === 0 || $wordGuessed) {
    $_SESSION['isGameOver'] = true;
    if ($_SESSION['attempts'] === 0) {
        // Play the boo.mp3 sound effect
        echo '<audio autoplay><source src="boo.mp3" type="audio/mpeg"></audio>';
    }
}

// Check for victory after completing level 3

function displayWord() {
    $display = '';
    foreach ($_SESSION['word'] as $letter) {
        if (in_array($letter, $_SESSION['guessed'])) {
            $display .= $letter;
        } else {
            $display .= '_';
        }
    }
    return $display;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hangman - Level <?php echo $_SESSION['wins']; ?></title>
    <link rel="stylesheet" type="text/css" href="project2.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><u>H_ngm_n</u></h1>
            <h2>Level <?php echo $_SESSION['wins']; ?> (Easy)</h2>
        </div>
        <div class="bg1 <?php echo $shakingClass; ?>">
            <?php
            if ($_SESSION['isGameOver'] && $wordGuessed) {
                echo '<img src="dancing.gif" class="item" alt="dancing Image">';
            } else {
                echo '<img src="images/' . (6 - $_SESSION['attempts']) . '.png" class="item" alt="Hangman Image">';
            }
            ?>
        </div>

        <?php
        if ($_SESSION['isGameOver']) {
            if ($wordGuessed) {
                echo "<h2>Congratulations! You guessed the word! Two more levels to go!</h2>";
                echo "<a href='index-hm.php'><button type='button'>Take me Home</button></a>";
                $_SESSION['currentscore'] += (30) + ($_SESSION['attempts'] * 10); //score increases by 30 for each level completetion and 10 points for every attempt left
                $_SESSION['currentLevel']++;
                $_SESSION['wins']++; // Increment the wins counter
                if ($_SESSION['currentLevel'] <= 3) {
                    // Reset the session for the next word
                    unset($_SESSION['word']);
                    unset($_SESSION['guessed']);
                    unset($_SESSION['attempts']);
                    unset($_SESSION['isGameOver']);
                    header("Location: level1.php");
                    exit;
                }
                if ($_SESSION['wins'] > 3) {
                    echo "<a href='level2.php'><button type='button'>Play Level 2</button></a>";
                    // Reset the session for a new game
                    unset($_SESSION['word']);
                    unset($_SESSION['guessed']);
                    unset($_SESSION['attempts']);
                    unset($_SESSION['isGameOver']);
                    $_SESSION['currentLevel'] = 1;
                    $_SESSION['wins'] = 1;
                    exit;
}
            } else {
                echo "<h2>Game Over</h2>";
                echo "<p>The word was: " . implode('', $_SESSION['word']) . "</p>";
                if ($_SESSION['wins'] == 1) {
                    echo "<a href='level1.php?reset=true'><button type='button'>Try Again</button></a>";
                } else {
                    echo "<a href='level1.php?reset=true'><button type='button'>Try Again</button></a>";
                }
            }
        } else {
            // Display the current level information
            echo "<h2>Word: " . displayWord() . "</h2>";
            echo "<p>Attempts left: " . $_SESSION['attempts'] . "</p>";
            echo "<p>Score: " . $_SESSION['currentscore'] . "</p>";
            echo "<form method='post' action=''>";
            echo "<label for='guess'>Guess a letter:</label>";
            echo "<input type='text' name='guess' maxlength='1' pattern='[A-Za-z]' required>";
            echo "<input type='submit' value='Submit'>";
            echo "</form>";
        }
        ?>

        <div class="guessed-container">
            <div class="letters-guessed">
                <p>Letters Used:</p>
                <div class="letters">
                    <?php
                    foreach ($_SESSION['guessed'] as $guessedLetter) {
                        echo "<span>$guessedLetter</span>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
