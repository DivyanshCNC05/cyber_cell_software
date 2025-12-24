<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/reports.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['ADMIN']);
$cyber = report_cyber_summary();
$ceir = report_ceir_summary();
?>
<!doctype html><html><body>
<h3>Admin Reports</h3>
<h4>Cyber</h4>
<p>Total complaints: <?= sanitize($cyber['total_complaints'] ?? 0) ?></p>
<p>Total fraud amount: <?= sanitize($cyber['total_amount'] ?? 0) ?></p>
<h4>CEIR</h4>
<p>Total forms: <?= sanitize($ceir['total_forms'] ?? 0) ?></p>
<p>Total block actions: <?= sanitize($ceir['total_block_actions'] ?? 0) ?></p>
</body></html>