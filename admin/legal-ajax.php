<?php
require_once 'includes/config.php';
header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? '';
$type   = $_REQUEST['type']   ?? ''; // privacy | terms | rights

// Map type to table
$tables = [
    'privacy' => 'privacy_sections',
    'terms'   => 'terms_sections',
    'rights'  => 'patient_rights',
];

if (!isset($tables[$type]) && !in_array($action, [''])) {
    // allow list/get_one/create/update/delete only with valid type
}

$table = $tables[$type] ?? '';

switch ($action) {

    case 'list':
        if (!$table) { echo json_encode([]); break; }
        $res  = mysqli_query($con, "SELECT * FROM `$table` ORDER BY sort_order ASC, created_at ASC");
        $rows = [];
        while ($row = mysqli_fetch_assoc($res)) $rows[] = $row;
        echo json_encode($rows);
        break;

    case 'get_one':
        if (!$table) { echo json_encode(['success'=>false,'message'=>'Invalid type.']); break; }
        $id  = intval($_GET['id'] ?? 0);
        $res = mysqli_query($con, "SELECT * FROM `$table` WHERE id=$id");
        $row = mysqli_fetch_assoc($res);
        echo $row
            ? json_encode(['success'=>true,  'item'    => $row])
            : json_encode(['success'=>false, 'message' => 'Not found.']);
        break;

    case 'create':
        if (!$table) { echo json_encode(['success'=>false,'message'=>'Invalid type.']); break; }
        $title   = mysqli_real_escape_string($con, trim($_POST['title']   ?? ''));
        $content = mysqli_real_escape_string($con, trim($_POST['content'] ?? ''));
        $order   = intval($_POST['sort_order'] ?? 0);
        $icon    = $type !== 'rights' ? mysqli_real_escape_string($con, trim($_POST['icon'] ?? 'fa-solid fa-circle-info')) : '';

        if (!$title || !$content) {
            echo json_encode(['success'=>false,'message'=>'Title and content are required.']);
            break;
        }

        if ($type === 'rights') {
            $sql = "INSERT INTO `$table` (title, description, sort_order) VALUES ('$title','$content',$order)";
        } else {
            $sql = "INSERT INTO `$table` (icon, title, content, sort_order) VALUES ('$icon','$title','$content',$order)";
        }

        echo mysqli_query($con, $sql)
            ? json_encode(['success'=>true,  'message'=>'Added successfully!'])
            : json_encode(['success'=>false, 'message'=>'DB error: '.mysqli_error($con)]);
        break;

    case 'update':
        if (!$table) { echo json_encode(['success'=>false,'message'=>'Invalid type.']); break; }
        $id      = intval($_POST['id'] ?? 0);
        $title   = mysqli_real_escape_string($con, trim($_POST['title']   ?? ''));
        $content = mysqli_real_escape_string($con, trim($_POST['content'] ?? ''));
        $order   = intval($_POST['sort_order'] ?? 0);
        $icon    = $type !== 'rights' ? mysqli_real_escape_string($con, trim($_POST['icon'] ?? 'fa-solid fa-circle-info')) : '';

        if (!$id || !$title || !$content) {
            echo json_encode(['success'=>false,'message'=>'Invalid data.']);
            break;
        }

        if ($type === 'rights') {
            $sql = "UPDATE `$table` SET title='$title', description='$content', sort_order=$order WHERE id=$id";
        } else {
            $sql = "UPDATE `$table` SET icon='$icon', title='$title', content='$content', sort_order=$order WHERE id=$id";
        }

        echo mysqli_query($con, $sql)
            ? json_encode(['success'=>true,  'message'=>'Updated successfully!'])
            : json_encode(['success'=>false, 'message'=>'DB error: '.mysqli_error($con)]);
        break;

    case 'delete':
        if (!$table) { echo json_encode(['success'=>false,'message'=>'Invalid type.']); break; }
        $id = intval($_POST['id'] ?? 0);
        if (!$id) { echo json_encode(['success'=>false,'message'=>'Invalid ID.']); break; }
        echo mysqli_query($con, "DELETE FROM `$table` WHERE id=$id")
            ? json_encode(['success'=>true,  'message'=>'Deleted successfully!'])
            : json_encode(['success'=>false, 'message'=>'DB error.']);
        break;

    default:
        echo json_encode(['success'=>false,'message'=>'Invalid action.']);
}