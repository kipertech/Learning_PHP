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
function checkInput($value = null, $paramName = '', $minimum = 0, $maximum = 0, $isFloat = false): ?string
{
    // If input was not found
    if (is_null($value)) {
        return(' • Please provide an input for ' . $paramName . '.');
    }

    // If input is non-numeric
    if (!is_numeric($value)) {
        return(' • The input for ' . $paramName . ' is not in a valid numeric format.');
    }

    // Check float input
    if ($isFloat) {
        if (((float)$minimum - (float)$value) > PHP_FLOAT_EPSILON) {
            return(' • The amount of ' . $paramName . ' must be at least ' . $minimum . '.');
        }

        if (((float)$value - (float)$maximum) > PHP_FLOAT_EPSILON) {
            return(' • The amount of ' . $paramName . ' cannot be larger than ' . $maximum . '.');
        }
    }
    // Check int input
    else {
        if ((int)$value < (int)$minimum) {
            return(' • The amount of ' . $paramName . ' must be at least ' . $minimum . '.');
        }

        // If input value is above the maximum
        if ((int)$value > (int)$maximum) {
            return(' • The amount of ' . $paramName . ' cannot be larger than ' . $maximum . '.');
        }
    }

    return '';
}
