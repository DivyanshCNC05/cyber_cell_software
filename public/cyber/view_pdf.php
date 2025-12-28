<?php
require __DIR__ . '/../../includes/db.php';
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/ceir_thanas.php';

// allow CEIR_USER (and ADMIN if you want)
require_login();
if (!in_array(($_SESSION['role'] ?? ''), ['CEIR_USER','ADMIN'], true)) {
  header('Location: /index.php'); exit;
}

$thana = $_GET['thana'] ?? '';
$ceir_id = isset($_GET['ceir_id']) ? (int)$_GET['ceir_id'] : 0;

if (!isset($CEIR_TABLES[$thana]) || $ceir_id <= 0) {
  http_response_code(400);
  exit('Invalid request');
}

$table = $CEIR_TABLES[$thana];

// Get pdf path from DB
$stmt = $pdo->prepare("SELECT pdf_attach FROM {$table} WHERE ceir_id = :id LIMIT 1");
$stmt->execute([':id' => $ceir_id]);
$row = $stmt->fetch();

$pdf = $row['pdf_attach'] ?? '';
if (!$pdf) {
  http_response_code(404);
  exit('No PDF attached');
}

// pdf_attach stored like: /uploads/ceir_pdfs/filename.pdf
$baseDir = realpath(__DIR__ . '/../uploads/ceir_pdfs');
$filePath = realpath(__DIR__ . '/..' . $pdf); // because /uploads/... is under public/

// Security: ensure file is inside uploads folder
if (!$baseDir || !$filePath || strpos($filePath, $baseDir) !== 0 || !is_file($filePath)) {
  http_response_code(404);
  exit('File not found');
}

// Stream PDF inline [web:393][web:402]
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="ceir_' . $ceir_id . '.pdf"');
header('Content-Length: ' . filesize($filePath));
readfile($filePath);
exit;
