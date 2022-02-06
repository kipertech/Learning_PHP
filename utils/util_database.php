<?php

// Load .env
require_once(__DIR__ . './util_dotenv.php');
use KiperTech\DotEnv;

$dotenv_path = __DIR__ . '/../.env';
if (file_exists($dotenv_path)) {
    (new DotEnv($dotenv_path)) -> load();
}

// Load DB Connection
function getDBConnection(): object
{
    $mysqli = null;
    $connection_error = '';

    $database_host = '';
    if (!empty($database_host))
    {
        // Connect to DB
        $mysqli = new mysqli(
            $database_host,
            $_SERVER['RDS_USERNAME'],
            $_SERVER['RDS_PASSWORD'],
            $_SERVER['RDS_DB_NAME'],
            $_SERVER['RDS_PORT']
        );

        // Check connection
        if ($mysqli -> connect_errno) {
            $connection_error = "Failed to connect to MySQL: " . $mysqli -> connect_error;
            $mysqli = null;
        }
    }
    else $connection_error = 'Failed to load environment variables';

    return((object)[
        'connection_error' => $connection_error,
        'mysqli' => $mysqli
    ]);
}

// Single-fetch Query
function simpleQueryFetch($mysqli, $query, $param, $singleResult = false, $closeAfterDone = false): mysqli_result|array|null|false
{
    $row = null;
    $result_list = [];

    if ($stmt = $mysqli -> prepare($query)) {
        // Bind parameter
        $stmt -> bind_param("i", $param);

        // Execute query
        $stmt -> execute();

        // Get record based on input param
        $result_set = $stmt -> get_result(); /* Return an array of data */
        if ($singleResult) {
            $row = $result_set -> fetch_assoc() /* Sequentially fetch data in that array */;
        }
        else {
            while ($row = $result_set -> fetch_assoc()) {
                $result_list[] = $row;
            }
        }

        // Free result set
        $stmt -> free_result();
    }

    // Close connection
    if ($closeAfterDone) $mysqli -> close();

    // Return value
    return($singleResult ? $row : $result_list);
}