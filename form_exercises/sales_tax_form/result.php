<?php

session_start();

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

// Input params
$page_title = "Result | Sales Tax Form";
$hide_db_info = true;
$back_link = 'index.php';

// Get input from param
$error = '';
$input_state = $_SESSION['state'];
$input_price = (float)$_SESSION['price'];
$input_discount = (float)$_SESSION['discount'];

// Displayed sales tax rate
$displayed_rate = $input_state === 'MI' ? 'Michigan - 6%' : 'Illinois - 6.25%';

// Calculation
$sales_tax_rate = (strtoupper($input_state) === 'MI') ? 0.06 : 0.0625;
$discount_rate = $input_discount / 100;

$discounted_price = $input_price - ($input_price * $discount_rate);
$sales_tax = $discounted_price * $sales_tax_rate;

$total_price = 0;
$total_price = $discounted_price + $sales_tax;

// Format for nice looking number
$total_price = number_format((float)$total_price, 2, '.', ',');

// Define HTML Body
ob_start(); ?>

    <!-- Result -->
    <p class="gray-text separate-link">Listed Price: <span class="black-text">$<?php print($input_price) ?></span></p>
    <p class="gray-text">Discount Rate: <span class="black-text"><?php print($input_discount) ?>%</span></p>

    <p class="gray-text">Discounted Price: <span class="black-text">$<?php print($discounted_price) ?></span></p>
    <p class="gray-text">Sales Tax (<?php print($displayed_rate) ?>): <span class="black-text">$<?php print($sales_tax) ?></span></p>

    <p class="gray-text">Total Price:</p>
    <div class="money-result-box"><h1>$<?php echo($total_price) ?></h1></div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/page_template.php');