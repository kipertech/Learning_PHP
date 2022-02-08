<?php

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

$start = microtime(true);

// Input params
$page_title = "Book List | Books App";
$hide_page_title = true;
$hide_db_info = true;
$active_name = 'Books';

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
        $query = "SELECT * FROM books";
        $result_list = simpleQueryFetch($mysqli, $query, '', false, true);

        // If ID is not found
        if (count($result_list) === 0) {
            $error = ' • No book was found in the database';
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
    <h1 class="books-app-title">Just a list of books...</h1>

    <p class="books-app-text gray-text">...but nonetheless, pick one!!!</p>

    <div class="books-app-menu-container">
        <?php
            foreach ($result_list as $element) {
                $book_id = $element['book_id'];
                $book_name = $element['title'];
                print("<p class='books-app-text books-app-menu'><a href='book_detail.php?id=$book_id'>> $book_name</a></p>");
            }
        ?>
    </div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/page_template.php');