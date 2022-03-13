<?php

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

// Load components
require_once(__DIR__ . '/components/small_items.php');

$start = microtime(true);

// Input params
$page_title = "Delete Customer | Final Project";
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

    // Fetch Employee Detail
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
                FROM orders INNER JOIN customers ON customers.CustomerID = orders.CustomerID
                WHERE orders.CustomerID = ?
            ";
            $order_list = simpleQueryFetch($mysqli, $query, $input_data, false, true);

            if (count($order_list) > 0) {
                $error = 'Unable to comply, this customer is associated with one or more orders.';
            }
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
        $query = "DELETE FROM customers WHERE CustomerID = " . $input_data;
        $update_error = simpleQueryUpdate($mysqli, $query, true);

        // Check if there is error
        if ($update_error !== '') {
            $error = 'Failed to delete customer. Error message:' . "\n" . '"' . $update_error . '"';
        }
        else {
            echo('<script>alert("Customer removed, muhahaha!")</script>');
            header('Location: customer_list.php');
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
    <p class="books-app-back-link"><a href="customer_list.php">< Go back to Customer List</a></p>

    <!-- Main Content -->
<?php if (empty($error)): ?>
    <!-- Page Title -->
    <h1 class="books-app-title no-margin-top">Delete Confirmation</h1>

    <p class="books-app-text gray-text">Are you sure you want to delete this customer?</p>

    <!-- Delete Button -->
    <form method="post">
        <button type="submit" name="cmdDelete" class="delete-button">Confirm Delete Customer</button>
    </form>

    <!-- Customer Data -->
    <p class="books-app-text gray-text separate-link">ID: <span class="black-text"><?php print($row['CustomerID']) ?></span></p>
    <p class="books-app-text gray-text">Name: <span class="black-text"><?php print($row['CustomerName']) ?></span></p>
    <p class="books-app-text gray-text">Contact Name: <span class="black-text"><?php print($row['ContactName']) ?></span></p>
    <p class="books-app-text gray-text">Address: <span class="black-text"><?php print($row['Address']) ?></span></p>
    <p class="books-app-text gray-text">City: <span class="black-text"><?php print($row['City']) ?></span></p>
    <p class="books-app-text gray-text">Country: <span class="black-text"><?php print($row['Country']) ?></span></p>
    <p class="books-app-text gray-text">Postal Code: <span class="black-text"><?php print($row['PostalCode']) ?></span></p>

    <!-- List of Orders -->
    <h2 class="books-app-sub-title dark-blue-text separate-link">Orders placed by this customer: <?php print(count($order_list)) ?></h2>
<?php endif; ?>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/form_template.php');