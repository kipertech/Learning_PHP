<?php

$page_title = "Wombat List";

// Load helpers
require_once(__DIR__ . '/../../utils/util_params.php');
require_once(__DIR__ . '/../../utils/util_database.php');

$start = microtime(true);

// Initialize values
$total_time = 0;
$result_list = [];
$error = '';

// If input is valid
if (empty($error)) {
    // Connect to DB
    $dbObject = getDBConnection();
    $mysqli = $dbObject -> mysqli;
    $error = $dbObject -> connection_error;

    // If successfully connected to DB
    if (empty($error)) {
        // Perform query
        $query = "SELECT * FROM wombats";
        $result_list = simpleQueryFetch($mysqli, $query, '', false, true);

        // If ID is not found
        if (count($result_list) === 0) {
            $error = ' • No wombat was found in the database';
        }

        // Log execution time
        $total_time = number_format(microtime(true) - $start, 2, '.', ',');
    }
}

// Define HTML Body
$page_body = '';
foreach ($result_list as $row) {
    $wombat_name = $row['name'];
    $detail_url = 'wombat_detail.php?id=' . $row['wombat_id'];
    $page_body .= "<p> • <a href=$detail_url>$wombat_name</a></p>";
}

// Import HTML Template
require_once('../../components/page_template.php');