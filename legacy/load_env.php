<?php
// Lightweight .env loader. Safe to include multiple times.
if (!defined('ENV_LOADED')) {
    define('ENV_LOADED', true);
    $dotenvPath = __DIR__ . DIRECTORY_SEPARATOR . '.env';
    if (file_exists($dotenvPath) && is_readable($dotenvPath)) {
        $lines = file($dotenvPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '#') === 0) {
                continue;
            }
            if (preg_match('/^(?:export\s+)?([A-Za-z_][A-Za-z0-9_]*)\s*=\s*(.*)$/', $line, $m)) {
                $name = $m[1];
                $value = $m[2];
                if ((strlen($value) >= 2 && $value[0] === '"' && $value[strlen($value)-1] === '"') || (strlen($value) >= 2 && $value[0] === "'" && $value[strlen($value)-1] === "'")) {
                    $value = substr($value, 1, -1);
                }
                $value = str_replace(['\\n', '\\r', '\\"', "\\'"], ["\n", "\r", '"', "'"] , $value);
                putenv("{$name}={$value}");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}
