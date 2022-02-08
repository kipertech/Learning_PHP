<?php

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

$start = microtime(true);

// Input params
$page_title = "Game Detail | Wombat Games";
$hide_page_title = true;
$active_name = 'Games';
$data_source = 'Custom';
$data_source_url = 'https://webapps.skilling.us/node/394';

// Get input from param
$input_data = getParam('id');
$error = checkNumericInput($input_data, 'Game ID (Param Name: "id")', 1, null);

// Initialize values
$total_time = 0;
$row = [];
$wombat_list = [];

// If input is valid
if (empty($error)) {
    // Connect to DB
    $dbObject = getDBConnection();
    $mysqli = $dbObject -> mysqli;
    $error = $dbObject -> connection_error;

    // If successfully connected to DB
    if (empty($error)) {
        // Perform query
        $query = "SELECT * FROM wombat_games WHERE game_id = ?";
        $row = simpleQueryFetch($mysqli, $query, $input_data, true, false);

        // If ID is not found
        if (empty($row)) {
            $error = ' • No game was found for the input ID ' . $input_data;
        }
        // Get the list of wombats playing this game
        else {
            $query = "SELECT wombats.* FROM wombat_plays INNER JOIN wombats ON wombat_plays.wombat_id = wombats.wombat_id WHERE wombat_plays.game_id = ?";
            $wombat_list = simpleQueryFetch($mysqli, $query, $input_data, false, true);
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
    <!-- Back Link -->
    <p class="books-app-back-link"><a href="game_list.php">< Go back to Game List</a></p>

    <!-- Page Title -->
    <h1 class="books-app-title no-margin-top">Game Detail</h1>

    <!-- Game Data -->
    <p class="books-app-text gray-text separate-link">ID: <span class="black-text"><?php print($row['game_id']) ?></span></p>
    <p class="books-app-text gray-text">Name: <span class="black-text"><?php print($row['name']) ?></span></p>

    <!-- List of Wombats -->
    <h2 class="books-app-sub-title dark-blue-text separate-link"><?php echo(count($wombat_list) === 0 ? 'It appears that no wombat is playing this game!' : 'Wombats playing this game') ?></h2>

    <div class="books-app-menu-container">
        <?php
            foreach ($wombat_list as $element) {
                $wombat_id = $element['wombat_id'];
                $wombat_name = $element['name'];
                print("<p class='books-app-text books-app-menu'> • <a href='wombat_detail.php?id=$wombat_id'>$wombat_name</a></p>");
            }
        ?>
    </div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/page_template.php');