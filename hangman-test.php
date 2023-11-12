<?php
session_start();

// If the game is lost, take back to index
if (isset($_GET['reset']) && $_GET['reset'] === 'true') {
    unset($_SESSION['word']);
    unset($_SESSION['guessed']);
    unset($_SESSION['attempts']);
    unset($_SESSION['isGameOver']);
    header('Location: index-hm.php');
    exit;
}

// Check if the current level is completed
if (isset($_GET['next']) && $_GET['next'] === 'true') {
    $_SESSION['currentLevel'] = ($_SESSION['currentLevel'] ?? 1) + 1;
    unset($_SESSION['word']);
    unset($_SESSION['guessed']);
    unset($_SESSION['attempts']);
    unset($_SESSION['isGameOver']);
    header("Location: {$_SERVER['PHP_SELF']}?mode=$gameMode");
    exit;
}

// Set the game mode (default to easy if not specified)
$gameMode = $_GET['mode'] ?? 'easy';

// Check if the game mode exists
if (!in_array($gameMode, ['easy', 'medium', 'hard'])) {
    echo "Invalid game mode!";
    exit;
}

// Get the word list based on the selected game mode
function getWordList($gameMode) {
    $filename = "{$gameMode}.txt";
    if (!file_exists($filename)) {
        echo "Word file not found!";
        exit;
    }

    return file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

// Check if the current level exists in the word lists
$currentLevel = $_SESSION['currentLevel'] ?? 0;
$wordList = getWordList($gameMode);

if (!isset($wordList[$_SESSION['currentLevel']])) {
    echo "You've completed all levels!";
    exit;
}

if (!isset($_SESSION['word'])) {
    $_SESSION['word'] = str_split(strtoupper($wordList[$_SESSION['currentLevel']]));
    $_SESSION['guessed'] = [];
    $_SESSION['attempts'] = 6;
    $_SESSION['isGameOver'] = false;
}

if ($_SESSION['attempts'] === 0 || count(array_intersect($_SESSION['word'], $_SESSION['guessed'])) === count(array_unique($_SESSION['word']))) {
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
    <title>Hangman</title>
    <link rel="stylesheet" type="text/css" href="project2.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><u>H_ngm_n</u></h1>
            <h2>Level <?php echo $_SESSION['currentLevel'] + 1; ?></h2>
        </div>

        <?php
        if ($_SESSION['isGameOver']) {
            echo "<h2>Game Over</h2>";
            echo "<p>The word was: " . implode('', $_SESSION['word']) . "</p>";
            echo '<a href="?reset=true"><button type="button">Try Again</button></a>';

            // Check if there is another level to move to
            if (isset($wordList[$_SESSION['currentLevel'] + 1])) {
                echo '<a href="?next=true"><button type="button">Next Level</button></a>';
            } else {
                echo "You've completed all levels!";
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

            <?php if ($_SESSION['isGameOver']): ?>
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


    </div>
</body>
</html>

