<?php

// Get param from URL
function getParam(string $paramName = ''): ?string
{
    $value = null;

    if (isset($_GET[$paramName]) && ($_GET[$paramName] === '0' || $_GET[$paramName])) {
        $value = strtolower($_GET[$paramName]);
    }

    return $value;
}

// Check if Goats/Course Length
function checkNumericInput($value = null, $paramName = '', $minimum = 0, $maximum = null): ?string
{
    // If input was not found
    if (is_null($value)) {
        return(' • Please provide an input for ' . $paramName . '.');
    }

    // If input is non-numeric
    if (!is_numeric($value)) {
        return(' • The input for ' . $paramName . ' is not in a valid numeric format.');
    }

    // Check input value
    if ((int)$value < (int)$minimum) {
        return(' • The amount of ' . $paramName . ' must be at least ' . $minimum . '.');
    }

    // If input value is above the maximum
    if ($maximum !== null) {
        if ((int)$value > (int)$maximum) {
            return(' • The amount of ' . $paramName . ' cannot be larger than ' . $maximum . '.');
        }
    }

    return '';
}