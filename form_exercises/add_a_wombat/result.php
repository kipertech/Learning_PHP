<?php

session_start();

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

$start = microtime(true);

// Input params
$page_title = "Result | Add a Wombat";
$back_link = 'index.php';
$data_source = 'Custom';
$data_source_url = 'https://webapps.skilling.us/node/393';

// Get input from param
$error = empty($_SESSION['name']) ? 'No input was found, please use the input form.' : '';
$input_name = $_SESSION['name'];
$input_weight = !empty($_SESSION['weight']) ? $_SESSION['weight'] : 'NULL';
$input_comment = $_SESSION['comment'];

// Add to DB
if ($error === '') {
    $inserted_id = null;

    // Connect to DB
    $dbObject = getDBConnection();
    $mysqli = $dbObject -> mysqli;
    $error = $dbObject -> connection_error;

    // If successfully connected to DB
    if (empty($error)) {
        // Check if wombat's name already exists
        $query = "SELECT * FROM wombats WHERE name = ?";
        $wombat_result = simpleQueryFetch($mysqli, $query, $input_name, true, false, true);

        if ($wombat_result) {
            $error = 'This wombat name ("' . $input_name . '") already exists in the database, please choose another name.';
            $mysqli -> close();
        }
        else {
            // Perform query
            $query_comment = str_replace("'", "\'", $input_comment);
            $query = "INSERT INTO wombats (name, weight, comments, added_externally) VALUES ('".$input_name."', ".$input_weight.", '".$query_comment."', 1)";
            $result_obj = simpleQueryInsert($mysqli, $query, true);

            // Check if there is error
            $inserted_id = $result_obj -> last_id;
            $insert_error = $result_obj -> error;
            if ($insert_error !== '') {
                $error = 'Failed to add new wombat. Error message:' . "\n" . '"' . $insert_error . '"';
            }
            // Clear input
            else {
                $_SESSION['name'] = '';
                $_SESSION['weight'] = '';
                $_SESSION['comment'] = '';
            }
        }

        // Log execution time
        $total_time = number_format(microtime(true) - $start, 2, '.', ',');
    }
}

// Define HTML Body
ob_start(); ?>

    <!-- Result -->
    <p class="gray-text separate-link">ID: <span class="black-text"><?php print($inserted_id ?? '(Unknown)') ?></span></p>
    <p class="gray-text">Name: <span class="black-text"><?php print($input_name) ?></span></p>
    <p class="gray-text">Weight: <span class="black-text"><?php print($input_weight ?? '(Unknown)') ?></span></p>
    <p class="gray-text">Comment: <span class="black-text"><?php print($input_comment !== '' ? $input_comment : '(None)') ?></span></p>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/page_template.php');