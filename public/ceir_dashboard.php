<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['CEIR_USER']);
$user = current_user();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars(APP_NAME) ?> - CEIR Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">CEIR Dashboard</a>
    <div class="d-flex">
      <span class="navbar-text me-3"><?= sanitize($user['full_name'] ?? '') ?></span>
      <a class="btn btn-light" href="/logout.php">Logout</a>
    </div>
  </div>
</nav>
<div class="container py-5">
  <h3>CEIR Dashboard</h3>
  <div class="row gy-3 mt-3">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Add</h5>
          <p class="card-text">Add new CEIR form</p>
          <a href="/ceir_form.php" class="btn btn-primary">Add</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Update</h5>
          <p class="card-text">Update CEIR entries</p>
          <a href="/reports_ceir.php" class="btn btn-secondary">Manage</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Delete</h5>
          <p class="card-text">Delete CEIR records</p>
          <a href="/reports_ceir.php" class="btn btn-danger">Delete</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Generate Report</h5>
          <p class="card-text">Generate CEIR reports</p>
          <a href="/reports_ceir.php" class="btn btn-primary">Generate</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Print</h5>
          <p class="card-text">Print-friendly CEIR reports</p>
          <a href="/reports_ceir.php?print=1" class="btn btn-outline-primary">Print</a>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>