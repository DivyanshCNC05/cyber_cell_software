<?php
require __DIR__ . '/../../includes/auth.php';
require_cyber_user_number(1);
// debug log
error_log('DEBUG: user1.php requested, REQUEST_URI=' . ($_SERVER['REQUEST_URI'] ?? '')); 
$title = "User1 Dashboard";
require __DIR__ . '/../../templates/header.php';
echo '<!-- DEBUG: user1_requested: ' . ($_SERVER['REQUEST_URI'] ?? '') . ' -->';
?> 

<h3 class="mb-3">User1 Dashboard</h3>

<div class="row g-3">
  <div class="col-md-4">
    <a class="text-decoration-none" href="<?= BASE_PATH ?>/cyber/add.php">
      <div class="card h-100"><div class="card-body">
        <h5 class="card-title">Add</h5>
        <p class="card-text">Add new cyber complaint</p>
      </div></div>
    </a>
  </div>

  <div class="col-md-4">
    <a class="text-decoration-none" href="<?= BASE_PATH ?>/cyber/list.php">
      <div class="card h-100"><div class="card-body">
        <h5 class="card-title">Update</h5>
        <p class="card-text">Search and update complaint</p>
      </div></div>
    </a>
  </div>

  <div class="col-md-4">
    <a class="text-decoration-none" href="<?= BASE_PATH ?>/cyber/list.php">
      <div class="card h-100"><div class="card-body">
        <h5 class="card-title">Delete</h5>
        <p class="card-text">Search and delete complaint</p>
      </div></div>
    </a>
  </div>

  <div class="col-md-4">
    <a class="text-decoration-none" href="<?= BASE_PATH ?>/cyber/report.php">
      <div class="card h-100"><div class="card-body">
        <h5 class="card-title">Generate Report</h5>
        <p class="card-text">From date to date</p>
      </div></div>
    </a>
  </div>

  <div class="col-md-4">
    <a class="text-decoration-none" href="<?= BASE_PATH ?>/cyber/print.php">
      <div class="card h-100"><div class="card-body">
        <h5 class="card-title">Print</h5>
        <p class="card-text">Print report</p>
      </div></div>
    </a>
  </div>
</div>

<?php require __DIR__ . '/../../templates/footer.php'; ?>
