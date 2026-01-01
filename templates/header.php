<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title ?? 'Dashboard') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= BASE_PATH ?>/assets/css/style.css" rel="stylesheet">
</head>
<?php
$uri = $_SERVER['REQUEST_URI'] ?? '';
$pageClass = '';
if (strpos($uri, '/dashboards/admin.php') !== false) { $pageClass = ' admin-bg'; }
if (
  strpos($uri, '/dashboards/user1.php') !== false ||
  strpos($uri, '/dashboards/user2.php') !== false ||
  strpos($uri, '/dashboards/user3.php') !== false ||
  strpos($uri, '/dashboards/ceir.php') !== false
)  { $pageClass = ' user1-bg'; }
?>
<body class="<?= htmlspecialchars('bg-light' . $pageClass) ?>">
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-cust-logo"><img src="<?= BASE_PATH ?>/assets/image/cyber-logo.png" alt="Cyber Cell Logo" style="height: 6rem; padding-left: 1rem;"></span>
    <a class="btn btn-outline-light btn-sm" href="<?= BASE_PATH ?>/logout.php">Logout</a>
  </div>
</nav>
<div class="container py-4">
<?php echo '<!-- DEBUG: BASE_PATH: ' . (defined('BASE_PATH') ? BASE_PATH : '') . ' | REQUEST_URI: ' . ($_SERVER['REQUEST_URI'] ?? '') . ' | SCRIPT_NAME: ' . ($_SERVER['SCRIPT_NAME'] ?? '') . ' -->'; ?>

