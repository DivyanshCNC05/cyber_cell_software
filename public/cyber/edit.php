<?php
require __DIR__ . '/../../includes/db.php';
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/thanas.php';

// Allow ADMIN to act as a user if as_user is provided
if (($_SESSION['role'] ?? '') === 'ADMIN' && isset($_REQUEST['as_user'])) {
  $acting_user = (int)($_REQUEST['as_user']);
} else {
  require_role('CYBER_USER');
  $acting_user = (int)($_SESSION['user_number'] ?? 0);
}

$allowed = cyber_allowed_thanas_for_logged_user();
if (!$allowed) die('No thanas assigned.');

function p($k, $d='') { return trim($_POST[$k] ?? $d); }

$thana = $_GET['thana'] ?? '';
$sno   = isset($_GET['sno']) ? (int)$_GET['sno'] : 0;

if (!in_array($thana, $allowed, true) || !isset($CYBER_TABLES[$thana])) {
    die('Invalid / unauthorized thana.');
}
if ($sno <= 0) die('Invalid S.No.');

$table = $CYBER_TABLES[$thana];

$error = '';
$success = '';

/* 1) Fetch record */
$stmt = $pdo->prepare("SELECT * FROM {$table} WHERE sno = :sno LIMIT 1");
$stmt->execute([':sno' => $sno]);
$row = $stmt->fetch();

if (!$row) die('Record not found.'); // fetch() returns false if no row [web:334]

