<?php

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

$start = microtime(true);

// Input params
$page_title = "Author Detail | Books App";
$hide_page_title = true;
$hide_db_info = true;
$active_name = 'Authors';

// Get input from param
$input_data = getParam('id');
$error = checkNumericInput($input_data, 'Author ID (Param Name: "id")', 1, null);

// Initialize values
$total_time = 0;
$row = [];
$book_list = [];

// If input is valid
if (empty($error)) {
    // Connect to DB
    $dbObject = getDBConnection();
    $mysqli = $dbObject -> mysqli;
    $error = $dbObject -> connection_error;

    // If successfully connected to DB
    if (empty($error)) {
        // Perform query
        $query = "SELECT * FROM authors WHERE author_id = ?";
        $row = simpleQueryFetch($mysqli, $query, $input_data, true, false);

        // If ID is not found
        if (empty($row)) {
            $error = ' • No book was found for the input ID ' . $input_data;
        }
        // Get the list of books
        else {
            $query = "SELECT books.* FROM wrote INNER JOIN books ON books.book_id = wrote.book_id WHERE wrote.author_id = ?";
            $book_list = simpleQueryFetch($mysqli, $query, $input_data, false, true);
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
    <p class="books-app-back-link"><a href="author_list.php">< Go back to Author List</a></p>

    <!-- Page Title -->
    <h1 class="books-app-title no-margin-top">Author Detail</h1>

    <p class="books-app-text gray-text">What you need to know about our author!</p>

    <!-- Book Data -->
    <p class="books-app-text gray-text separate-link">ID: <span class="black-text"><?php print($row['author_id']) ?></span></p>
    <p class="books-app-text gray-text">Name: <span class="black-text"><?php print($row['first_name'] . ' ' . $row['last_name']) ?></span></p>
    <p class="books-app-text gray-text">Country: <span class="black-text"><?php print($row['notes'] ?? '(None)') ?></span></p>

    <!-- List of Authors -->
    <h2 class="books-app-sub-title dark-blue-text separate-link">Books by this author</h2>

    <div class="books-app-menu-container">
        <?php
        foreach ($book_list as $element) {
            $book_id = $element['book_id'];
            $book_name = $element['title'];
            print("<p class='books-app-text books-app-menu'> • <a href='book_detail.php?id=$book_id'>$book_name</a></p>");
        }
        ?>
    </div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/page_template.php');