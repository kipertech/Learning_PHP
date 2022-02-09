<?php

function findObjectById($id, array $array = []) {
    foreach ($array as $element) {
        if ((string)$id === (string)$element -> id) {
            return $element;
        }
    }

    return false;
}