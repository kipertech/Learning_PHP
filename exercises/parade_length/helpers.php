<?php

// Get param from URL
function getParam(string $paramName = ''): ?int
{
    $value = null;

    if (isset($_GET[$paramName]) && !empty($_GET[$paramName])) {
        $value = strtolower($_GET[$paramName]);
    }

    return $value;
}

// Check if Float/Boat input is valid
function checkInput($value = null, $paramName = ''): ?string
{
    if (empty($value) || empty($paramName)) return(' • Please provide number of ' . $paramName . '.');

    $error_message = '';

    if (!is_numeric($value)) {
        $error_message = ' • The number of ' . $paramName . ' is not in valid format.';
    }
    else if ($value < 0) {
        $error_message = ' • The number of ' . $paramName . ' cannot be less than 0.';
    }

    return $error_message;
}

