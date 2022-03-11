<?php

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

$start = microtime(true);

// Input params
$page_title = "Customer List | Final Project";
$hide_page_title = true;
$hide_db_info = true;
$active_name = 'Customers';

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
        $query = "SELECT * FROM customers";
        $result_list = simpleQueryFetch($mysqli, $query, '', false, true);

        // If the employee table is empty
        if (count($result_list) === 0) {
            $error = ' • No customer was found in the database';
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
    <h1 class="books-app-title">This is the list of your loyal customers</h1>

    <p class="books-app-text gray-text">Click to see their information</p>

    <div class="books-app-menu-container">
        <?php
        foreach ($result_list as $element) {
            $customer_id = $element['CustomerID'];
            $customer_name = $element['CustomerName'];
            print("<p class='books-app-text books-app-menu'><a href='book_detail.php?id=$customer_id'>> $customer_name</a></p>");
        }
        ?>
    </div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/page_template.php');