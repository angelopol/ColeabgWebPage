<?php
// Simple diagnostic helper to check DB configuration sources without exposing secrets.
// Place this file in the project root and visit via browser to see which source was detected.
require __DIR__ . '/conn.php';

// Do not reveal credentials; only show which source provided them and whether a connection succeeded.
$info = ['env_dsn' => (bool)getenv('DB_DSN'), 'env_user' => (bool)getenv('DB_USER')];
// check .env existence
$dotenvPath = __DIR__ . DIRECTORY_SEPARATOR . '.env';
$info['dotEnvExists'] = file_exists($dotenvPath) && is_readable($dotenvPath);
// check src/db_config.php
$localCfgPath = __DIR__ . DIRECTORY_SEPARATOR . 'db_config.php';
$info['localCfgExists'] = file_exists($localCfgPath) && is_readable($localCfgPath);

header('Content-Type: application/json');
// Try to open a connection but avoid printing exception details that might leak info.
try {
    $pdo = DataConnect();
    $info['connection'] = 'ok';
} catch (Throwable $e) {
    $info['connection'] = 'failed';
}

echo json_encode($info, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
