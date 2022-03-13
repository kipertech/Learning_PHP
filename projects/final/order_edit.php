<?php

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_general.php');

$start = microtime(true);

// Get input from param
$input_data = getParam('id');
$error = checkNumericInput($input_data, 'Order ID (Param Name: "id")', 1, null);
$success_message = '';

// Input params
$page_title = "Edit Order | Final Project";
$hide_page_title = true;
$hide_db_info = true;
$active_name = 'Orders';

// Initialize values
$total_time = 0;
$row = [];
$order_list = [];

$employee_list = [];
$customer_list = [];
$shipper_list = [];

// Get data from session
$input_date = getSessionValue('date', '');
$input_employee_id = getSessionValue('employee_id', '');
$input_customer_id = getSessionValue('customer_id', '');
$input_shipper_id = getSessionValue('shipper_id', '');

// If input is valid
if (empty($error)) {
    // Fetching Data
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
            $mysqli -> close();
        }
        // Set data for the input
        else {
            $input_date = $row['OrderDate'];
            $input_employee_id = $row['EmployeeID'];
            $input_customer_id = $row['CustomerID'];
            $input_shipper_id = $row['ShipperID'];

            // Save current data in case there is a POST command
            $_SESSION['date'] = $input_date;
            $_SESSION['employee_id'] = $input_employee_id;
            $_SESSION['customer_id'] = $input_customer_id;
            $_SESSION['shipper_id'] = $input_shipper_id;

            // Fetch Employee, Customer and Shipper list
            $employee_list = simpleQueryFetch($mysqli, "SELECT * FROM employees", '', false, false);
            $customer_list = simpleQueryFetch($mysqli, "SELECT * FROM customers", '', false, false);
            $shipper_list = simpleQueryFetch($mysqli, "SELECT * FROM shippers", '', false, true);
        }

        // Log execution time
        $total_time = number_format(microtime(true) - $start, 2, '.', ',');
    }

    // Editing Data
    if (isset($_POST['cmdAddEdit'])) {
        // Get input from POST
        $input_date = getParam('order_date', true, true, false) ?? '';
        $input_employee_id = $_POST['employee-list'][0];
        $input_customer_id = $_POST['customer-list'][0];
        $input_shipper_id = $_POST['shipper-list'][0];

        // Check for input errors
        $date_error = checkStringInput($input_date, 'Date');
        $employee_error = checkNumericInput($input_employee_id, 'Employee ID');
        $customer_error = checkNumericInput($input_customer_id, 'Customer ID');
        $shipper_error = checkNumericInput($input_shipper_id, 'Shipper ID');

        // Combine error messages
        $error = errorGenerator([$date_error, $employee_error, $customer_error, $shipper_error]);

        // Proceed if there is no error
        if (empty($error)) {
            // Connect to DB
            $dbObject = getDBConnection();
            $mysqli = $dbObject -> mysqli;
            $error = $dbObject -> connection_error;

            // Perform update query
            $query = "
                UPDATE orders
                SET OrderDate = '".$input_date."',
                    EmployeeID = ".$input_employee_id.",
                    CustomerID = ".$input_customer_id.",
                    ShipperID = ".$input_shipper_id."
                WHERE OrderID = ".$input_data
            ;
            $update_error = simpleQueryUpdate($mysqli, $query, true);

            // Check if there is error
            if ($update_error !== '') {
                $error = 'Failed to update order data. Error message:' . "\n" . '"' . $update_error . '"';
            }
            else {
                header('Location: order_detail.php?id=' . $input_data);
                exit();
            }
        }
    }
    // Is Deleting
    elseif (isset($_POST['cmdDelete'])) {
        // Go to delete confirmation page
        header("Location: order_delete.php?id=" . $input_data);
        exit();
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
    <h1 class="books-app-title no-margin-top">Edit Order</h1>

    <p class="books-app-text gray-text">You can only update the Date, Customer, Employee and Shipper information</p>

    <!-- Form -->
    <div class="big-form-container">
        <form method="post">
            <!-- Order Date -->
            <div class="small-form-container vertical-form">
                <label>Order Date</label>
                <input type="date" name="order_date"
                       value="<?= $input_date ?>"
                       min="1902-01-01" max="2022-05-01">
                <small>From the last 120 years only please...</small>
            </div>

            <!-- Employee Select -->
            <div class="small-form-container vertical-form">
                <label>Select Employee</label>
                <select class="drop-down-list" name="employee-list[]" size="1">
                    <?php
                    foreach ($employee_list as $element) {
                        $employee_id = $element['EmployeeID'];
                        $employee_name = $employee_id . '. ' . $element['FirstName'] . ' ' . $element['LastName'];

                        if ($input_employee_id === $employee_id) {
                            print("<option selected value=$employee_id>$employee_name</option>");
                        }
                        else print("<option value=$employee_id>$employee_name</option>");
                    }
                    ?>
                </select>
            </div>

            <!-- Customer Select -->
            <div class="small-form-container vertical-form">
                <label>Select Customer</label>
                <select class="drop-down-list" name="customer-list[]" size="9">
                    <?php
                    foreach ($customer_list as $element) {
                        $customer_id = $element['CustomerID'];
                        $customer_name = str_pad($customer_id, 2, '0', STR_PAD_LEFT) . '. ' . $element['CustomerName'];

                        if ($input_customer_id === $customer_id) {
                            print("<option selected value=$customer_id>$customer_name</option>");
                        }
                        else print("<option value=$customer_id>$customer_name</option>");
                    }
                    ?>
                </select>
            </div>

            <!-- Shipper Select -->
            <div class="small-form-container vertical-form">
                <label>Select Shipper</label>
                <select class="drop-down-list" name="shipper-list[]" size="1">
                    <?php
                    foreach ($shipper_list as $element) {
                        $shipper_id = $element['ShipperID'];
                        $shipper_name = $element['ShipperName'];

                        if ($input_shipper_id === $shipper_id) {
                            print("<option selected value=$shipper_id>$shipper_name</option>");
                        }
                        else print("<option value=$shipper_id>$shipper_name</option>");
                    }
                    ?>
                </select>
            </div>

            <!-- Submit -->
            <div class="button-container button-container-double">
                <button type="submit" name="cmdAddEdit">Save Order Data</button>
                <button type="submit" name="cmdDelete" class="delete-button">Delete Order</button>
            </div>
        </form>
    </div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/form_template.php');