/* 2) Update on POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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
      ':block_or_unblock'       => p('block_or_unblock', 'UNBLOCK'),
      ':digital_arrest'         => p('digital_arrest') !== '' ? (int)p('digital_arrest') : 0,
      ':digital_amount'         => p('digital_amount') !== '' ? p('digital_amount') : 0,
      ':mobile_number'          => p('mobile_number'),
      ':sno'                    => $sno,
    ];

    if ($data[':complaint_date'] === '') {
        $error = 'Complaint date is required.';
    } elseif ($data[':applicant_name'] === '') {
        $error = 'Applicant name is required.';
    } else {
        $sql = "UPDATE {$table} SET
            complaint_number       = :complaint_number,
            applicant_name         = :applicant_name,
            acknowledgement_number = :acknowledgement_number,
            nature_of_fraud        = :nature_of_fraud,
            incident_date          = :incident_date,
            complaint_date         = :complaint_date,
            total_fraud            = :total_fraud,
            hold_date              = :hold_date,
            hold_amount            = :hold_amount,
            refund_amount          = :refund_amount,
            fraud_mobile_number    = :fraud_mobile_number,
            fraud_imei_number      = :fraud_imei_number,
            block_or_unblock       = :block_or_unblock,
            digital_arrest         = :digital_arrest,
            digital_amount         = :digital_amount,
            mobile_number          = :mobile_number
          WHERE sno = :sno
          LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

        // Redirect back to list (prevents resubmit on refresh)
        $q = 'thana=' . urlencode($thana) . '&updated=1';
        if (isset($acting_user) && $acting_user) { $q .= '&as_user=' . $acting_user; }
        header('Location: ' . BASE_PATH . '/cyber/list.php?' . $q);
        exit;
    }
}

// refresh $row for display if error (keep posted values)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $error) {
    $row = array_merge($row, [
        'complaint_number'       => $_POST['complaint_number'] ?? '',
        'applicant_name'         => $_POST['applicant_name'] ?? '',
        'acknowledgement_number' => $_POST['acknowledgement_number'] ?? '',
        'nature_of_fraud'        => $_POST['nature_of_fraud'] ?? '',
        'incident_date'          => $_POST['incident_date'] ?? '',
        'complaint_date'         => $_POST['complaint_date'] ?? '',
        'total_fraud'            => $_POST['total_fraud'] ?? '',
        'hold_date'              => $_POST['hold_date'] ?? '',
        'hold_amount'            => $_POST['hold_amount'] ?? '',
        'refund_amount'          => $_POST['refund_amount'] ?? '',
        'fraud_mobile_number'    => $_POST['fraud_mobile_number'] ?? '',
        'fraud_imei_number'      => $_POST['fraud_imei_number'] ?? '',
        'block_or_unblock'       => $_POST['block_or_unblock'] ?? 'UNBLOCK',
        'digital_arrest'         => $_POST['digital_arrest'] ?? '',
        'digital_amount'         => $_POST['digital_amount'] ?? '',
        'mobile_number'          => $_POST['mobile_number'] ?? '',
    ]);
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Cyber Complaint</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Edit Complaint (<?= htmlspecialchars(cyber_thana_label($thana)) ?>) - S.No <?= (int)$sno ?></h3>
    <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_PATH ?>/cyber/list.php?thana=<?= urlencode($thana) ?><?= isset($acting_user) && $acting_user ? '&as_user=' . $acting_user : '' ?>">Back</a>
  </div>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" class="row g-3">
    <input type="hidden" name="as_user" value="<?= htmlspecialchars($acting_user) ?>">
    <div class="col-md-4">
      <label class="form-label">Complaint Number</label>
      <input class="form-control" name="complaint_number" value="<?= htmlspecialchars($row['complaint_number'] ?? '') ?>">
    </div>

    <div class="col-md-8">
      <label class="form-label">Applicant Name *</label>
      <input class="form-control" name="applicant_name" required value="<?= htmlspecialchars($row['applicant_name'] ?? '') ?>">
    </div>

    <div class="col-md-4">
      <label class="form-label">Acknowledgement Number</label>
      <input class="form-control" name="acknowledgement_number" value="<?= htmlspecialchars($row['acknowledgement_number'] ?? '') ?>">
    </div>

    <div class="col-md-8">
      <label class="form-label">Nature of Fraud</label>
      <input class="form-control" name="nature_of_fraud" value="<?= htmlspecialchars($row['nature_of_fraud'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Incident Date</label>
      <input type="date" class="form-control" name="incident_date" value="<?= htmlspecialchars($row['incident_date'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Complaint Date *</label>
      <input type="date" class="form-control" name="complaint_date" required value="<?= htmlspecialchars($row['complaint_date'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Total Fraud</label>
      <input type="number" step="0.01" class="form-control" name="total_fraud" value="<?= htmlspecialchars($row['total_fraud'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Hold Date</label>
      <input type="date" class="form-control" name="hold_date" value="<?= htmlspecialchars($row['hold_date'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Hold Amount</label>
      <input type="number" step="0.01" class="form-control" name="hold_amount" value="<?= htmlspecialchars($row['hold_amount'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Refund Amount</label>
      <input type="number" step="0.01" class="form-control" name="refund_amount" value="<?= htmlspecialchars($row['refund_amount'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Fraud Mobile</label>
      <input class="form-control" name="fraud_mobile_number" value="<?= htmlspecialchars($row['fraud_mobile_number'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Fraud IMEI</label>
      <input class="form-control" name="fraud_imei_number" value="<?= htmlspecialchars($row['fraud_imei_number'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Block / Unblock</label>
      <select name="block_or_unblock" class="form-select">
        <option value="UNBLOCK" <?= (($row['block_or_unblock'] ?? '') === 'UNBLOCK') ? 'selected' : '' ?>>UNBLOCK</option>
        <option value="BLOCK"   <?= (($row['block_or_unblock'] ?? '') === 'BLOCK') ? 'selected' : '' ?>>BLOCK</option>
      </select>
    </div>

    <div class="col-md-3">
      <label class="form-label">Digital Arrest (count)</label>
      <input type="number" class="form-control" name="digital_arrest" value="<?= htmlspecialchars($row['digital_arrest'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Digital Amount</label>
      <input type="number" step="0.01" class="form-control" name="digital_amount" value="<?= htmlspecialchars($row['digital_amount'] ?? '') ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Mobile Number</label>
      <input class="form-control" name="mobile_number" value="<?= htmlspecialchars($row['mobile_number'] ?? '') ?>">
    </div>

    <div class="col-12">
      <button class="btn btn-primary" type="submit">Update</button>
      <a class="btn btn-secondary" href="<?= BASE_PATH ?>/cyber/list.php?thana=<?= urlencode($thana) ?><?= isset($acting_user) && $acting_user ? '&as_user=' . $acting_user : '' ?>">Cancel</a>
    </div>
  </form>
</div>
</body>
</html>
