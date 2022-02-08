<?php

$page_title = "Home | Books App";
$hide_page_title = true;
$hide_db_info = true;
$active_name = 'Home';

// Define HTML Body
ob_start();

// Nav Bar
require_once(__DIR__ . '/components/nav_bar.php');

?>

<!-- Page Title -->
<h1 class="books-app-title">Hola!</h1>

<p class="books-app-text gray-text">Well, don't just sit there, do something!</p>

<div class="books-app-menu-container">
    <p class="books-app-text books-app-menu"><a href="book_list.php">> See list of books</a></p>
    <p class="books-app-text books-app-menu"><a href="author_list.php">> See list of authors</a></p>
</div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/page_template.php');