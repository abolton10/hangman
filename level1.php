<?php
session_start();

// Check if the reset parameter is set in the URL and reset the session
if (isset($_GET['reset']) && $_GET['reset'] === 'true') {
    unset($_SESSION['word']);
    unset($_SESSION['guessed']);
    unset($_SESSION['attempts']);
    unset($_SESSION['isGameOver']);
    $_SESSION['currentLevel'] = 0; // Set the level to 0 when resetting
    header('Location: level1.php');
    exit;
}

// Set the level to 1 if it's not set
$_SESSION['currentLevel'] = $_SESSION['currentLevel'] ?? 0;

echo "Current Level: " . $_SESSION['currentLevel'] . "<br>";

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
    <title>Hangman - Level <?php echo $_SESSION['currentLevel'] + 1; ?></title>
    <link rel="stylesheet" type="text/css" href="project2.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><u>H_ngm_n</u></h1>
            <h2>Level <?php echo $_SESSION['currentLevel'] + 1; ?> (Easy)</h2>
        </div>

        <?php
        if ($_SESSION['isGameOver']) {
            if ($wordGuessed && $_SESSION['attempts'] > 0) {
                echo "<h2>Congratulations! You guessed the word!</h2>";
                $nextLevel = $_SESSION['currentLevel'] + 1;
                if (isset($wordList[$nextLevel - 1])) {
                    // Reset the session for the next level
                    unset($_SESSION['word']);
                    unset($_SESSION['guessed']);
                    unset($_SESSION['attempts']);
                    unset($_SESSION['isGameOver']);
                    $_SESSION['currentLevel']++;
                    header("Location: level{$_SESSION['currentLevel']}.php");
                    exit;
                } else {
                    echo "<p>Congratulations! You've completed all levels!</p>";
                }
            } else {
                echo "<h2>Game Over</h2>";
                echo "<p>The word was: " . implode('', $_SESSION['word']) . "</p>";
                echo "<a href='?reset=true'><button type='button'>Try Again</button></a>";
            }
        } else {
            // Display the current level information
            echo "<h2>Word: " . displayWord() . "</h2>";
            echo "<p>Attempts left: " . $_SESSION['attempts'] . "</p>";
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
