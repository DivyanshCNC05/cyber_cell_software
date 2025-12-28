<?php
require __DIR__ . 'access.php';
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Print</title>
  <style>@media print { .no-print { display:none; } }</style>
</head>
<body>
<div class="no-print">
  <button onclick="window.print()">Print</button>
  <button onclick="history.back()">Back</button>
</div>
</body>
</html>
