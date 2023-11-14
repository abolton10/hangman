<html>
    <link rel="stylesheet" type="text/css" href="project2.css">
    <head>
        <title>Celebration</title>
    </head>
    <body>

        <div class="container">
            <h1>Congratulations! Thank you for playing H_ngm_n!</h2>
            <div class="wrapper">
                <div class="head"></div>
                <div class="torso"></div>
                <div class="leftarm"></div>
                <div class="rightarm"></div>
                <div class="leftleg"></div>
                <div class="leftfoot"></div>
                <div class="rightleg"></div>
                <div class="rightfoot"></div>
            </div> 
        </div>

        <p><a href='./register.php'>Register to save your spot on the leaderboard!</a></p>

        <?php
            // Read the leaderboard file into an array of lines
            $leaderboardData = file('Leaderboard.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            if ($leaderboardData !== false) {
                // Initialize an associative array to store username => highscore pairs
                $leaderboard = array();

                // Process each line and populate the leaderboard array
                foreach ($leaderboardData as $line) {
                    list($username, $highscore) = explode(',', $line, 2);
                    $leaderboard[$username] = (int)$highscore;
                }

                // Sort the leaderboard array based on high scores in descending order
                arsort($leaderboard);
            }
        ?>

        <h1>Leaderboard</h1>

        <?php 
            if (!empty($leaderboard)) : ?>
                <table class="leaderboard">
                    <thead class="leaderboard">
                        <tr class="leaderboard">
                            <th class="leaderboard">Rank</th>
                            <th>Username</th>
                            <th>High Score</th>
                        </tr>
                    </thead>
                    <tbody class="leaderboard">
                        <?php
                        $rank = 1;
                        foreach ($leaderboard as $username => $highscore) {
                            echo "<tr class='leaderboard'>";
                            echo "<td class='leaderboard'>{$rank}</td>";
                            echo "<td>{$username}</td>";
                            echo "<td>{$highscore}</td>";
                            echo "</tr>";
                            $rank++;
                        }
                        ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No data available in the leaderboard.</p>
            <?php endif; ?>
    </body>
</html>
