<?php
    // Load helpers
    require_once(__DIR__ . '/../../utils/util_params.php');
    require_once(__DIR__ . '/../../utils/util_database.php');

    $start = microtime(true);

    // Initialize values
    $total_time = 0;
    $row = [];
    $error = '';

    // Get input from param
    $input_id = getParam('id');
    $error = checkNumericInput($input_id, 'Employee ID (Param Name: "id")', 1, null);

    // If input is valid
    if (empty($error)) {
        // Connect to DB
        $dbObject = getDBConnection();
        $mysqli = $dbObject -> mysqli;
        $error = $dbObject -> connection_error;

        // If successfully connected to DB
        if (empty($error)) {
            // Perform query
            $query = "SELECT * FROM employees WHERE EmployeeID = ?";
            $row = simpleQueryFetch($mysqli, $query, $input_id, true, true);

            // If ID is not found
            if (empty($row)) {
                $error = ' • No result was found for the input ID: ' . $input_id;
            }

            // Log execution time
            $total_time = number_format(microtime(true) - $start, 2, '.', ',');
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>
        ID From GET - Phat Pham
    </title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="../style_general.css">
</head>

<body>
    <h1>ID From GET</h1>

    <!-- Error Check -->
    <?php if ($error === ''): ?>
        <!-- Connection Info -->
        <div class="database-info-container">
            <p class="database-info">Database Host: <span class="purple-text"><?php print($_SERVER['RDS_HOSTNAME']) ?></span></p>
            <p>---</p>
            <p class="database-info">Input ID: <span class="purple-text"><?php print($input_id) ?></span></p>
            <p class="database-info">Execution Time: <span class="purple-text"><?php print($total_time) ?> seconds</span></p>
        </div>

        <!-- Main Container -->
        <div class="main-container">
            <h3>Employee Detail</h3>
            <p class="gray-text">Employee ID: <span class="black-text"><?php print($row['EmployeeID']) ?></span></p>
            <p class="gray-text">First Name: <span class="black-text"><?php print($row['FirstName']) ?></span></p>
            <p class="gray-text">Last Name: <span class="black-text"><?php print($row['LastName']) ?></span></p>
            <p class="gray-text">Birthday: <span class="black-text"><?php print(date_format(date_create($row['BirthDate']), 'M d, Y')) ?></span></p>
            <p class="gray-text">Notes:</p>
            <p class="black-text"><?php print($row['Notes']) ?></p>
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
</body>
</html>