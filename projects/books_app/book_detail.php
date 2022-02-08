<?php

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

$start = microtime(true);

// Input params
$page_title = "Book Detail | Books App";
$hide_page_title = true;
$hide_db_info = true;
$active_name = 'Books';

// Get input from param
$input_data = getParam('id');
$error = checkNumericInput($input_data, 'Book ID (Param Name: "id")', 1, null);

// Initialize values
$total_time = 0;
$row = [];
$author_list = [];

// If input is valid
if (empty($error)) {
    // Connect to DB
    $dbObject = getDBConnection();
    $mysqli = $dbObject -> mysqli;
    $error = $dbObject -> connection_error;

    // If successfully connected to DB
    if (empty($error)) {
        // Perform query
        $query = "SELECT * FROM books WHERE book_id = ?";
        $row = simpleQueryFetch($mysqli, $query, $input_data, true, false);

        // If ID is not found
        if (empty($row)) {
            $error = ' • No book was found for the input ID ' . $input_data;
        }
        // Get the list of authors
        else {
            $query = "SELECT authors.* FROM wrote INNER JOIN authors ON authors.author_id = wrote.author_id WHERE wrote.book_id = ?";
            $author_list = simpleQueryFetch($mysqli, $query, $input_data, false, true);
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
    <p class="books-app-back-link"><a href="book_list.php">< Go back to Book List</a></p>

    <!-- Page Title -->
    <h1 class="books-app-title no-margin-top">Book Detail</h1>

    <p class="books-app-text gray-text">Just the key info... you have to buy it to read it, sorry :(</p>

    <!-- Book Data -->
    <p class="books-app-text gray-text separate-link">ID: <span class="black-text"><?php print($row['book_id']) ?></span></p>
    <p class="books-app-text gray-text">Name: <span class="black-text"><?php print($row['title']) ?></span></p>
    <p class="books-app-text gray-text">Notes: <span class="black-text"><?php print($row['notes'] ?? '(None)') ?></span></p>

    <!-- List of Authors -->
    <h2 class="books-app-sub-title dark-blue-text separate-link">Authors</h2>

    <div class="books-app-menu-container">
        <?php
            foreach ($author_list as $element) {
                $author_id = $element['author_id'];
                $author_name = $element['first_name'] . ' ' . $element['last_name'];
                print("<p class='books-app-text books-app-menu'> • <a href='author_detail.php?id=$author_id'>$author_name</a></p>");
            }
        ?>
    </div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/page_template.php');