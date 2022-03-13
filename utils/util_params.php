<?php

// Check if session data is set
function getSessionValue(string $paramName = '', $default_value = ''): bool | string
{
    if(isset($_SESSION[$paramName]) && !empty($_SESSION[$paramName])) {
        return $_SESSION[$paramName];
    }

    return($default_value || '');
}

// Get param from URL
function getParam(string $paramName = '', bool $parseStr = false, bool $isPOST = false, bool $lower_str = true): ?string
{
    $value = null;
    $method = $isPOST ? $_POST : $_GET;

    if (isset($method[$paramName]) && ($method[$paramName] === '0' || $method[$paramName])) {
        $value = $lower_str ? strtolower($method[$paramName]) : $method[$paramName];

        if ($parseStr) {
            // Parse space the input name
            $value = str_replace('+', ' ', $value);
            $value = str_replace('%20', ' ', $value);
        }
    }

    return $value;
}

// Check Numeric input is valid
function checkNumericInput($value = null, $paramName = '', $minimum = 0, $maximum = null, bool $is_float = false, bool $optional = false): ?string
{
    // If input was not found
    if (is_null($value) && !$optional) {
        return(' • Please provide an input for ' . $paramName . '.');
    }

    if (!empty($value)) {
        // If input is non-numeric
        if (!is_numeric($value)) {
            return(' • The input for ' . $paramName . ' is not in a valid numeric format.');
        }

        // Check input value
        if (
            $is_float ?
                (((float)$minimum - (float)$value) > PHP_FLOAT_EPSILON)
                :
                ((int)$value < (int)$minimum)
        ) {
            return(' • The amount of ' . $paramName . ' must be at least ' . $minimum . '.');
        }

        if (
            $is_float ?
                (((float)$value - (float)$maximum) > PHP_FLOAT_EPSILON)
                :
                ($maximum !== null && ((int)$value > (int)$maximum))
        ) {
            return(' • The amount of ' . $paramName . ' cannot be larger than ' . $maximum . '.');
        }
    }

    return '';
}

// Check String input is valid
function checkStringInput($value = null, $paramName = '', array $valid_array = []): ?string
{
    // If input was not found
    if (empty($value)) {
        return(' • Please provide an input for ' . $paramName . '.');
    }

    // If input is not in the supplied list
    $valid_array_lower = array_map(fn($item): string => strtolower($item), $valid_array);
    $valid_input_str = implode(' or ', $valid_array);
    if ((count($valid_array) > 0) && !in_array(strtolower($value), $valid_array_lower)) {
        return(' • "' . $value . '" is not a valid input (expecting ' . $valid_input_str . ')');
    }

    return '';
}

// Check birthday input
function checkBirthdayInput(string $birthday = '', int $minimum_year = 18)
{
    // Check valid string input first
    if (empty(checkStringInput($birthday, 'Birthday'))) {
        $year = (int)substr($birthday, 0, 4);
        $current_year = (int)date("Y");

        return((($current_year - $year) >= $minimum_year) ? '' : ' • Input birthday does not meet minimum requirement.');
    }

    return '';
}