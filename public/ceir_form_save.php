<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/tables.php';
require_role(['ADMIN','CEIR_USER']);
$pdo = getPDO();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = $_POST['thana_table'] ?? '';
    $allowed = get_ceir_tables();
    if (!in_array($table, $allowed)) {
        flash_set('error', 'Invalid thana selected');
        redirect('/ceir_form.php');
    }

    $stmt = $pdo->prepare("INSERT INTO {$table} (name, father_name, address, date_of_complaint, mobile_number, imei, lost_found, block_unblock, pdf_attach, created_by) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['name'] ?? null,
        $_POST['father_name'] ?? null,
        $_POST['address'] ?? null,
        $_POST['mobile_number'] ?? null,
        $_POST['imei'] ?? null,
        $_POST['lost_found'] ?? 'LOST',
        $_POST['block_unblock'] ?? 'BLOCK',
        null,
        $_SESSION['user_id']
    ]);
    flash_set('success', 'CEIR form saved');
    redirect('/ceir_dashboard.php');
}
redirect('/ceir_form.php');
?>