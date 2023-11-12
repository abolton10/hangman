<?php
session_start();

// Check if the reset parameter is set in the URL and reset the session
if (isset($_GET['reset']) && $_GET['reset'] === 'true') {
    session_destroy();
    session_start();
}

// Set the level to 1
$_SESSION['currentLevel'] = 0; // Level 1

function getEasyWordList() {
    $filename = 'easy.txt';
    if (!file_exists($filename)) {
        echo "Word file not found!";
        exit;
    }

    return file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

if (!isset($_SESSION['word'])) {
    $wordList = getEasyWordList();
    $selectedWord = $wordList[array_rand($wordList)];
    $_SESSION['word'] = str_split(strtoupper($selectedWord));
    $_SESSION['guessed'] = [];
    $_SESSION['attempts'] = 6;
    $_SESSION['isGameOver'] = false;
}

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
    <title>Hangman - Level 1</title>
    <link rel="stylesheet" type="text/css" href="project2.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><u>H_ngm_n</u></h1>
            <h2>Level 1</h2>
        </div>

        <?php
        if ($_SESSION['isGameOver']) {
            if ($wordGuessed) {
                echo "<h2>Congratulations! You guessed the word!</h2>";
                echo '<a href="?next=true"><button type="button">Go to Level 2</button></a>';
            } else {
                echo "<h2>Game Over</h2>";
                echo "<p>The word was: " . implode('', $_SESSION['word']) . "</p>";
                echo '<a href="level1.php?reset=true"><button type="button">Try Again</button></a>';
            }
        } else {
            echo "<h2>Word: " . displayWord() . "</h2>";
            echo "<p>Attempts left: " . $_SESSION['attempts'] . "</p>";
            echo '<form method="post" action="">';
            echo '<label for="guess">Guess a letter:</label>';
            echo '<input type="text" name="guess" maxlength="1" pattern="[A-Za-z]" required>';
            echo '<input type="submit" value="Submit">';
            echo '</form>';
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
