<?php

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

// Load components
require_once(__DIR__ . '/components/nav_bar.php');
require_once(__DIR__ . '/components/small_items.php');

// Input params
$page_title = "Employee List | Final Project";
$hide_page_title = true;
$hide_db_info = true;
$active_name = 'Employees';

// Initialize values
$result_list = [];
$error = '';

// Connect to DB
$dbObject = getDBConnection();
$mysqli = $dbObject -> mysqli;
$error = $dbObject -> connection_error;

// If successfully connected to DB
if (empty($error)) {
    // Perform query
    $query = "SELECT * FROM employees";
    $result_list = simpleQueryFetch($mysqli, $query, '', false, true);

    // If the employee table is empty
    if (count($result_list) === 0) {
        $error = ' • No employee was found in the database';
    }
}

// Define HTML Body
ob_start();

?>
    <!-- Page Title -->
    <h1 class="books-app-title">This is the list of your lovely employees</h1>

    <p class="books-app-text gray-text">Click to see their information, tap the little icon to edit.</p>

    <p class="books-app-text gray-text">Total Count: <?php print(count($result_list)) ?></p>

    <div class="books-app-menu-container">
        <?php
        renderAddButton('employee_edit.php', 'Employee');

        foreach ($result_list as $element) {
            $employee_id = $element['EmployeeID'];
            $employee_name = $element['FirstName'] . ' ' . $element['LastName'];
            renderListItem($employee_id, $employee_name, 'employee_edit', 'employee_detail');
        }
        ?>
    </div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/page_template.php');