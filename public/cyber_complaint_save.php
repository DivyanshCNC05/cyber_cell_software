<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/tables.php';
require_role(['ADMIN','CYBER_USER']);
$pdo = getPDO();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = $_POST['thana_table'] ?? '';
    $allowed = get_cyber_tables();
    if (!in_array($table, $allowed)) {
        flash_set('error', 'Invalid thana selected');
        redirect('/cyber_complaint_form.php');
    }

    $stmt = $pdo->prepare("INSERT INTO {$table} (applicant_name, nature_of_fraud, incident_date, complaint_date, created_by) VALUES (?, ?, ?, NOW(), ?)");
    $stmt->execute([$_POST['applicant_name'] ?? null, $_POST['nature_of_fraud'] ?? null, $_POST['incident_date'] ?: null, $_SESSION['user_id']]);
    flash_set('success', 'Complaint saved');
    redirect('/cyber_dashboard.php');
}
redirect('/cyber_complaint_form.php');
?>