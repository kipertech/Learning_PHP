<?php
    // Load helpers
    require_once('../../utils/util_params.php');
    require_once('../../utils/util_database.php');

    $start = microtime(true);

    // Initialize values
    $total_time = 0;
    $result_list = [];
    $error = '';

    // Get input from param
    $min_accuracy = getParam('min');
    $error = checkNumericInput($min_accuracy, 'Minimum Accuracy', 1, null);

    // If input is valid
    if (empty($error)) {
        // Connect to DB
        $dbObject = getDBConnection();
        $mysqli = $dbObject -> mysqli;
        $error = $dbObject -> connection_error;

        // If successfully connected to DB
        if (empty($error)) {
            // Perform query
            $query = "SELECT * FROM workouts WHERE accuracy >= ?";
            $result_list = simpleQueryFetch($mysqli, $query, $min_accuracy, false, true);

            // Log execution time
            $total_time = number_format(microtime(true) - $start, 2, '.', ',');
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>
        Supernatural Accuracy - Phat Pham
    </title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="index.css">
</head>

<body>

</body>
    <h1>Supernatural Accuracy</h1>

    <!-- Error Check -->
    <?php if ($error === ''): ?>
        <!-- Connection Info -->
        <div class="database-info-container">
            <p class="database-info">Database Host: <span class="purple-text"><?php print($_SERVER['RDS_HOSTNAME']) ?></span></p>
            <p>---</p>
            <p class="database-info">Input Accuracy: <span class="purple-text"><?php print($min_accuracy) ?></span></p>
            <p class="database-info">Result Count: <span class="purple-text"><?php print(count($result_list)) ?></span></p>
            <p class="database-info">Execution Time: <span class="purple-text"><?php print($total_time) ?> seconds</span></p>
        </div>

        <!-- Main Container -->
        <div class="main-container">
            <table>
                <!-- Header Row -->
                <tr>
                    <th>Workout ID</th>
                    <th>Goat Name</th>
                    <th>Accuracy</th>
                    <th>Power</th>
                </tr>

                <!-- Data Rows -->
                <?php
                    foreach ($result_list as $row) {
                        $id = $row['workout_id'];
                        $name = $row['name'];
                        $accuracy = $row['accuracy'];
                        $power = $row['power'];
                        print("
                            <tr>
                                <td>$id</td>
                                <td>$name</td>
                                <td>$accuracy</td>
                                <td>$power</td>
                            </tr>
                        ");
                    }
                ?>
            </table>
        </div>
    <?php else: ?>
        <div class="error-view">
            <p class="error-message bold-text">One or more errors have occurred:</p>
            <p class="error-message"><?php echo($error) ?></p>
        </div>
    <?php endif; ?>

    <!-- Footer Text -->
    <p class="footer-text">A masterpiece by Phat Pham.
        Made with ♥ in 2022.</p>
</html>