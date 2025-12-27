<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/tables.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/cyber_dashboard.php');
if (!csrf_check($_POST['csrf_token'] ?? '')) {
    flash_set('error', 'Invalid CSRF token');
    redirect('/cyber_dashboard.php');
}
$table = $_POST['table'] ?? '';
$id = $_POST['id'] ?? '';
$tables = get_cyber_tables();
if (!in_array($table, $tables) || !$id || !is_numeric($id)) {
    flash_set('error', 'Invalid parameters');
    redirect('/cyber_dashboard.php');
}
$pdo = getPDO();
$stmt = $pdo->prepare("DELETE FROM {$table} WHERE sno = ?");
$stmt->execute([$id]);
flash_set('success', 'Record deleted');
redirect('/cyber_list.php?table=' . urlencode($table));
