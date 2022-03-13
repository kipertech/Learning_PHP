<?php

$page_title = "Home | Final Project";
$hide_page_title = true;
$hide_db_info = true;
$active_name = 'Home';

// Define HTML Body
ob_start();

// Nav Bar
require_once(__DIR__ . '/components/nav_bar.php');

?>

    <!-- Page Title -->
    <h1 class="books-app-title">Aloha!</h1>

    <p class="books-app-text gray-text">Welcome aboard, fearless manager. Qapla'!</p>

    <div class="books-app-menu-container">
        <p class="books-app-text books-app-menu"><a href="employee_list.php">> See list of employees</a></p>
        <p class="books-app-text books-app-menu"><a href="customer_list.php">> See list of customers</a></p>
        <p class="books-app-text books-app-menu"><a href="order_list.php">> See list of orders</a></p>
    </div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/page_template.php');