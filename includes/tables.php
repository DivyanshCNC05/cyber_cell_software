<?php
require_once __DIR__ . '/db.php';

function get_cyber_tables() {
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME LIKE '%_cyber'");
    $stmt->execute([DB_NAME]);
    return array_column($stmt->fetchAll(), 'TABLE_NAME');
}

function get_ceir_tables() {
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME LIKE 'ceir_%'");
    $stmt->execute([DB_NAME]);
    return array_column($stmt->fetchAll(), 'TABLE_NAME');
}

function pretty_thana($tableName) {
    $t = str_replace(['_cyber','ceir_'], ['', ''], $tableName);
    $t = str_replace('_', ' ', $t);
    return ucwords($t);
}

function report_cyber_summary($from = null, $to = null) {
    $tables = get_cyber_tables();
    $pdo = getPDO();
    $total_count = 0;
    $total_amount = 0.0;
    foreach ($tables as $tbl) {
        $sql = "SELECT COUNT(*) AS cnt, COALESCE(SUM(total_fraud),0) AS amt FROM {$tbl} WHERE 1=1";
        $params = [];
        if ($from) { $sql .= ' AND complaint_date >= ?'; $params[] = $from; }
        if ($to) { $sql .= ' AND complaint_date <= ?'; $params[] = $to; }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        $total_count += (int)($row['cnt'] ?? 0);
        $total_amount += (float)($row['amt'] ?? 0);
    }
    return ['total_complaints' => $total_count, 'total_amount' => $total_amount];
}

function report_ceir_summary($from = null, $to = null) {
    $tables = get_ceir_tables();
    $pdo = getPDO();
    $total_forms = 0;
    $total_block_actions = 0;
    foreach ($tables as $tbl) {
        $sql = "SELECT COUNT(*) AS cnt, SUM(IF(block_unblock = 'BLOCK',1,0)) AS blocks FROM {$tbl} WHERE 1=1";
        $params = [];
        if ($from) { $sql .= ' AND date_of_complaint >= ?'; $params[] = $from; }
        if ($to) { $sql .= ' AND date_of_complaint <= ?'; $params[] = $to; }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        $total_forms += (int)($row['cnt'] ?? 0);
        $total_block_actions += (int)($row['blocks'] ?? 0);
    }
    return ['total_forms' => $total_forms, 'total_block_actions' => $total_block_actions];
}
