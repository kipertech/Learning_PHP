<?php

// Reference: https://dev.to/fadymr/php-create-your-own-php-dotenv-3k2i

function loadDotEnv(string $path) :void
{
    if(!file_exists($path)) {
        throw new \InvalidArgumentException(sprintf('%s does not exist', $path));
    }

    if (!is_readable($path)) {
        throw new \RuntimeException(sprintf('%s file is not readable', $path));
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {

        if (str_starts_with(trim($line), '#')) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}
