<?php

// Start current session
session_start();

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

// Input params
$page_title = "Sales Tax Form";
$hide_db_info = true;

// Initialize value
$error = '';
$input_state = '';
$input_price = 0;
$input_discount = 0;

// Check if user sent an input
if ($_POST) {
    $input_state = getParam('state', true, true, false) ?? '';
    $input_price = getParam('price', false, true) ?? 0;
    $input_discount = getParam('discount', false, true) ?? 0;

    // Check for input error
    $state_error = checkStringInput($input_state, 'State', ['MI', 'IL']);
    $price_error = checkNumericInput($input_price, 'Price', 0);
    $discount_error = checkNumericInput($input_discount, 'Discount Rate', 0);

    // Combine error messages
    $error =
        $state_error .
        (!empty($state_error) ? "\n" : '') .
        $price_error .
        ((!empty($price_error) || !empty($state_error)) ? "\n" : '') .
        $discount_error;

    // Send to header and store input for current session
    if (empty($error)) {
        $_SESSION['state'] = $input_state;
        $_SESSION['price'] = $input_price;
        $_SESSION['discount'] = $input_discount;
        header('Location: result.php');
        exit();
    }
}

// Define HTML Body
ob_start(); ?>
    <p>Complete the form below, then click Submit</p>

    <div class="big-form-container">
        <!-- Form -->
        <form method="post">
            <!-- State -->
            <div class="small-form-container vertical-form">
                <label>State</label>
                <input type="text" name="state" value="<?= $input_state ?>">
                <small>Only MI or IL</small>
            </div>

            <!-- Price -->
            <div class="small-form-container vertical-form">
                <label>Price</label>
                <input type="number" name="price" value="<?= $input_price ?>">
                <small>In dollars</small>
            </div>

            <!-- Discount Rate -->
            <div class="small-form-container vertical-form">
                <label>Discount Rate</label>
                <input type="number" name="discount" value="<?= $input_discount ?>">
                <small>Percentage, example: 5</small>
            </div>

            <!-- Submit Button -->
            <div class="button-container">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/form_template.php');