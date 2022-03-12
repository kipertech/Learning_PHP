<?php

// Load .env on local environment
require_once(__DIR__ . '/util_dotenv.php');
loadDotEnv(__DIR__ . '/../.env');

// Load DB Connection
function getDBConnection(): object
{
    $mysqli = null;
    $connection_error = '';

    // Connect to DB
    $mysqli = new mysqli(
        $_SERVER['RDS_HOSTNAME'],
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

    return((object)[
        'connection_error' => $connection_error,
        'mysqli' => $mysqli
    ]);
}

// Simple fetch Query
function simpleQueryFetch($mysqli, $query, $param, $singleResult = false, $closeAfterDone = false, bool $stringInput = false): mysqli_result|array|null|false
{
    $row = null;
    $result_list = [];

    if ($stmt = $mysqli -> prepare($query)) {
        // Bind parameter
        if ($param) $stmt -> bind_param($stringInput ? "s" : "i", $param);

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

// Simple insert query
function simpleQueryInsert($mysqli, $query, $closeAfterDone = false): array|null|false|object
{
    $last_id = null;
    $error = '';

    if ($mysqli -> query($query) === true) {
        $last_id = $mysqli -> insert_id;
    }
    else $error = $mysqli -> error;

    // Close connection
    if ($closeAfterDone) $mysqli -> close();

    // Return value
    return((object)[
        'last_id' => $last_id,
        'error' => $error
    ]);
}

// Simple update query
function simpleQueryUpdate($mysqli, $query, $closeAfterDone = false): string|null|false
{
    $error = '';

    if ($mysqli -> query($query) !== true) {
        $error = $mysqli -> error;
    }

    // Close connection
    if ($closeAfterDone) $mysqli -> close();

    // Return value
    return($error);
}