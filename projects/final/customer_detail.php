<?php

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

// Load components
require_once(__DIR__ . './components/small_items.php');

$start = microtime(true);

// Input params
$page_title = "Customer Detail | Final Project";
$hide_page_title = true;
$hide_db_info = true;
$active_name = 'Customers';

// Get input from param
$input_data = getParam('id');
$error = checkNumericInput($input_data, 'Customer ID (Param Name: "id")', 1, null);

// Initialize values
$total_time = 0;
$row = [];
$order_list = [];

// If input is valid
if (empty($error)) {
    // Connect to DB
    $dbObject = getDBConnection();
    $mysqli = $dbObject -> mysqli;
    $error = $dbObject -> connection_error;

    // If successfully connected to DB
    if (empty($error)) {
        // Perform query
        $query = "SELECT * FROM customers WHERE CustomerID = ?";
        $row = simpleQueryFetch($mysqli, $query, $input_data, true, false);

        // If ID is not found
        if (empty($row)) {
            $error = ' • No customer was found for the input ID ' . $input_data;
        }
        // Get the list of orders associated with this employee
        else {
            $query = "
                    SELECT *
                    FROM orders
                        INNER JOIN employees ON employees.EmployeeID = orders.EmployeeID
                        INNER JOIN customers ON customers.CustomerID = orders.CustomerID
                        INNER JOIN shippers ON shippers.ShipperID = orders.ShipperID
                    WHERE orders.CustomerID = ?
                ";
            $order_list = simpleQueryFetch($mysqli, $query, $input_data, false, true);
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
    <p class="books-app-back-link"><a href="customer_list.php">< Go back to Customer List</a></p>

    <!-- Main Content -->
    <?php if (empty($error)): ?>
        <!-- Page Title -->
        <h1 class="books-app-title no-margin-top">Customer Detail</h1>

        <p class="books-app-text gray-text">What you need to know about your loyal customer</p>

        <!-- Customer Data -->
        <p class="books-app-text gray-text separate-link">ID: <span class="black-text"><?php print($row['CustomerID']) ?></span></p>
        <p class="books-app-text gray-text">Name: <span class="black-text"><?php print($row['CustomerName']) ?></span></p>
        <p class="books-app-text gray-text">Contact Name: <span class="black-text"><?php print($row['ContactName']) ?></span></p>
        <p class="books-app-text gray-text">Address: <span class="black-text"><?php print($row['Address']) ?></span></p>
        <p class="books-app-text gray-text">City: <span class="black-text"><?php print($row['City']) ?></span></p>
        <p class="books-app-text gray-text">Country: <span class="black-text"><?php print($row['Country']) ?></span></p>
        <p class="books-app-text gray-text">Postal Code: <span class="black-text"><?php print($row['PostalCode']) ?></span></p>

        <!-- Edit Button -->
        <?php renderAddButton('customer_edit.php?id=' . $input_data, 'Customer', 'Update', './assets/image_edit_color.png'); ?>

        <!-- List of Orders -->
        <h2 class="books-app-sub-title dark-blue-text separate-link">Orders placed by this customer (<?php print(count($order_list)) ?>)</h2>

        <div class="books-app-menu-container separate-link">
            <table>
                <!-- Header Row -->
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Serving Employee</th>
                    <th>Shipper Name</th>
                </tr>

                <!-- Data Rows -->
                <?php
                foreach ($order_list as $element) {
                    $order_id = $element['OrderID'];
                    $order_date = date_format(date_create($element['OrderDate']), 'M d, Y');
                    $employee_name = $element['FirstName'] . ' ' . $element['LastName'];
                    $shipper_name = $element['ShipperName'];

                    print("
                        <tr>
                            <td>$order_id</td>
                            <td>$order_date</td>
                            <td>$employee_name</td>
                            <td>$shipper_name</td>
                        </tr>
                    ");
                }
                ?>
            </table>
        </div>
    <?php endif; ?>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/form_template.php');