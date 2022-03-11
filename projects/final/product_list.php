<?php

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

$start = microtime(true);

// Input params
$page_title = "Product List | Final Project";
$hide_page_title = true;
$hide_db_info = true;
$active_name = 'Products';

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
        $query = "SELECT ProductID, ProductName FROM products";
        $result_list = simpleQueryFetch($mysqli, $query, '', false, true);

        // If the product table is empty
        if (count($result_list) === 0) {
            $error = ' • No products was found in the database';
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
    <h1 class="books-app-title">This is the list of your products</h1>

    <p class="books-app-text gray-text">Click to see their information</p>

    <div class="books-app-menu-container">
        <?php
        foreach ($result_list as $element) {
            $product_id = $element['ProductID'];
            $product_name = $element['ProductName'];
            print("<p class='books-app-text books-app-menu'><a href='book_detail.php?id=$product_id'>> $product_name</a></p>");
        }
        ?>
    </div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/page_template.php');