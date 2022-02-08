<?php

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

$start = microtime(true);

// Input params
$page_title = "Game List | Wombat Games";
$hide_page_title = true;
$active_name = 'Games';
$data_source = 'Custom';
$data_source_url = 'https://webapps.skilling.us/node/394';

// Initialize values
$total_time = 0;
$result_list = [];
$error = '';

// If input is valid
if (empty($error)) {
    // Connect to DB
    $dbObject = getDBConnection();
    $mysqli = $dbObject -> mysqli;
    $error = $dbObject -> connection_error;

    // If successfully connected to DB
    if (empty($error)) {
        // Perform query
        $query = "SELECT * FROM wombat_games";
        $result_list = simpleQueryFetch($mysqli, $query, '', false, true);

        // If ID is not found
        if (count($result_list) === 0) {
            $error = ' • No game was found in the database';
        }

        // Log execution time
        $total_time = number_format(microtime(true) - $start, 2, '.', ',');
    }
}

// Define HTML Body
ob_start();

// Nav Bar
require_once(__DIR__ . '/components/nav_bar.php');

?>
    <!-- Page Title -->
    <h1 class="books-app-title">Game List</h1>

    <div class="books-app-menu-container">
        <?php
        foreach ($result_list as $element) {
            $game_id = $element['game_id'];
            $game_name = $element['name'];
            print("<p class='books-app-text books-app-menu'><a href='game_detail.php?id=$game_id'>> $game_name</a></p>");
        }
        ?>
    </div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/page_template.php');