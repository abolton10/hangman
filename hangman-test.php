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
            <h2>Level <?php echo $currentLevel + 1; ?></h2>
        </div>

        <?php
        if ($_SESSION['isGameOver']) {
            echo "<h2>Game Over</h2>";
            echo "<p>The word was: " . implode('', $_SESSION['word']) . "</p>";
            echo '<a href="?reset=true"><button type="button">Try Again</button></a>';

            // Check if there is another level to move to
            if (isset($wordLists[$gameMode][$currentLevel + 1])) {
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

