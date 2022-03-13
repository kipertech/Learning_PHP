<?php

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

// Load components
require_once(__DIR__ . './components/small_items.php');

$start = microtime(true);

// Input params
$page_title = "Order List | Final Project";
$hide_page_title = true;
$hide_db_info = true;
$active_name = 'Orders';

// Initialize values
$current_page = (int)(getParam('current_page') ?? 1);
$per_page = 30;
$previous_page_id = ((($current_page > 0 ? $current_page : 1) - 1) * (int)$per_page) + 10247 /* Larger than the first OrderID */;
$max_page = 1;
$total_time = 0;
$result_list = [];
$total_result_list = [];
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
        $query = "
            SELECT *
            FROM orders
                INNER JOIN employees ON employees.EmployeeID = orders.EmployeeID
                INNER JOIN customers ON customers.CustomerID = orders.CustomerID
                INNER JOIN shippers ON shippers.ShipperID = orders.ShipperID
            WHERE (OrderID > ".$previous_page_id.")
            ORDER BY OrderID
            LIMIT ".$per_page
        ;
        $result_list = simpleQueryFetch($mysqli, $query, '', false, false);

        // Get the total number of orders in database
        $query = "SELECT * FROM orders";
        $total_result_list = simpleQueryFetch($mysqli, $query, '', false, true);

        // If the employee table is empty
        if (count($result_list) === 0) {
            $error = ' • No order was found in the database';
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
    <h1 class="books-app-title">This is the list of those juicy orders you have</h1>

    <p class="books-app-text gray-text">Click to see their information, tap the little pencil icon to edit.</p>

    <p class="books-app-text gray-text">Current Page: <?php print($current_page) ?></p>

    <p class="books-app-text gray-text">Result Per Page: <?php print($per_page) ?></p>

    <p class="books-app-text gray-text">Total Count: <?php print(count($total_result_list)) ?></p>

    <!-- Next Page Button -->
    <?php if (($current_page * $per_page) < count($total_result_list)): ?>
        <p class='books-app-text books-app-menu'><a href="<?php print('order_list.php?current_page='.($current_page + 1)) ?>">Next Page ></a></p>
    <?php endif; ?>

    <!-- Previous Page Button -->
    <?php if ($current_page > 1): ?>
        <p class='books-app-text books-app-menu'><a href="<?php print('order_list.php?current_page='.($current_page - 1)) ?>">< Previous Page</a></p>
    <?php endif; ?>

    <!-- Table -->
    <div class="books-app-menu-container">
        <table>
            <!-- Header Row -->
            <thead>
                <tr>
                    <th class="edit-column"></th>
                    <th class="id-column">Order ID</th>
                    <th>Date</th>
                    <th>Customer Name</th>
                    <th>Employee Name</th>
                    <th>Shipper</th>
                </tr>
            </thead>

            <!-- Data Rows -->
            <tbody>
                <?php
                foreach ($result_list as $element) {
                    $order_id = $element['OrderID'];
                    $order_date = date_format(date_create($element['OrderDate']), 'M d, Y');
                    $customer_id = $element['CustomerID'];
                    $customer_name = $element['CustomerName'];
                    $employee_id = $element['EmployeeID'];
                    $employee_name = $element['FirstName'] . ' ' . $element['LastName'];
                    $shipper_name = $element['ShipperName'];

                    print("
                        <tr>
                            <td class='edit-column'><a href='order_edit.php?id=$order_id'><img src='./assets/image_edit.png' alt='edit' style='width: 20px; height: 20px'/></a></td>
                            <td class='id-column'><a href='order_detail.php?id=$order_id'>$order_id</a></td>
                            <td>$order_date</td>
                            <td><a href='customer_detail.php?id=$customer_id' target='_blank' rel='noopener noreferrer'>$customer_name</a></td>
                            <td><a href='employee_detail.php?id=$employee_id' target='_blank' rel='noopener noreferrer'>$employee_name</a></td>
                            <td>$shipper_name</td>
                        </tr>
                    ");
                }
                ?>
            </tbody>
        </table>
    </div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/page_template.php');