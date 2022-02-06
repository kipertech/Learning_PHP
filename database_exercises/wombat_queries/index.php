<?php
    $start = microtime(true);

    // Load questions
    require_once(__DIR__ . '/questions.php');

    // Log execution time
    $total_time = number_format(microtime(true) - $start, 2, '.', ',');
?>

<!DOCTYPE html>
<html>
<head>
    <title>
        Wombat Queries - Phat Pham
    </title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="index.css">
</head>

<body>

</body>
    <h1>Wombat Queries</h1>

    <!-- Connection Info -->
    <div class="database-info-container">
        <p class="database-info">Database Host: <span class="purple-text"><?php print($_SERVER['RDS_HOSTNAME']) ?></span></p>
        <p class="database-info">Total Execution Time: <span class="purple-text"><?php print($total_time) ?> seconds</span></p>
        <p class="database-info">DBMS: <span class="purple-text">PHPStorm's Built-in</p>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <!-- List all questions -->
        <?php
            $question_list = getQuestions();
            foreach ($question_list as $element) {
                print("
                    <div class='question-container'>
                        <h4 class='green-text'>Question #$element->id</h4>
                        <p class='question-content'>$element->content</p>
                        <p class='code-text blue-text'>$element->query</p>
                        <img class='screenshot-image' src=$element->screenshot_url alt='img_$element->id'/>
                    </div>
                ");
            }
        ?>
    </div>

    <!-- Footer Text -->
    <p class="footer-text">A masterpiece by Phat Pham.
        Made with ♥ in 2022.</p>
</html>