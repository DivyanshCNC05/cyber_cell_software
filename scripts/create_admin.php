<?php
// scripts/create_admin.php
// Usage: php create_admin.php email password [full_name] [role]

require_once __DIR__ . '/../includes/db.php';

if (PHP_SAPI !== 'cli') {
    echo "This script must be run from the command line.\n";
    exit(1);
}

if ($argc < 3) {
    echo "Usage: php create_admin.php email password [full_name] [role]\n";
    exit(1);
}

$email = $argv[1];
$password = $argv[2];
$full_name = $argv[3] ?? 'Administrator';
$role = strtoupper($argv[4] ?? 'ADMIN');

$pdo = getPDO();

// check user exists
$check = $pdo->prepare('SELECT 1 FROM login_credentials WHERE email = ?');
$check->execute([$email]);
if ($check->fetchColumn()) {
    echo "User '$email' already exists.\n";
    exit(1);
}

if (strlen($password) < 8) {
    echo "Warning: the password is shorter than 8 characters — consider using a stronger password.\n";
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$insert = $pdo->prepare('INSERT INTO login_credentials (email, password_hash, role, full_name, is_active, created_at) VALUES (?, ?, ?, ?, 1, NOW())');
$insert->execute([$email, $hash, $role, $full_name]);

echo "Admin user '$email' created with role '$role'.\n";
