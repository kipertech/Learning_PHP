<?php

// Start current session
session_start();

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

// Input params
$page_title = "Add a Wombat";
$data_source = 'Custom';
$data_source_url = 'https://webapps.skilling.us/node/393';

// Initialize value
$error = '';
$input_name = $_SESSION['name'];
$input_weight = $_SESSION['weight'];
$input_comment = $_SESSION['comment'];

// Check if user sent an input
if ($_POST) {
    $input_name = getParam('name', true, true, false) ?? '';
    $input_weight = getParam('weight', false, true) ?? null;
    $input_comment = getParam('comment', true, true, false) ?? '';

    // Check for input error
    $name_error = checkStringInput($input_name, 'Name');
    $weight_error = checkNumericInput($input_weight, 'Weight', 0, null, false, true);

    // Combine error messages
    $error =
        $name_error .
        (!empty($name_error) ? "\n" : '') .
        $weight_error;

    // Send to header and store input for current session
    if (empty($error)) {
        $_SESSION['name'] = $input_name;
        $_SESSION['weight'] = $input_weight;
        $_SESSION['comment'] = $input_comment;
        header('Location: result.php');
        exit();
    }
}

// Define HTML Body
ob_start(); ?>
    <p>Complete the form below to add a new wombat</p>

    <div class="big-form-container">
        <!-- Form -->
        <form method="post">
            <!-- State -->
            <div class="small-form-container vertical-form">
                <label>Name</label>
                <input type="text" name="name" value="<?= $input_name ?>">
                <small>Make sure it's a cute one</small>
            </div>

            <!-- Price -->
            <div class="small-form-container vertical-form">
                <label>Weight</label>
                <input type="number" name="weight" value="<?= $input_weight ?>">
                <small>In kilograms (the universal standard, duh)</small>
            </div>

            <!-- Discount Rate -->
            <div class="small-form-container vertical-form">
                <label>Comment</label>
                <input type="text" name="comment" value="<?= $input_comment ?>">
                <small>Anything you want to add?</small>
            </div>

            <!-- Submit Button -->
            <div class="button-container">
                <button type="submit">Add Wombat</button>
            </div>
        </form>
    </div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/form_template.php');