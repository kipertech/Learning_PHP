<?php
    // Load .env
    require_once('../../utils/util_dotenv.php');
    use KiperTech\DotEnv;
    (new DotEnv('../../.env'))->load();

    // Load questions
    require_once('./questions.php');

    // Connect to DB
    $start = microtime(true);
    $mysqli = new mysqli(
        $_SERVER['RDS_HOSTNAME'],
        $_SERVER['RDS_USERNAME'],
        $_SERVER['RDS_PASSWORD'],
        $_SERVER['RDS_DB_NAME'],
        $_SERVER['RDS_PORT']
    );

    // Check connection
    $connection_error = '';
    if ($mysqli -> connect_errno) {
        $connection_error = "Failed to connect to MySQL: " . $mysqli -> connect_error;
        return;
    }

    // Perform query
    $query = "SELECT * FROM wombats WHERE wombat_id=?";
    $id = 3;
    if ($stmt = $mysqli -> prepare($query)) {
        // Bind parameter
        $stmt->bind_param("i", $id);

        // Execute query
        $stmt -> execute();

        // Get record based on input ID
        $row = $stmt -> get_result() /* Return an array of data */ -> fetch_assoc() /* Sequentially fetch data in that array */;

        // Free result set
        $stmt -> free_result();
    }

    // Close connection
    $mysqli -> close();

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