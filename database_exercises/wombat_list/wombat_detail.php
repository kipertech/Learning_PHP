<?php

$page_title = "Wombat Detail";

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

$start = microtime(true);

// Initialize values
$total_time = 0;
$row = [];
$error = '';

// Get input from param
$input_data = getParam('id');
$error = checkNumericInput($input_data, 'Wombat ID (Param Name: "id")', 1, null);

// If input is valid
if (empty($error)) {
    // Connect to DB
    $dbObject = getDBConnection();
    $mysqli = $dbObject -> mysqli;
    $error = $dbObject -> connection_error;

    // If successfully connected to DB
    if (empty($error)) {
        // Perform query
        $query = "SELECT * FROM wombats WHERE wombat_id = ?";
        $row = simpleQueryFetch($mysqli, $query, $input_data, true, true);

        // If ID is not found
        if (empty($row)) {
            $error = ' • No wombat was found in the database with ID ' . $input_data;
        }

        // Log execution time
        $total_time = number_format(microtime(true) - $start, 2, '.', ',');
    }
}

// Define HTML Body
ob_start(); ?>

<p class="gray-text">Wombat ID: <span class="black-text"><?php print($row['wombat_id']) ?></span></p>
<p class="gray-text">Name: <span class="black-text"><?php print($row['name']) ?></span></p>
<p class="gray-text">Weight: <span class="black-text"><?php print($row['weight'] ?? '(Unknown)') ?></span></p>
<p class="gray-text">Comments: <span class="black-text"><?php print($row['comments'] ?? '(None)') ?></span></p>

<p class="separate-link"><a href="index.php">Go back to Wombat List</a></p>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/page_template.php');