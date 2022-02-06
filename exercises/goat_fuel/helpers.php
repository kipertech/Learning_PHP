<?php

// Get param from URL
function getParamFromGet(string $paramName = ''): ?string
{
    $value = null;

    if (isset($_GET[$paramName]) && ($_GET[$paramName] === '0' || $_GET[$paramName])) {
        $value = strtolower($_GET[$paramName]);
    }

    return $value;
}

// Check if Goats/Course Length
function checkInput($value = null, $paramName = '', $minimum = 0): ?string
{
    // If input was not found
    if (is_null($value)) {
        return(' • Please provide an input for ' . $paramName . '.');
    }

    // If input is non-numeric
    if (!is_numeric($value)) {
        return(' • The input for ' . $paramName . ' is not in a valid numeric format.');
    }

    // If input value is below the minimum
    if ((int)$value < (int)$minimum) {
        return(' • The amount of ' . $paramName . ' must be at least ' . $minimum . '.');
    }

    return '';
}

// Check if Hurdles input is valid
function checkHurdles($value = null): ?string
{
    // If nothing was input
    if (empty($value)) {
        return(' • Please indicate whether you want Hurdles or not.');
    }

    // Determine Error Message
    $error_message = '';
    $lowerValue = strtolower($value);

    if ($lowerValue !== 'n' && $lowerValue !== 'y') {
        $error_message = ' • Your input for Hurdles is not in a valid format (must be either Y or N).';
    }

    return $error_message;
}
