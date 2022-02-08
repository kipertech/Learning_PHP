<?php

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

// Input params
$page_title = "Result | Pokemon Petting Zoo Tickets";
$hide_db_info = true;
$back_link = 'index.php';

// Get input from param
$error = '';
$input_adults = getParam('adults', false, true) ?? 0;
$input_children = getParam('children', false, true) ?? 0;

$total_price = 0;
$total_price = ((int)$input_adults * 15) + ((int)$input_children * 8);

if ($input_children > 0) {
    $error = checkNumericInput($input_adults, 'Adults', 1, null);
}

// Define HTML Body
ob_start(); ?>

<!-- Result -->
<p class="gray-text separate-link">Adults: <span class="black-text"><?php print($input_adults) ?></span></p>
<p class="gray-text">Children: <span class="black-text"><?php print($input_children) ?></span></p>
<p class="gray-text">Price:</p>
<div class="money-result-box"><h1>$<?php echo($total_price) ?></h1></div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/page_template.php');