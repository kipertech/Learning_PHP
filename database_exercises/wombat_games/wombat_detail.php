<?php

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

$start = microtime(true);

// Input params
$page_title = "Wombat Detail | Wombat Games";
$hide_page_title = true;
$active_name = 'Wombats';
$data_source = 'Custom';
$data_source_url = 'https://webapps.skilling.us/node/394';

// Get input from param
$input_data = getParam('id');
$error = checkNumericInput($input_data, 'Wombat ID (Param Name: "id")', 1, null);

// Initialize values
$total_time = 0;
$row = [];
$game_list = [];

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
        $row = simpleQueryFetch($mysqli, $query, $input_data, true, false);

        // If ID is not found
        if (empty($row)) {
            $error = ' • No wombat was found for the input ID ' . $input_data;
        }
        // Get the list of games this wombat is playing
        else {
            $query = "SELECT wombat_games.* FROM wombat_plays INNER JOIN wombat_games ON wombat_plays.game_id = wombat_games.game_id WHERE wombat_plays.wombat_id = ?";
            $game_list = simpleQueryFetch($mysqli, $query, $input_data, false, true);
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
    <p class="books-app-back-link"><a href="index.php">< Go back to Wombat List</a></p>

    <!-- Page Title -->
    <h1 class="books-app-title no-margin-top">Wombat Detail</h1>

    <!-- Wombat Data -->
    <p class="books-app-text gray-text separate-link">ID: <span class="black-text"><?php print($row['wombat_id']) ?></span></p>
    <p class="books-app-text gray-text">Name: <span class="black-text"><?php print($row['name']) ?></span></p>
    <p class="books-app-text gray-text">Weight: <span class="black-text"><?php print($row['weight']) ?></span></p>
    <p class="books-app-text gray-text">Comments: <span class="black-text"><?php print($row['comments'] ?? '(None)') ?></span></p>

    <!-- List of Games -->
    <h2 class="books-app-sub-title dark-blue-text separate-link"><?php echo(count($game_list) === 0 ? 'It appears that this wombat is not playing any game!' : 'Games that this wombat is playing') ?></h2>

    <div class="books-app-menu-container">
        <?php
        foreach ($game_list as $element) {
            $game_id = $element['game_id'];
            $game_name = $element['name'];
            print("<p class='books-app-text books-app-menu'> • <a href='game_detail.php?id=$game_id'>$game_name</a></p>");
        }
        ?>
    </div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/page_template.php');