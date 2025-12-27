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

// Detailed reports used by UI
function report_cyber_by_thana($from = null, $to = null) {
    $tables = get_cyber_tables();
    $pdo = getPDO();
    $out = [];
    foreach ($tables as $tbl) {
        $sql = "SELECT 
            COUNT(*) AS complaints,
            SUM(IF(acknowledgement_number IS NOT NULL AND acknowledgement_number != '',1,0)) AS acknowledgements,
            COALESCE(SUM(total_fraud),0) AS total_fraud,
            COALESCE(SUM(hold_amount),0) AS total_hold,
            COALESCE(SUM(refund_amount),0) AS total_refund,
            COALESCE(SUM(digital_arrest),0) AS total_digital_arrest,
            COALESCE(SUM(digital_amount),0) AS total_digital_amount,
            SUM(IF(mobile_number IS NOT NULL AND mobile_number != '',1,0)) AS total_mobile_numbers
            FROM {$tbl} WHERE 1=1";
        $params = [];
        if ($from) { $sql .= ' AND complaint_date >= ?'; $params[] = $from; }
        if ($to) { $sql .= ' AND complaint_date <= ?'; $params[] = $to; }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        $out[$tbl] = array_merge(['thana' => $tbl], $row ?: []);
    }
    return $out;
}

function report_admin_by_thana($from = null, $to = null) {
    // Similar to cyber but only the columns admin cares about
    $tables = get_cyber_tables();
    $pdo = getPDO();
    $out = [];
    foreach ($tables as $tbl) {
        $sql = "SELECT 
            COUNT(*) AS complaint_count,
            COALESCE(SUM(total_fraud),0) AS total_fraud,
            COALESCE(SUM(hold_amount),0) AS total_hold
            FROM {$tbl} WHERE 1=1";
        $params = [];
        if ($from) { $sql .= ' AND complaint_date >= ?'; $params[] = $from; }
        if ($to) { $sql .= ' AND complaint_date <= ?'; $params[] = $to; }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        $row = $row ?: ['complaint_count' => 0, 'total_fraud' => 0, 'total_hold' => 0];
        $row['hold_percentage'] = ($row['total_fraud'] > 0) ? (100.0 * $row['total_hold'] / $row['total_fraud']) : 0.0;
        $out[$tbl] = array_merge(['thana' => $tbl], $row);
    }
    return $out;
}

function report_ceir_by_month_thana($from = null, $to = null) {
    $tables = get_ceir_tables();
    $pdo = getPDO();
    $out = [];
    foreach ($tables as $tbl) {
        $sql = "SELECT DATE_FORMAT(date_of_complaint, '%Y-%m') AS ym,
                    SUM(IF(lost_found = 'LOST',1,0)) AS lost,
                    SUM(IF(lost_found = 'FOUND',1,0)) AS found,
                    SUM(IF(block_unblock = 'BLOCK',1,0)) AS blocks
                FROM {$tbl} WHERE 1=1";
        $params = [];
        if ($from) { $sql .= ' AND date_of_complaint >= ?'; $params[] = $from; }
        if ($to) { $sql .= ' AND date_of_complaint <= ?'; $params[] = $to; }
        $sql .= ' GROUP BY ym';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        while ($row = $stmt->fetch()) {
            $key = $row['ym'] . '|' . $tbl;
            $out[$key] = ['ym' => $row['ym'], 'thana' => $tbl, 'lost' => (int)$row['lost'], 'found' => (int)$row['found'], 'blocks' => (int)$row['blocks']];
        }
    }
    // return as indexed array
    return array_values($out);
}
