<?php

function findObjectById($id, array $array = []) {
    foreach ($array as $element) {
        if ((string)$id === (string)$element -> id) {
            return $element;
        }
    }

    return false;
}

function errorGenerator(array $error_messages = []) {
    if (count($error_messages) < 1) return '';

    $error = '';

    foreach ($error_messages as $index => $element) {
        $connector = !empty($error) ? "\n" : "";
        $error = $error . $connector . $element;
    }

    return $error;
}