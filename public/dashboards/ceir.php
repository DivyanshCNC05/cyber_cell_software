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
  <div class="col-md-4"><a class="text-decoration-none" href="<?= BASE_PATH ?>/ceir/add.php">
    <div class="card h-100"><div class="card-body"><h5 class="card-title">Add</h5><p class="card-text">Add CEIR form + PDF</p></div></div>
  </a></div> 

  <div class="col-md-4"><a class="text-decoration-none" href="<?= BASE_PATH ?>/ceir/list.php">
    <div class="card h-100"><div class="card-body"><h5 class="card-title">Update</h5><p class="card-text">Search and update form</p></div></div>
  </a></div> 

  <div class="col-md-4"><a class="text-decoration-none" href="<?= BASE_PATH ?>/ceir/list.php">
    <div class="card h-100"><div class="card-body"><h5 class="card-title">Delete</h5><p class="card-text">Search and delete form</p></div></div>
  </a></div> 

  <div class="col-md-4"><a class="text-decoration-none" href="<?= BASE_PATH ?>/ceir/report.php">
    <div class="card h-100"><div class="card-body"><h5 class="card-title">Generate Report</h5><p class="card-text">From date to date</p></div></div>
  </a></div> 

  <div class="col-md-4"><a class="text-decoration-none" href="<?= BASE_PATH ?>/ceir/print.php">
    <div class="card h-100"><div class="card-body"><h5 class="card-title">Print</h5><p class="card-text">Print report</p></div></div>
  </a></div> 
</div>

<?php require __DIR__ . '/../../templates/footer.php'; ?>
