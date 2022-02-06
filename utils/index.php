<?php

// Get param from URL
function getParam(string $paramName = '')
{
    $value = null;

    if (isset($_GET[$paramName]) && !empty($_GET[$paramName])) {
        $value = strtolower($_GET[$paramName]);
    }

    return $value;
}

// Check if input value exists in array
function checkInputValid(string $inputValue = '', array $valueArray = [])
{
    if (empty($inputValue) || count($valueArray) === 0) return false;

    return(in_array($inputValue, $valueArray));
}


