<?php
session_start();

$wordGuessed = false;

// If the game is lost or reset, take back to index
if (isset($_GET['reset']) && $_GET['reset'] === 'true') {
    unset($_SESSION['word']);
    unset($_SESSION['guessed']);
    unset($_SESSION['attempts']);
    unset($_SESSION['isGameOver']);
    $_SESSION['currentLevel'] = 0; // Set the level to 0 when resetting
    header('Location: hangman-test.php');
    exit;
}

// Check if the current level is completed
if (isset($_GET['next']) && $_GET['next'] === 'true') {
    // Check if the word is guessed correctly before moving to the next level
    if (count(array_intersect($_SESSION['word'], $_SESSION['guessed'])) === count(array_unique($_SESSION['word']))) {
        $_SESSION['currentLevel'] = ($_SESSION['currentLevel'] ?? 0) + 1; // Increment the level
        $wordGuessed = true;
    } else {
        // Reset the current level if the word is guessed incorrectly
        $_SESSION['currentLevel'] = 0;
    }

    unset($_SESSION['word']);
    unset($_SESSION['guessed']);
    unset($_SESSION['attempts']);
    unset($_SESSION['isGameOver']);
    header('Location: hangman-test.php');
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

// Set the game mode (default to easy if not specified)
$gameMode = $_GET['mode'] ?? 'easy';

// Check if the game mode exists
if (!in_array($gameMode, ['easy', 'medium', 'hard'])) {
    echo "Invalid game mode!";
    exit;
}

// Check if the current level exists in the word lists
$currentLevel = $_SESSION['currentLevel'] ?? 0;
$wordLists = [
    'easy'   => ['easy', 'bags', 'money'], // Replace with your actual word list for easy mode
    'medium' => ['medium', 'headphone', 'ipad'], // Replace with your actual word list for medium mode
    'hard'   => ['hard', 'cousin', 'soap'], // Replace with your actual word list for hard mode
];

if (!isset($wordLists[$gameMode][$currentLevel])) {
    echo "You've completed all levels!";
    exit;
}

if (!isset($_SESSION['word'])) {
    $wordList = $wordLists[$gameMode];
    $_SESSION['word'] = str_split(strtoupper($wordList[$currentLevel]));
    $_SESSION['guessed'] = [];
    $_SESSION['attempts'] = 6;
    $_SESSION['isGameOver'] = false;
}

if ($_SESSION['attempts'] === 0 || count(array_intersect($_SESSION['word'], $_SESSION['guessed'])) === count(array_unique($_SESSION['word']))) {
    $_SESSION['isGameOver'] = true;
}

// Check if all letters are guessed correctly
if (isset($_POST['guess']) && !$_SESSION['isGameOver']) {
    $letter = strtoupper($_POST['guess']);

    if (!in_array($letter, $_SESSION['guessed'])) {
        array_push($_SESSION['guessed'], $letter);

        if (!in_array($letter, $_SESSION['word'])) {
            $_SESSION['attempts']--;
        }
    }

    if (count(array_intersect($_SESSION['word'], $_SESSION['guessed'])) === count(array_unique($_SESSION['word']))) {
        $_SESSION['isGameOver'] = true;
        $wordGuessed = true;
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hangman</title>
    <link rel="stylesheet" type="text/css" href="project2.css">
</head>
<body>
    <div class="container <?php echo ($wordGuessed) ? 'confetti' : ''; ?>">
        <div class="header">
            <h1><u>H_ngm_n</u></h1>
            <h2>Level <?php echo $currentLevel + 1; ?></h2>
        </div>

        <?php if ($_SESSION['isGameOver']): ?>
            <h2>Game Over</h2>
            <p>The word was: <?php echo implode('', $_SESSION['word']); ?></p>
            <!-- Single "Try Again" button that resets the current level -->
            <a href="?reset=true"><button type="button">Try Again</button></a>

            <!-- Check if there is another level to move to -->
            <?php if (isset($wordLists[$gameMode][$currentLevel + 1])): ?>
                <a href="?next=true"><button type="button">Next Level</button></a>
            <?php else: ?>
                You've completed all levels!
            <?php endif; ?>
        <?php else: ?>
            <h2>Word: <?php echo displayWord(); ?></h2>
            <p>Attempts left: <?php echo $_SESSION['attempts']; ?></p>
        <?php endif; ?>

        <form method="post" action="">
            <?php if (!$_SESSION['isGameOver']): ?>
                <label for="guess">Guess a letter:</label>
                <input type="text" name="guess" maxlength="1" pattern="[A-Za-z]" required>
                <input type="submit" value="Submit">
            <?php endif; ?>
        </form>

        <div class="hangman-image">
            <img src="images/<?php echo 6 - $_SESSION['attempts']; ?>.jpg" alt="Hangman Image">
        </div>

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
