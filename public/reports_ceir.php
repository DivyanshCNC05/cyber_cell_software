<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/tables.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['ADMIN','CEIR_USER']);
$data = report_ceir_summary();
?>
<!doctype html><html><body>
<h3>CEIR Report</h3>
<p>Total forms: <?= sanitize($data['total_forms'] ?? 0) ?></p>
<p>Total block actions: <?= sanitize($data['total_block_actions'] ?? 0) ?></p>
</body></html>