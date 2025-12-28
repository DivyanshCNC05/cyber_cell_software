<?php
require __DIR__ . '/../../includes/db.php';
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/thanas.php';

// If ADMIN is acting as another user, allow access and set $acting_user, otherwise require CYBER_USER
if (($_SESSION['role'] ?? '') === 'ADMIN' && isset($_REQUEST['as_user'])) {
  $acting_user = (int)($_REQUEST['as_user']);
} else {
  require_role('CYBER_USER');
  $acting_user = (int)($_SESSION['user_number'] ?? 0);
}

$allowed = cyber_allowed_thanas_for_logged_user();
$error = '';
$success = '';

function p($k, $d='') { return trim($_POST[$k] ?? $d); }

$selected_thana = $_POST['thana'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if (!in_array($selected_thana, $allowed, true)) {
    $error = 'You are not allowed to add for this thana.';
  } elseif (!isset($CYBER_TABLES[$selected_thana])) {
    $error = 'Invalid thana selected.';
  } else {

    $table = $CYBER_TABLES[$selected_thana]; // whitelisted table name

    $data = [
      ':complaint_number'       => p('complaint_number'),
      ':applicant_name'         => p('applicant_name'),
      ':acknowledgement_number' => p('acknowledgement_number'),
      ':nature_of_fraud'        => p('nature_of_fraud'),
      ':incident_date'          => p('incident_date') ?: null,
      ':complaint_date'         => p('complaint_date'),
      ':total_fraud'            => p('total_fraud') !== '' ? p('total_fraud') : 0,
      ':hold_date'              => p('hold_date') ?: null,
      ':hold_amount'            => p('hold_amount') !== '' ? p('hold_amount') : 0,
      ':refund_amount'          => p('refund_amount') !== '' ? p('refund_amount') : 0,
      ':fraud_mobile_number'    => p('fraud_mobile_number'),
      ':fraud_imei_number'      => p('fraud_imei_number'),
      ':block_or_unblock'       => p('block_or_unblock','UNBLOCK'),
      ':digital_arrest'         => p('digital_arrest') !== '' ? (int)p('digital_arrest') : 0,
      ':digital_amount'         => p('digital_amount') !== '' ? p('digital_amount') : 0,
      ':mobile_number'          => p('mobile_number'),
      ':created_by'             => (int)($_SESSION['user_id'] ?? 0),
    ];

    if ($data[':complaint_date'] === '') {
      $error = 'Complaint date is required.';
    } elseif ($data[':applicant_name'] === '') {
      $error = 'Applicant name is required.';
    } else {

      $sql = "INSERT INTO {$table}
        (complaint_number, applicant_name, acknowledgement_number, nature_of_fraud,
         incident_date, complaint_date, total_fraud, hold_date, hold_amount,
         refund_amount, fraud_mobile_number, fraud_imei_number, block_or_unblock,
         digital_arrest, digital_amount, mobile_number, created_by)
        VALUES
        (:complaint_number, :applicant_name, :acknowledgement_number, :nature_of_fraud,
         :incident_date, :complaint_date, :total_fraud, :hold_date, :hold_amount,
         :refund_amount, :fraud_mobile_number, :fraud_imei_number, :block_or_unblock,
         :digital_arrest, :digital_amount, :mobile_number, :created_by)";

      $stmt = $pdo->prepare($sql);
      $stmt->execute($data);

      $success = "Saved successfully in {$table}. Record ID: " . $pdo->lastInsertId();
      $_POST = [];
      $selected_thana = '';
    }
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Cyber Complaint</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Add Cyber Complaint</h3>
    <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_PATH ?>/dashboards/user<?= $acting_user ?>.php">Back</a>
  </div>

  <?php if (!$allowed): ?>
    <div class="alert alert-warning">
      No thanas assigned to this user_number. Please fill CYBER_ALLOWED_BY_USER in includes/thanas.php.
    </div>
  <?php endif; ?>

  <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

  <form method="post" class="row g-3">
    <input type="hidden" name="as_user" value="<?= htmlspecialchars($acting_user) ?>">

    <div class="col-md-4">
      <label class="form-label">Thana</label>
      <select name="thana" class="form-select" required>
        <option value="">Select</option>
        <?php foreach ($allowed as $key): ?>
          <option value="<?= htmlspecialchars($key) ?>" <?= ($selected_thana === $key) ? 'selected' : '' ?>>
            <?= htmlspecialchars(cyber_thana_label($key)) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label">Complaint Number</label>
      <input class="form-control" name="complaint_number" value="<?= htmlspecialchars($_POST['complaint_number'] ?? '') ?>">
    </div>

    <div class="col-md-4">
      <label class="form-label">Acknowledgement Number</label>
      <input class="form-control" name="acknowledgement_number" value="<?= htmlspecialchars($_POST['acknowledgement_number'] ?? '') ?>">
    </div>

    <div class="col-md-6">
      <label class="form-label">Applicant Name *</label>
      <input class="form-control" name="applicant_name" required value="<?= htmlspecialchars($_POST['applicant_name'] ?? '') ?>">
    </div>

    <div class="col-md-6">
      <label class="form-label">Nature of Fraud</label>
      <input class="form-control" name="nature_of_fraud" value="<?= htmlspecialchars($_POST['nature_of_fraud'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Incident Date</label>
      <input type="date" class="form-control" name="incident_date" value="<?= htmlspecialchars($_POST['incident_date'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Complaint Date *</label>
      <input type="date" class="form-control" name="complaint_date" required value="<?= htmlspecialchars($_POST['complaint_date'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Total Fraud</label>
      <input type="number" step="0.01" class="form-control" name="total_fraud" value="<?= htmlspecialchars($_POST['total_fraud'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Hold Date</label>
      <input type="date" class="form-control" name="hold_date" value="<?= htmlspecialchars($_POST['hold_date'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Hold Amount</label>
      <input type="number" step="0.01" class="form-control" name="hold_amount" value="<?= htmlspecialchars($_POST['hold_amount'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Refund Amount</label>
      <input type="number" step="0.01" class="form-control" name="refund_amount" value="<?= htmlspecialchars($_POST['refund_amount'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Fraud Mobile</label>
      <input class="form-control" name="fraud_mobile_number" value="<?= htmlspecialchars($_POST['fraud_mobile_number'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Fraud IMEI</label>
      <input class="form-control" name="fraud_imei_number" value="<?= htmlspecialchars($_POST['fraud_imei_number'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Block / Unblock</label>
      <select name="block_or_unblock" class="form-select">
        <option value="UNBLOCK">UNBLOCK</option>
        <option value="BLOCK" <?= (($_POST['block_or_unblock'] ?? '') === 'BLOCK') ? 'selected' : '' ?>>BLOCK</option>
      </select>
    </div>

    <div class="col-md-3">
      <label class="form-label">Digital Arrest (count)</label>
      <input type="number" class="form-control" name="digital_arrest" value="<?= htmlspecialchars($_POST['digital_arrest'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Digital Amount</label>
      <input type="number" step="0.01" class="form-control" name="digital_amount" value="<?= htmlspecialchars($_POST['digital_amount'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Mobile Number</label>
      <input class="form-control" name="mobile_number" value="<?= htmlspecialchars($_POST['mobile_number'] ?? '') ?>">
    </div>

    <div class="col-12">
      <button class="btn btn-primary" type="submit">Save</button>
      <a class="btn btn-secondary" href="<?= BASE_PATH ?>/dashboards/user<?= (int)($_SESSION['user_number'] ?? 1) ?>.php">Cancel</a>
    </div>

  </form>
</div>
</body>
</html>
