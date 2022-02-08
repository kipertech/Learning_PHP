<?php

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