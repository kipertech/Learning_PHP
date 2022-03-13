<?php

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

// Load components
require_once(__DIR__ . './components/small_items.php');

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
            $query = "
                SELECT *
                FROM order_details
                    INNER JOIN products p on order_details.ProductID = p.ProductID
                    INNER JOIN categories c on p.CategoryID = c.CategoryID
                    INNER JOIN suppliers s on p.SupplierID = s.SupplierID
                WHERE order_details.OrderID = ?
            ";
            $product_list = simpleQueryFetch($mysqli, $query, $input_data, false, true);
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
    <p class="books-app-back-link"><a href="order_list.php">< Go back to Order List</a></p>

    <!-- Page Title -->
    <h1 class="books-app-title no-margin-top">Order Detail</h1>

    <p class="books-app-text gray-text">A bit boring to look at, but oh well...</p>

    <!-- Edit Button -->
    <?php renderAddButton('order_edit.php?id=' . $input_data, 'Order', 'Update', './assets/image_edit_color.png'); ?>

    <!-- Order Data -->
    <p class="books-app-text gray-text separate-link">ID: <span class="black-text"><?php print($row['OrderID']) ?></span></p>
    <p class="books-app-text gray-text">Date: <span class="black-text"><?php print(date_format(date_create($row['OrderDate']), 'M d, Y')) ?></span></p>
    <p class="books-app-text gray-text">Placed By: <span class="black-text"><a href="<?php print('customer_detail.php?id='.$row['CustomerID']) ?>" target='_blank' rel='noopener noreferrer'><?php print($row['CustomerName']) ?></a></span></p>
    <p class="books-app-text gray-text">Served By: <span class="black-text"><a href="<?php print('employee_detail.php?id='.$row['EmployeeID']) ?>" target='_blank' rel='noopener noreferrer'><?php print($row['FirstName'].' '.$row['LastName']) ?></a></span></p>

    <!-- Product List -->
    <h2 class="books-app-sub-title dark-blue-text separate-link">Product listed in this order (<?php print(count($product_list)) ?>)</h2>

    <!-- Table -->
    <div class="books-app-menu-container separate-link">
        <table>
            <!-- Header Row -->
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Unit Description</th>
                <th>Supplier</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
            </thead>

            <!-- Data Rows -->
            <tbody>
                <?php
                foreach ($product_list as $element) {
                    $product_id = $element['ProductID'];
                    $product_name = $element['ProductName'];
                    $category = $element['CategoryName'];
                    $product_unit_description = $element['Unit'];
                    $quantity = $element['Quantity'];
                    $price = $element['Price'];
                    $supplier_name = $element['SupplierName'];
                    $total_price = (int)$quantity * (float)$price;

                    print("
                        <tr>
                            <td>$product_id</td>
                            <td>$product_name</td>
                            <td>$category</td>
                            <td>$product_unit_description</td>
                            <td>$supplier_name</td>
                            <td>$quantity</td>
                            <td>$$price</td>
                            <td>$$total_price</td>
                        </tr>
                    ");
                }
                ?>
            </tbody>
        </table>
    </div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/form_template.php');