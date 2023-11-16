<?php
session_start();

// Set the level to 2
$_SESSION['currentLevel'] = 1; // Level 2

function getMediumWordList() {
    $filename = 'medium.txt';
    if (!file_exists($filename)) {
        echo "Word file not found!";
        exit;
    }

    return file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

if (!isset($_SESSION['word'])) {
    $wordList = getMediumWordList();
    $selectedWord = $wordList[array_rand($wordList)];
    $_SESSION['word'] = str_split(strtoupper($selectedWord));
    $_SESSION['guessed'] = [];
    $_SESSION['attempts'] = 6;
    $_SESSION['isGameOver'] = false;
}

// Process the guess
if (isset($_POST['guess']) && !$_SESSION['isGameOver']) {
    $letter = strtoupper($_POST['guess']);
    if (!in_array($letter, $_SESSION['guessed'])) {
        array_push($_SESSION['guessed'], $letter);
        if (!in_array($letter, $_SESSION['word'])) {
            $_SESSION['attempts']--;
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
                echo '<form method="post" action="level1.php"><input type="submit" value="Try Again"></form>';
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
