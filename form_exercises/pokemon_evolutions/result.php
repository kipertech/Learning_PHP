<?php

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');
require_once(__DIR__ . '/../../utils/util_general.php');

// Import Pokemon List
require_once('./pokemon_list.php');

// Input params
$page_title = "Result | Pokemon Evolutions";
$hide_db_info = true;
$back_link = 'index.php';
$pokemon_list = getPokemonList();

// Get input from param
$chosen = null;
$error = '';
$error_none = '';
if (!isset($_POST['pokemon-list'])) {
    $error = "You didn't choose any pokemon.";
}
else {
    $chosen = $_POST['pokemon-list'];
    if (count($chosen) === 1 && $chosen[0] === 'none') {
        $error = "You didn't choose any pokemon.";
    }
}


// Define HTML Body
ob_start(); ?>

    <!-- Result -->
    <?php
        foreach ($chosen as $id) {
            if ($id !== 'none') {
                $pokemon_item = findObjectById($id, $pokemon_list);
                $pokemon_name = $pokemon_item -> name;
                $evolution = $pokemon_item -> evolution;
                print("<p class='books-app-text'> • <span class='bold-text'>$pokemon_name</span> evolves into <span class='bold-text'>$evolution</span>.</p>");
            }
        }
    ?>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/page_template.php');