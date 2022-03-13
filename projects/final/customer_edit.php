<?php

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_general.php');

$start = microtime(true);

// Get input from param
$input_data = getParam('id');
$has_no_id = empty($input_data);
$error = $has_no_id ? "" : checkNumericInput($input_data, 'Customer ID (Param Name: "id")', 1, null);
$success_message = '';

// Input params
$page_title = ($has_no_id ? "Add" : "Edit") . " Customer | Final Project";
$hide_page_title = true;
$hide_db_info = true;
$active_name = 'Customers';

// Initialize values
$total_time = 0;
$row = [];
$order_list = [];

// Get data from session
$input_name = getSessionValue('name', '');
$input_contact_name = getSessionValue('contact_name', '');
$input_address = getSessionValue('address', '');
$input_city = getSessionValue('city', '');
$input_country = getSessionValue('country', '');
$input_postal = getSessionValue('postal', '');

// If input is valid
if (empty($error)) {
    // Just Fetching Data
    if (!$has_no_id) {
        // Connect to DB
        $dbObject = getDBConnection();
        $mysqli = $dbObject -> mysqli;
        $error = $dbObject -> connection_error;

        // If successfully connected to DB
        if (empty($error)) {
            // Perform query
            $query = "SELECT * FROM customers WHERE CustomerID = ?";
            $row = simpleQueryFetch($mysqli, $query, $input_data, true, true);

            // If ID is not found
            if (empty($row)) {
                $error = ' • No customer was found for the input ID ' . $input_data;
            }
            // Set data for the input
            else {
                $input_name = $row['CustomerName'];
                $input_contact_name = $row['ContactName'];
                $input_address = $row['Address'];
                $input_city = $row['City'];
                $input_country = $row['Country'];
                $input_postal = $row['PostalCode'];

                // Save current data in case there is a POST command
                $_SESSION['name'] = $input_name;
                $_SESSION['contact_name'] = $input_contact_name;
                $_SESSION['address'] = $input_address;
                $_SESSION['city'] = $input_city;
                $_SESSION['country'] = $input_country;
                $_SESSION['postal'] = $input_postal;
            }

            // Log execution time
            $total_time = number_format(microtime(true) - $start, 2, '.', ',');
        }
    }

    // Adding or Editing Data
    if (isset($_POST['cmdAddEdit'])) {
        // Get input from POST
        $input_name = getParam('name', true, true, false) ?? '';
        $input_contact_name = getParam('contact_name', true, true, false) ?? '';
        $input_address = getParam('address', true, true, false) ?? '';
        $input_city = getParam('city', true, true, false) ?? '';
        $input_country = getParam('country', true, true, false) ?? '';
        $input_postal = getParam('postal', true, true, false) ?? '';

        // Check for input errors
        $name_error = checkStringInput($input_name, 'Name');
        $contact_name_error = checkStringInput($input_contact_name, 'Contact Name');
        $address_error = checkStringInput($input_address, 'Address');
        $city_error = checkStringInput($input_city, 'City');
        $country_error = checkStringInput($input_country, 'Country');
        $postal_error = checkStringInput($input_postal, 'Postal Code');

        // Combine error messages
        $error = errorGenerator([$name_error, $contact_name_error, $address_error, $city_error, $country_error, $postal_error]);

        // Proceed if there is no error
        if (empty($error)) {
            // Connect to DB
            $dbObject = getDBConnection();
            $mysqli = $dbObject -> mysqli;
            $error = $dbObject -> connection_error;

            // Is Editing
            if (!$has_no_id) {
                // Perform update query
                $query = "
                    UPDATE customers
                    SET CustomerName = '".$input_name."',
                        ContactName = '".$input_contact_name."',
                        Address = '".$input_address."',
                        City = '".$input_city."',
                        Country = '".$input_country."',
                        PostalCode = '".$input_postal."'
                    WHERE CustomerID = ".$input_data
                ;
                $update_error = simpleQueryUpdate($mysqli, $query, true);

                // Check if there is error
                if ($update_error !== '') {
                    $error = 'Failed to update customer data. Error message:' . "\n" . '"' . $update_error . '"';
                }
                else {
                    $success_message = 'Successfully updated customer data';
                }
            }
            // Is Adding
            else {
                // Perform insert query
                $query = "
                    INSERT INTO customers (CustomerName, ContactName, Address, City, Country, PostalCode)
                    VALUES ('".$input_name."', '".$input_contact_name."', '".$input_address."', '".$input_city."', '".$input_country."', '".$input_postal."')
                ";
                $result_obj = simpleQueryInsert($mysqli, $query, true);

                // Check if there is error
                $inserted_id = $result_obj -> last_id;
                $insert_error = $result_obj -> error;
                if ($insert_error !== '') {
                    $error = 'Failed to add new customer. Error message:' . "\n" . '"' . $insert_error . '"';
                }
                // Go to employee detail page
                else {
                    header("Location: customer_detail.php?id=" . $inserted_id);
                }
            }
        }
    }
    // Is Deleting
    elseif (isset($_POST['cmdDelete'])) {
        // Check if this customer is associated with any order
        $dbObject = getDBConnection();
        $mysqli = $dbObject -> mysqli;
        $error = $dbObject -> connection_error;

        $query = "
            SELECT *
            FROM orders INNER JOIN customers ON customers.CustomerID = orders.CustomerID
            WHERE orders.CustomerID = ?
        ";
        $order_list = simpleQueryFetch($mysqli, $query, $input_data, false, true);

        if (count($order_list) > 0) {
            $error = 'This customer is associated with one or more orders, unable to delete.';
        }
        else {
            // Go to delete confirmation page
            header("Location: customer_delete.php?id=" . $input_data);
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

    <!-- Page Title -->
    <h1 class="books-app-title no-margin-top"><?php print($has_no_id ? "Add" : "Edit") ?> Customer</h1>

    <!-- Form -->
    <div class="big-form-container">
        <form method="post">
            <!-- Customer Name -->
            <div class="small-form-container vertical-form">
                <label>Customer Name</label>
                <input type="text" name="name" value="<?= $input_name ?>">
                <small>Should not be empty!</small>
            </div>

            <!-- Contact Name -->
            <div class="small-form-container vertical-form">
                <label>Contact Name</label>
                <input type="text" name="contact_name" value="<?= $input_contact_name ?>">
                <small>Same as above... do not leave empty!</small>
            </div>

            <!-- Address -->
            <div class="small-form-container vertical-form">
                <label>Address</label>
                <input type="text" name="address" value="<?= $input_address ?>">
                <small>Number and street name only please</small>
            </div>

            <!-- City -->
            <div class="small-form-container vertical-form">
                <label>City</label>
                <input type="text" name="city" value="<?= $input_city ?>">
                <small>Name of their lovely city</small>
            </div>

            <!-- Country -->
            <div class="small-form-container vertical-form">
                <label>Country</label>
                <input type="text" name="country" value="<?= $input_country ?>">
                <small>Probably just USA, but who knows?</small>
            </div>

            <!-- Postal -->
            <div class="small-form-container vertical-form">
                <label>Postal Code</label>
                <input type="text" name="postal" value="<?= $input_postal ?>">
                <small>Only the formal 5 digits</small>
            </div>

            <!-- Submit -->
            <div class="button-container button-container-double">
                <button type="submit" name="cmdAddEdit"><?php print($has_no_id ? "Add New Customer" : "Save Customer Data") ?></button>
                <?php if (!$has_no_id): ?>
                    <button type="submit" name="cmdDelete" class="delete-button">Delete Customer</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/form_template.php');