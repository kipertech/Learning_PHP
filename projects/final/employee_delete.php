<?php

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

// Load components
require_once(__DIR__ . '/components/small_items.php');

$start = microtime(true);

// Input params
$page_title = "Delete Employee | Final Project";
$hide_page_title = true;
$hide_db_info = true;
$active_name = 'Employees';

// Get input from param
$input_data = getParam('id');
$error = checkNumericInput($input_data, 'Employee ID (Param Name: "id")', 1, null);

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
        $query = "SELECT * FROM employees WHERE EmployeeID = ?";
        $row = simpleQueryFetch($mysqli, $query, $input_data, true, false);

        // If ID is not found
        if (empty($row)) {
            $error = ' • No employee was found for the input ID ' . $input_data;
        }
        // Get the list of orders associated with this employee
        else {
            $query = "
                SELECT *
                FROM orders INNER JOIN employees ON employees.EmployeeID = orders.EmployeeID
                WHERE orders.EmployeeID = ?
            ";
            $order_list = simpleQueryFetch($mysqli, $query, $input_data, false, true);

            if (count($order_list) > 0) {
                $error = 'Unable to comply, this employee is associated with one or more orders.';
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
        $query = "DELETE FROM employees WHERE EmployeeID = " . $input_data;
        $update_error = simpleQueryUpdate($mysqli, $query, true);

        // Check if there is error
        if ($update_error !== '') {
            $error = 'Failed to delete employee. Error message:' . "\n" . '"' . $update_error . '"';
        }
        else {
            echo('<script>alert("Employee removed, muhahaha!")</script>');
            header('Location: employee_list.php');
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
    <p class="books-app-back-link"><a href="employee_list.php">< Go back to Employee List</a></p>

    <!-- Main Content -->
    <?php if (empty($error)): ?>
        <!-- Page Title -->
        <h1 class="books-app-title no-margin-top">Delete Confirmation</h1>

        <p class="books-app-text gray-text">Are you sure you want to delete this employee?</p>

        <!-- Delete Button -->
        <form method="post">
            <button type="submit" name="cmdDelete" class="delete-button">Confirm Delete Employee</button>
        </form>

        <!-- Employee Data -->
        <p class="books-app-text gray-text separate-link">ID: <span class="black-text"><?php print($row['EmployeeID']) ?></span></p>
        <p class="books-app-text gray-text">Name: <span class="black-text"><?php print($row['FirstName'] . ' ' . $row['LastName']) ?></span></p>
        <p class="books-app-text gray-text">Birthday: <span class="black-text"><?php print(date_format(date_create($row['BirthDate']), 'M d, Y')) ?></span></p>
        <p class="books-app-text gray-text">Notes: <span class="black-text"><?php print($row['Notes'] ?? '(None)') ?></span></p>

        <!-- List of Orders -->
        <h2 class="books-app-sub-title dark-blue-text separate-link">Orders served by this employee: <?php print(count($order_list)) ?></h2>
    <?php endif; ?>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/form_template.php');