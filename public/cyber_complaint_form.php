<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/tables.php';
require_role(['ADMIN','CYBER_USER']);
$user = current_user();
$tables = get_cyber_tables();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cyber Complaint Form</title>
</head>
<body>
<h3>Submit Cyber Complaint</h3>
<form method="post" action="/cyber_complaint_save.php" enctype="multipart/form-data">
  <label>Thana</label><br>
  <select name="thana_table" required>
    <?php foreach ($tables as $t): ?>
      <option value="<?= sanitize($t) ?>"><?= sanitize(pretty_thana($t)) ?></option>
    <?php endforeach; ?>
  </select><br>

  <label>Applicant name</label><br>
  <input name="applicant_name" required><br>

  <label>Nature of fraud</label><br>
  <input name="nature_of_fraud"><br>

  <label>Incident date</label><br>
  <input type="date" name="incident_date"><br>

  <button>Submit</button>
</form>
</body>
</html>