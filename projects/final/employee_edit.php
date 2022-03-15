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
$error = $has_no_id ? "" : checkNumericInput($input_data, 'Employee ID (Param Name: "id")', 1, null);
$success_message = '';

// Input params
$page_title = ($has_no_id ? "Add" : "Edit") . " Employee | Final Project";
$hide_page_title = true;
$hide_db_info = true;
$active_name = 'Employees';

// Initialize values
$total_time = 0;
$row = [];
$order_list = [];

// Get data from session
$input_name_first = getSessionValue('first_name', '');
$input_name_last = getSessionValue('last_name', '');
$input_birthday = getSessionValue('birthday', '1996-12-10');
$input_notes = getSessionValue('notes', '');

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
            $query = "SELECT * FROM employees WHERE EmployeeID = ?";
            $row = simpleQueryFetch($mysqli, $query, $input_data, true, true);

            // If ID is not found
            if (empty($row)) {
                $error = ' • No employee was found for the input ID ' . $input_data;
            }
            // Set data for the input
            else {
                $input_name_first = $row['FirstName'];
                $input_name_last = $row['LastName'];
                $input_birthday = $row['BirthDate'];
                $input_notes = $row['Notes'];

                // Save current data in case there is a POST command
                $_SESSION['first_name'] = $input_name_first;
                $_SESSION['last_name'] = $input_name_last;
                $_SESSION['birthday'] = $input_birthday;
                $_SESSION['notes'] = $input_notes;
            }

            // Log execution time
            $total_time = number_format(microtime(true) - $start, 2, '.', ',');
        }
    }

    // Adding or Editing Data
    if (isset($_POST['cmdAddEdit'])) {
        // Get input from POST
        $input_name_first = getParam('first_name', true, true, false) ?? '';
        $input_name_last = getParam('last_name', true, true, false) ?? '';
        $input_birthday = getParam('birthday', false, true) ?? '';
        $input_notes = getParam('notes', true, true, false) ?? '';

        // Check for input errors
        $name_first_error = checkStringInput($input_name_first, 'First Name');
        $name_last_error = checkStringInput($input_name_last, 'Last Name');
        $birthday_error = checkBirthdayInput($input_birthday, 18);

        // Combine error messages
        $error = errorGenerator([$name_first_error, $name_last_error, $birthday_error]);

        // Proceed if there is no error
        if (empty($error)) {
            // Connect to DB
            $dbObject = getDBConnection();
            $mysqli = $dbObject -> mysqli;
            $error = $dbObject -> connection_error;
            $query_comment = str_replace("'", "\'", $input_notes);

            // Is Editing
            if (!$has_no_id) {
                // Perform update query
                $query = "
                    UPDATE employees
                    SET FirstName = '".$input_name_first."', LastName = '".$input_name_last."', BirthDate = '".$input_birthday."', Notes = '".$input_notes."'
                    WHERE EmployeeID = ".$input_data
                ;
                $update_error = simpleQueryUpdate($mysqli, $query, true);

                // Check if there is error
                if ($update_error !== '') {
                    $error = 'Failed to update employee data. Error message:' . "\n" . '"' . $update_error . '"';
                }
                else {
                    $success_message = 'Successfully updated employee data';
                }
            }
            // Is Adding
            else {
                // Perform insert query
                $query = "
                    INSERT INTO employees (FirstName, LastName, BirthDate, Notes)
                    VALUES ('".$input_name_first."', '".$input_name_last."', '".$input_birthday."', '".$query_comment."')
                ";
                $result_obj = simpleQueryInsert($mysqli, $query, true);

                // Check if there is error
                $inserted_id = $result_obj -> last_id;
                $insert_error = $result_obj -> error;
                if ($insert_error !== '') {
                    $error = 'Failed to add new employee. Error message:' . "\n" . '"' . $insert_error . '"';
                }
                // Go to employee detail page
                else {
                    header("Location: employee_detail.php?id=" . $inserted_id);
                }
            }
        }
    }
    // Is Deleting
    elseif (isset($_POST['cmdDelete'])) {
        // Check if this employee is associated with any order
        $dbObject = getDBConnection();
        $mysqli = $dbObject -> mysqli;
        $error = $dbObject -> connection_error;

        $query = "
            SELECT *
            FROM orders INNER JOIN employees ON employees.EmployeeID = orders.EmployeeID
            WHERE orders.EmployeeID = ?
        ";
        $order_list = simpleQueryFetch($mysqli, $query, $input_data, false, true);

        if (count($order_list) > 0) {
            $error = 'This employee is associated with one or more orders, unable to delete.';
        }
        else {
            // Go to delete confirmation page
            header("Location: employee_delete.php?id=" . $input_data);
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

    <!-- Page Title -->
    <h1 class="books-app-title no-margin-top"><?php print($has_no_id ? "Add" : "Edit") ?> Employee</h1>

    <!-- Form -->
    <div class="big-form-container">
        <form method="post">
            <!-- First Name -->
            <div class="small-form-container vertical-form">
                <label>First Name</label>
                <input type="text" name="first_name" value="<?= $input_name_first ?>">
                <small>Should not be empty!</small>
            </div>

            <!-- Last Name -->
            <div class="small-form-container vertical-form">
                <label>Last Name</label>
                <input type="text" name="last_name" value="<?= $input_name_last ?>">
                <small>In glory of the family</small>
            </div>

            <!-- Birthday -->
            <div class="small-form-container vertical-form">
                <label>Birthday</label>
                <input type="date" name="birthday"
                       value="<?= $input_birthday ?>"
                       min="1900-01-01" max="2004-12-31">
                <small>(We only hire people who are 18 and above)</small>
            </div>

            <!-- Notes -->
            <div class="small-form-container vertical-form">
                <label>Notes</label>
                <textarea class="long-text-input" name="notes" rows="10"><?php print($input_notes) ?></textarea>
                <small>Anything you want to add?</small>
            </div>

            <!-- Submit -->
            <div class="button-container button-container-double">
                <button type="submit" name="cmdAddEdit"><?php print($has_no_id ? "Add New Employee" : "Save Employee Data") ?></button>
                <?php if (!$has_no_id): ?>
                    <button type="submit" name="cmdDelete" class="delete-button">Delete Employee</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/form_template.php');