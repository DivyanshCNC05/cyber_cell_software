<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/tables.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['ADMIN','CYBER_USER']);
$data = report_cyber_summary();
?>
<!doctype html><html><body>
<h3>Cyber Report</h3>
<p>Total complaints: <?= sanitize($data['total_complaints'] ?? 0) ?></p>
<p>Total fraud amount: <?= sanitize($data['total_amount'] ?? 0) ?></p>
</body></html>