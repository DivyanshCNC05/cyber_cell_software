<?php
// config.php - reads .env (if present) and defines constants

// Simple .env reader (supports PHP array returning .env or KEY=VALUE files)
$env_path = __DIR__ . '/../.env';
if (file_exists($env_path)) {
    $contents = file_get_contents($env_path);
    // If the file looks like PHP code that returns an array, include it and map keys
    if (strpos($contents, '<?php') !== false) {
        try {
            $env = include $env_path;
            if (is_array($env)) {
                if (isset($env['host']))  { if (!getenv('DB_HOST')) putenv('DB_HOST=' . $env['host']);  if (!defined('DB_HOST'))  define('DB_HOST', $env['host']); }
                if (isset($env['user']))  { if (!getenv('DB_USER')) putenv('DB_USER=' . $env['user']);  if (!defined('DB_USER'))  define('DB_USER', $env['user']); }
                if (isset($env['pass']))  { if (!getenv('DB_PASS')) putenv('DB_PASS=' . $env['pass']);  if (!defined('DB_PASS'))  define('DB_PASS', $env['pass']); }
                if (isset($env['dbname'])){ if (!getenv('DB_NAME')) putenv('DB_NAME=' . $env['dbname']); if (!defined('DB_NAME')) define('DB_NAME', $env['dbname']); }
                if (isset($env['port']))  { if (!getenv('DB_PORT')) putenv('DB_PORT=' . $env['port']);  if (!defined('DB_PORT'))  define('DB_PORT', $env['port']); }
            }
        } catch (Throwable $e) {
            // If include failed, fall back to KEY=VALUE parsing below
        }
    } else {
        // Fallback: parse KEY=VALUE style .env
        $lines = file($env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            if (strpos($line, '=') === false) continue;
            list($k, $v) = array_map('trim', explode('=', $line, 2));
            $v = trim($v, "\"'");
            if (!getenv($k)) putenv("$k=$v");
            if (!defined($k)) define($k, $v);
        }
    }
}

// Fallback constants if not defined via .env or environment
if (!defined('DB_HOST')) define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
if (!defined('DB_NAME')) define('DB_NAME', getenv('DB_NAME') ?: 'cybercell_db');
if (!defined('DB_USER')) define('DB_USER', getenv('DB_USER') ?: 'root');
if (!defined('DB_PASS')) define('DB_PASS', getenv('DB_PASS') ?: '');
if (!defined('APP_NAME')) define('APP_NAME', getenv('APP_NAME') ?: 'Cybercell');
if (!defined('BASE_PATH')) define('BASE_PATH', getenv('BASE_PATH') ?: '/');

// Basic security and session
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
session_start();

?>