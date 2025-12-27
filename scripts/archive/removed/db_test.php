<?php
// Simple diagnostics page to help debug DB and login issues.
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: text/plain; charset=utf-8');

echo "Diagnostics - " . (defined('APP_NAME') ? APP_NAME : 'App') . "\n\n";

// Show DB constants (mask password)
echo "DB_HOST: " . (defined('DB_HOST') ? DB_HOST : '(not defined)') . "\n";
echo "DB_NAME: " . (defined('DB_NAME') ? DB_NAME : '(not defined)') . "\n";
echo "DB_USER: " . (defined('DB_USER') ? DB_USER : '(not defined)') . "\n";
echo "DB_PORT: " . (defined('DB_PORT') ? DB_PORT : '(not defined)') . "\n";

try {
    echo "\nAttempting PDO connection...\n";
    $pdo = getPDO();
    echo "PDO OK\n";

    // Count login users
    $stmt = $pdo->query('SELECT COUNT(*) AS c FROM login_credentials');
    $row = $stmt->fetch();
    echo "login_credentials rows: " . ((int)($row['c'] ?? 0)) . "\n";

    // Show sample users (limit 5)
    $stmt = $pdo->query('SELECT user_id, email, role, is_active, full_name FROM login_credentials ORDER BY user_id ASC LIMIT 5');
    $users = $stmt->fetchAll();
    echo "\nSample users:\n";
    foreach ($users as $u) {
        echo "- #" . $u['user_id'] . " " . $u['email'] . " role=" . $u['role'] . " active=" . $u['is_active'] . " name=" . ($u['full_name'] ?? '') . "\n";
    }

    // Verify known password for seeded admin (password: 'password')
    $stmt = $pdo->prepare('SELECT password_hash FROM login_credentials WHERE email = ? LIMIT 1');
    $stmt->execute(['admin@cybercell.in']);
    $h = $stmt->fetchColumn();
    if ($h) {
        echo "\nVerifying seeded admin password (\"password\"): ";
        echo password_verify('password', $h) ? "OK\n" : "FAIL\n";
    } else {
        echo "\nSeeded admin not found to verify password.\n";
    }

} catch (Exception $e) {
    echo "PDO ERROR: " . $e->getMessage() . "\n";
}

// Show session and CSRF token info
echo "\nSession ID: " . session_id() . "\n";
echo "CSRF token (session): " . (isset($_SESSION['_csrf']) ? $_SESSION['_csrf'] : '(none)') . "\n";

// Helpful next steps
echo "\nIf PDO connection failed, verify credentials in .env and remote access from your host (Hostinger may block external connections).\n";
echo "If 'Verifying seeded admin password' shows FAIL, your DB may not have the seeded users or password hashes differ.\n";
echo "If CSRF token is missing, ensure your browser accepts cookies and session works.\n";

?>