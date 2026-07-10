<?php

function load_env(string $path): void
{
    if (!is_file($path)) {
        return;
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        if (
            (substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
            (substr($value, 0, 1) === "'" && substr($value, -1) === "'")
        ) {
            $value = substr($value, 1, -1);
        }

        $_ENV[$key] = $value;
        putenv($key . '=' . $value);
    }
}

function env_value(string $key, $default = null)
{
    $value = $_ENV[$key] ?? getenv($key);
    return $value === false || $value === null ? $default : $value;
}

load_env(dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . '.env');

define('APP_NAME', env_value('APP_NAME', 'PasarKita'));
define('APP_ENV', env_value('APP_ENV', 'local'));
define('APP_DEBUG', filter_var(env_value('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN));
define('BASEURL', rtrim(env_value('BASEURL', 'http://localhost/pasarkita/public/'), '/') . '/');
define('DB_HOST', env_value('DB_HOST', 'localhost'));
define('DB_USER', env_value('DB_USER', 'root'));
define('DB_PASS', env_value('DB_PASS', ''));
define('DB_NAME', env_value('DB_NAME', 'pasarkita'));
