<?php
session_start();

// Set the level to 2
$_SESSION['currentLevel'] = 1; // Level 2 (since array index starts from 0)

// If the game is lost or reset, take back to index
if (isset($_GET['reset']) && $_GET['reset'] === 'true') {
    unset($_SESSION['word']);
    unset($_SESSION['guessed']);
    unset($_SESSION['attempts']);
    unset($_SESSION['isGameOver']);
    header('Location: index-hm.php');
    exit;
}

// Redirect to level3.php after completing level 2
if (isset($_GET['next']) && $_GET['next'] === 'true') {
    header('Location: level3.php');
    exit;
}

// Always use the medium word list
$gameMode = 'medium';

// Define the medium word list
$wordLists = [
    'medium' => ['word4', 'word5', 'word6'], // Replace with your actual word list for medium mode
];

$currentLevel = $_SESSION['currentLevel'] ?? 0;

if (!isset($wordLists[$gameMode][$currentLevel])) {
    echo "You've completed level 2!";
    exit;
}

// Select a word if not already set
if (!isset($_SESSION['word'])) {
    $wordList = $wordLists[$gameMode];
    $selectedWord = $wordList[$currentLevel];
    $_SESSION['word'] = str_split(strtoupper($selectedWord));
    $_SESSION['guessed'] = [];
    $_SESSION['attempts'] = 6;
    $_SESSION['isGameOver'] = false;
}

// Check if the word is guessed correctly or attempts are over
$wordGuessed = count(array_intersect($_SESSION['word'], $_SESSION['guessed'])) === count(array_unique($_SESSION['word']));
if ($_SESSION['attempts'] === 0 || $wordGuessed) {
    $_SESSION['isGameOver'] = true;
}

if (isset($_POST['guess']) && !$_SESSION['isGameOver']) {
    $letter = strtoupper($_POST['guess']);

    if (!in_array($letter, $_SESSION['guessed'])) {
        array_push($_SESSION['guessed'], $letter);

        if (!in_array($letter, $_SESSION['word'])) {
            $_SESSION['attempts']--;
        }
    }
}

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
    <title>Hangman - Level 2</title>
    <link rel="stylesheet" type="text/css" href="project2.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><u>H_ngm_n</u></h1>
            <h2>Level 2</h2>
        </div>

        <?php
        if ($_SESSION['isGameOver']) {
            if ($wordGuessed) {
                echo "<h2>Congratulations! You guessed the word!</h2>";
                echo '<a href="?next=true"><button type="button">Go to Level 3</button></a>';
            } else {
                echo "<h2>Game Over</h2>";
                echo "<p>The word was: " . implode('', $_SESSION['word']) . "</p>";
                echo '<a href="?reset=true"><button type="button">Try Again</button></a>';
            }
        } else {
            echo "<h2>Word: " . displayWord() . "</h2>";
            echo "<p>Attempts left: " . $_SESSION['attempts'] . "</p>";
        }
        ?>

        <form method="post" action="">
            <label for="guess">Guess a letter:</label>
            <input type="text" name="guess" maxlength="1" pattern="[A-Za-z]" required>
            <input type="submit" value="Submit">

            <?php if ($_SESSION['isGameOver'] && !$wordGuessed): ?>
                <a href="?reset=true"><button type="button">Try Again</button></a>
            <?php endif; ?>
        </form>

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
