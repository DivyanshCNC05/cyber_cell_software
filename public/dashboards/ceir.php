<?php
require __DIR__ . '/../../includes/auth.php';
// Allow ADMIN to access CEIR dashboard
if (($_SESSION['role'] ?? '') === 'ADMIN') {
  // admin allowed
} else {
  require_role('CEIR_USER');
}
$title = "CEIR Dashboard";
require __DIR__ . '/../../templates/header.php';
?> 

<h3 class="mb-3">CEIR Dashboard</h3>

<div class="row g-3">
  <div class="col-md-6"><a class="text-decoration-none" href="<?= BASE_PATH ?>/ceir/add.php">
    <div class="card h-100"><div class="card-body"><h5 class="card-title">Add new complaint</h5></div></div>
  </a></div> 

  <div class="col-md-6"><a class="text-decoration-none" href="<?= BASE_PATH ?>/ceir/list.php">
    <div class="card h-100"><div class="card-body"><h5 class="card-title">Update and search form</h5></div></div>
  </a></div> 

  <div class="col-md-6"><a class="text-decoration-none" href="<?= BASE_PATH ?>/ceir/list.php">
    <div class="card h-100"><div class="card-body"><h5 class="card-title">Delete and search form</h5></div></div>
  </a></div> 

  <div class="col-md-6"><a class="text-decoration-none" href="<?= BASE_PATH ?>/ceir/report.php">
    <div class="card h-100"><div class="card-body"><h5 class="card-title">Generate Report (From date to date)</h5></div></div>
  </a></div> 
</div>


