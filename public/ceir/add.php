<?php
require __DIR__ . '/../../includes/db.php';
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/ceir_thanas.php';

// Allow ADMIN to access CEIR pages
if (($_SESSION['role'] ?? '') === 'ADMIN') {
  // admin allowed
} else {
  require_role('CEIR_USER');
}

$error = '';
$success = '';

function p($k, $d='') { return trim($_POST[$k] ?? $d); }

$selected = $_POST['thana'] ?? '';

function validate_pdf_upload(array $file): array {
  if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
    return [false, 'File upload failed.'];
  }

  $maxBytes = 5 * 1024 * 1024; // 5 MB
  if (($file['size'] ?? 0) > $maxBytes) {
    return [false, 'PDF must be <= 5MB'];
  }

  $finfo = new finfo(FILEINFO_MIME_TYPE);
  $mime = $finfo->file($file['tmp_name']);
  if ($mime !== 'application/pdf') {
    return [false, 'Only PDF allowed'];
  }

  return [true, 'OK'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if (!isset($CEIR_TABLES[$selected])) {
    $error = 'Invalid thana selected';
  } else {

    $table = $CEIR_TABLES[$selected];

    $name = p('name');
    $father_name = p('father_name');
    $address = p('address');
    $date_of_complaint = p('date_of_complaint');
    $mobile_number = p('mobile_number');
    $imei = p('imei');
    $lost_found = p('lost_found');
    $block_unblock = p('block_unblock');

    if ($name === '' || $date_of_complaint === '' || $imei === '' || $lost_found === '' || $block_unblock === '') {
      $error = 'Please fill all required fields';
    } else {

      $pdfPathDb = null;

      if (!empty($_FILES['pdf']['name'])) {
        [$ok, $msg] = validate_pdf_upload($_FILES['pdf']);
        if (!$ok) {
          $error = $msg;
        } else {
          $uploadDirFs = __DIR__ . '/../uploads/ceir_pdfs/';
          $uploadDirDb = '/uploads/ceir_pdfs/';

          if (!is_dir($uploadDirFs)) {
            mkdir($uploadDirFs, 0775, true);
          }

          $safeBase = preg_replace('/[^a-zA-Z0-9_-]/', '_', $imei);
          $newName = 'ceir_' . $safeBase . '_' . time() . '.pdf';

          $destFs = $uploadDirFs . $newName;

          if (!move_uploaded_file($_FILES['pdf']['tmp_name'], $destFs)) {
            $error = 'Could not save PDF on server';
          } else {
            $pdfPathDb = $uploadDirDb . $newName;
          }
        }
      }

      if ($error === '') {
        $sql = "INSERT INTO {$table}
          (name, father_name, address, date_of_complaint, mobile_number, imei,
           lost_found, block_unblock, pdf_attach, created_by)
          VALUES
          (:name, :father_name, :address, :date_of_complaint, :mobile_number, :imei,
           :lost_found, :block_unblock, :pdf_attach, :created_by)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
          ':name' => $name,
          ':father_name' => ($father_name !== '' ? $father_name : null),
          ':address' => ($address !== '' ? $address : null),
          ':date_of_complaint' => $date_of_complaint,
          ':mobile_number' => ($mobile_number !== '' ? $mobile_number : null),
          ':imei' => $imei,
          ':lost_found' => $lost_found,
          ':block_unblock' => $block_unblock,
          ':pdf_attach' => $pdfPathDb,
          ':created_by' => (int)($_SESSION['user_id'] ?? 0),
        ]);

        $success = "CEIR saved successfully. ID: " . $pdo->lastInsertId();
        $_POST = [];
        $selected = '';
      }
    }
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Add CEIR Complaint</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">

  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1">Add CEIR Complaint</h3>
      <small class="text-muted">Enter mobile device loss / block details</small>
    </div>
    <a class="btn btn-outline-secondary btn-sm"
       href="<?= BASE_PATH ?>/dashboards/ceir.php">← Back</a>
  </div>

  <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

  <!-- Form Card -->
  <div class="card shadow-sm border-0">
    <div class="card-body p-4">

      <form method="post" enctype="multipart/form-data" class="row g-4">

        <!-- CEIR Details -->
        <div class="col-12">
          <h6 class="text-primary fw-semibold border-bottom pb-2">CEIR Details</h6>
        </div>

        <div class="col-md-4">
          <label class="form-label">Thana *</label>
          <select name="thana" class="form-select" required>
            <option value="">Select</option>
            <?php foreach ($CEIR_TABLES as $key => $tbl): ?>
              <option value="<?= htmlspecialchars($key) ?>" <?= ($selected === $key) ? 'selected' : '' ?>>
                <?= htmlspecialchars(strtoupper(str_replace('_',' ', $key))) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Name *</label>
          <input class="form-control" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>

        <div class="col-md-4">
          <label class="form-label">Father Name</label>
          <input class="form-control" name="father_name" value="<?= htmlspecialchars($_POST['father_name'] ?? '') ?>">
        </div>

        <div class="col-12">
          <label class="form-label">Address</label>
          <textarea class="form-control" name="address" rows="2"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
        </div>

        <!-- Complaint & Device -->
        <div class="col-12 mt-3">
          <h6 class="text-primary fw-semibold border-bottom pb-2">Complaint & Device Details</h6>
        </div>

        <div class="col-md-4">
          <label class="form-label">Date of Complaint *</label>
          <input type="date" class="form-control" name="date_of_complaint" required
                 value="<?= htmlspecialchars($_POST['date_of_complaint'] ?? '') ?>">
        </div>

        <div class="col-md-4">
          <label class="form-label">Mobile Number</label>
          <input class="form-control" name="mobile_number"
                 value="<?= htmlspecialchars($_POST['mobile_number'] ?? '') ?>">
        </div>

        <div class="col-md-4">
          <label class="form-label">IMEI *</label>
          <input class="form-control" name="imei" required
                 value="<?= htmlspecialchars($_POST['imei'] ?? '') ?>">
        </div>

        <!-- Status -->
        <div class="col-12 mt-3">
          <h6 class="text-primary fw-semibold border-bottom pb-2">Status</h6>
        </div>

        <div class="col-md-4">
          <label class="form-label">Lost / Found *</label>
          <select name="lost_found" class="form-select" required>
            <option value="">Select</option>
            <option value="LOST" <?= (($_POST['lost_found'] ?? '') === 'LOST') ? 'selected' : '' ?>>LOST</option>
            <option value="FOUND" <?= (($_POST['lost_found'] ?? '') === 'FOUND') ? 'selected' : '' ?>>FOUND</option>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Block / Unblock *</label>
          <select name="block_unblock" class="form-select" required>
            <option value="">Select</option>
            <option value="BLOCK" <?= (($_POST['block_unblock'] ?? '') === 'BLOCK') ? 'selected' : '' ?>>BLOCK</option>
            <option value="UNBLOCK" <?= (($_POST['block_unblock'] ?? '') === 'UNBLOCK') ? 'selected' : '' ?>>UNBLOCK</option>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Attach PDF (optional)</label>
          <input type="file" class="form-control" name="pdf" accept="application/pdf">
          <div class="form-text">Only PDF files, max 5MB</div>
        </div>

        <!-- Actions -->
        <div class="col-12 mt-3 d-flex gap-2">
          <button class="btn btn-primary px-4" type="submit">💾 Save CEIR</button>
          <a class="btn btn-outline-secondary px-4"
             href="<?= BASE_PATH ?>/dashboards/ceir.php">Cancel</a>
        </div>

      </form>

    </div>
  </div>

</div>
</body>
</html>
