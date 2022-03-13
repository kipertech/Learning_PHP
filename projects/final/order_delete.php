<?php

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

// Load components
require_once(__DIR__ . '/components/small_items.php');

$start = microtime(true);

// Input params
$page_title = "Order Detail | Final Project";
$hide_page_title = true;
$hide_db_info = true;
$active_name = 'Orders';

// Get input from param
$input_data = getParam('id');
$error = checkNumericInput($input_data, 'Order ID (Param Name: "id")', 1, null);

// Initialize values
$total_time = 0;
$row = [];
$order_detail = [];
$product_list = [];

// If input is valid
if (empty($error)) {
    // Connect to DB
    $dbObject = getDBConnection();
    $mysqli = $dbObject -> mysqli;
    $error = $dbObject -> connection_error;

    // If successfully connected to DB
    if (empty($error)) {
        // Perform query
        $query = "
            SELECT *
            FROM orders o
                INNER JOIN employees ON employees.EmployeeID = o.EmployeeID
                INNER JOIN customers ON customers.CustomerID = o.CustomerID
                INNER JOIN shippers ON shippers.ShipperID = o.ShipperID
            WHERE o.OrderID = ?
        ";
        $row = simpleQueryFetch($mysqli, $query, $input_data, true, false);

        // If ID is not found
        if (empty($row)) {
            $error = ' • No order was found for the input ID ' . $input_data;
        }
        // Get the list of products associated with this order
        else {
            $query = "SELECT * FROM order_details WHERE order_details.OrderID = ?";
            $product_list = simpleQueryFetch($mysqli, $query, $input_data, false, true);
        }

        // Log execution time
        $total_time = number_format(microtime(true) - $start, 2, '.', ',');
    }

    // Check if POST Delete action is submitted
    if (isset($_POST['cmdDelete'])) {
        // Connect to DB
        $dbObject = getDBConnection();
        $mysqli = $dbObject -> mysqli;
        $error = $dbObject -> connection_error;

        // Perform update query
        $query = "DELETE FROM order_details WHERE OrderID = " . $input_data;
        $update_error = simpleQueryUpdate($mysqli, $query, false);

        $query = "DELETE FROM orders WHERE OrderID = " . $input_data;
        $update_error = simpleQueryUpdate($mysqli, $query, true);

        // Check if there is error
        if ($update_error !== '') {
            $error = 'Failed to delete order. Error message:' . "\n" . '"' . $update_error . '"';
        }
        else {
            echo('<script>alert("Order removed, muhahaha!")</script>');
            header('Location: order_list.php');
            exit();
        }
    }
}

// Define HTML Body
ob_start();

// Nav Bar
require_once(__DIR__ . '/components/nav_bar.php');

?>
    <!-- Back Link -->
    <p class="books-app-back-link"><a href="order_list.php">< Go back to Order List</a></p>

    <!-- Page Title -->
    <h1 class="books-app-title no-margin-top">Delete Confirmation</h1>

    <p class="books-app-text gray-text">Are you sure you want to delete this order?</p>

    <!-- Delete Button -->
    <form method="post">
        <button type="submit" name="cmdDelete" class="delete-button">Confirm Delete Order</button>
    </form>

    <!-- Order Data -->
    <p class="books-app-text gray-text separate-link">ID: <span class="black-text"><?php print($row['OrderID']) ?></span></p>
    <p class="books-app-text gray-text">Date: <span class="black-text"><?php print(date_format(date_create($row['OrderDate']), 'M d, Y')) ?></span></p>
    <p class="books-app-text gray-text">Placed By: <span class="black-text"><a href="<?php print('customer_detail.php?id='.$row['CustomerID']) ?>" target='_blank' rel='noopener noreferrer'><?php print($row['CustomerName']) ?></a></span></p>
    <p class="books-app-text gray-text">Served By: <span class="black-text"><a href="<?php print('employee_detail.php?id='.$row['EmployeeID']) ?>" target='_blank' rel='noopener noreferrer'><?php print($row['FirstName'].' '.$row['LastName']) ?></a></span></p>
    <p class="books-app-text gray-text">Shipper: <span class="black-text"><?php print($row['ShipperName']) ?></span></p>

    <!-- Product Count -->
    <h2 class="books-app-sub-title dark-blue-text separate-link">This order contains (<?php print(count($product_list)) ?>) products</h2>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/form_template.php');