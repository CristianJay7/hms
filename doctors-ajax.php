<?php
require_once 'includes/config.php';

// ── Input ────────────────────────────────────────────────────
$perPage = 10;
$page    = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$search  = isset($_GET['search']) ? trim($_GET['search']) : '';
$spec    = isset($_GET['spec'])   ? trim($_GET['spec'])   : '';
$sort    = isset($_GET['sort'])   ? $_GET['sort']         : 'name';
$dir     = isset($_GET['dir'])    && strtolower($_GET['dir']) === 'desc' ? 'DESC' : 'ASC';
$offset  = ($page - 1) * $perPage;

$allowedSort = ['name', 'specialization', 'clinic_hours', 'availability'];
if (!in_array($sort, $allowedSort)) $sort = 'name';

// ── WHERE ─────────────────────────────────────────────────────
$where  = [];
$params = [];
$types  = '';

if ($search !== '') {
    $where[]  = "name LIKE ?";
    $params[] = '%' . $search . '%';
    $types   .= 's';
}
if ($spec !== '') {
    $where[]  = "specialization = ?";
    $params[] = $spec;
    $types   .= 's';
}

$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// ── Count ─────────────────────────────────────────────────────
$cStmt = mysqli_prepare($con, "SELECT COUNT(*) AS total FROM doctors $whereSQL");
if ($types) mysqli_stmt_bind_param($cStmt, $types, ...$params);
mysqli_stmt_execute($cStmt);
$total      = mysqli_fetch_assoc(mysqli_stmt_get_result($cStmt))['total'];
$totalPages = max(1, ceil($total / $perPage));
$page       = min($page, $totalPages);
$offset     = ($page - 1) * $perPage;

// ── Fetch ─────────────────────────────────────────────────────
$sql   = "SELECT name, specialization, clinic_hours, availability
          FROM doctors $whereSQL
          ORDER BY $sort $dir
          LIMIT ? OFFSET ?";
$stmt  = mysqli_prepare($con, $sql);
$bt    = $types . 'ii';
$bp    = array_merge($params, [$perPage, $offset]);
mysqli_stmt_bind_param($stmt, $bt, ...$bp);
mysqli_stmt_execute($stmt);
$doctors = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);

// ── Response ──────────────────────────────────────────────────
header('Content-Type: application/json');
echo json_encode([
    'total'      => (int)$total,
    'totalPages' => (int)$totalPages,
    'page'       => (int)$page,
    'perPage'    => $perPage,
    'sort'       => $sort,
    'dir'        => strtolower($dir),
    'doctors'    => $doctors,
]);