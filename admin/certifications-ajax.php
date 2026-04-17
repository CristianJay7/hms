<?php
require_once 'includes/session.php';
require_once 'includes/config.php';
global $con;

header('Content-Type: application/json');

$action     = $_REQUEST['action'] ?? '';
$upload_dir = 'files/certifications/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

function upload_cert_file($file, $upload_dir) {
    $allowed_types = [
        'application/pdf',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];
    $allowed_exts = ['pdf', 'docx'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array(strtolower($file['type']), $allowed_types) && !in_array($ext, $allowed_exts)) return null;
    if (!in_array($ext, $allowed_exts)) return null;

    $name = uniqid('cert_') . '.' . $ext;
    $dest = $upload_dir . $name;
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return [
            'path'      => '/hms/admin/' . $dest,
            'file_type' => $ext,
        ];
    }
    return null;
}

switch ($action) {

    case 'list':
        $res  = mysqli_query($con, "SELECT * FROM certifications ORDER BY sort_order ASC, created_at DESC");
        $rows = [];
        while ($row = mysqli_fetch_assoc($res)) $rows[] = $row;
        echo json_encode($rows);
        break;

    case 'add':
        $title  = mysqli_real_escape_string($con, trim($_POST['title'] ?? ''));
        $status = in_array($_POST['status'] ?? '', ['active','inactive']) ? $_POST['status'] : 'active';
        $sort   = intval($_POST['sort_order'] ?? 0);

        if (!$title) { echo json_encode(['success' => false, 'message' => 'Title is required.']); break; }
        if (empty($_FILES['file']['name'])) {
            echo json_encode(['success' => false, 'message' => 'A PDF or DOCX file is required.']); break;
        }

        $uploaded = upload_cert_file($_FILES['file'], $upload_dir);
        if (!$uploaded) {
            echo json_encode(['success' => false, 'message' => 'Invalid file. PDF or DOCX only.']); break;
        }

        $path      = mysqli_real_escape_string($con, $uploaded['path']);
        $file_type = $uploaded['file_type'];

        $sql = "INSERT INTO certifications (title, file, file_type, sort_order, status)
                VALUES ('$title', '$path', '$file_type', $sort, '$status')";
        echo mysqli_query($con, $sql)
            ? json_encode(['success' => true,  'message' => 'Certification added successfully!'])
            : json_encode(['success' => false, 'message' => 'DB error: ' . mysqli_error($con)]);
        break;

    case 'get_one':
        $id  = intval($_GET['id'] ?? 0);
        $res = mysqli_query($con, "SELECT * FROM certifications WHERE id=$id");
        $row = mysqli_fetch_assoc($res);
        echo $row
            ? json_encode(['success' => true,  'cert' => $row])
            : json_encode(['success' => false, 'message' => 'Not found.']);
        break;

    case 'edit':
        $id     = intval($_POST['id'] ?? 0);
        $title  = mysqli_real_escape_string($con, trim($_POST['title'] ?? ''));
        $status = in_array($_POST['status'] ?? '', ['active','inactive']) ? $_POST['status'] : 'active';
        $sort   = intval($_POST['sort_order'] ?? 0);

        if (!$id || !$title) { echo json_encode(['success' => false, 'message' => 'Invalid data.']); break; }

        $file_sql       = '';
        $existing_file  = trim($_POST['existing_file'] ?? '');

        if (!empty($_FILES['file']['name'])) {
            $uploaded = upload_cert_file($_FILES['file'], $upload_dir);
            if ($uploaded) {
                // Delete old file
                if (!empty($existing_file)) {
                    $abs = $_SERVER['DOCUMENT_ROOT'] . $existing_file;
                    if (file_exists($abs)) unlink($abs);
                }
                $path      = mysqli_real_escape_string($con, $uploaded['path']);
                $file_type = $uploaded['file_type'];
                $file_sql  = ", file='$path', file_type='$file_type'";
            }
        }

        $sql = "UPDATE certifications SET title='$title', sort_order=$sort, status='$status' $file_sql WHERE id=$id";
        echo mysqli_query($con, $sql)
            ? json_encode(['success' => true,  'message' => 'Certification updated successfully!'])
            : json_encode(['success' => false, 'message' => 'DB error: ' . mysqli_error($con)]);
        break;

    case 'delete':
        $id = intval($_POST['id'] ?? 0);
        if (!$id) { echo json_encode(['success' => false, 'message' => 'Invalid ID.']); break; }

        $res = mysqli_query($con, "SELECT file FROM certifications WHERE id=$id");
        $row = mysqli_fetch_assoc($res);
        if (!empty($row['file'])) {
            $abs = $_SERVER['DOCUMENT_ROOT'] . $row['file'];
            if (file_exists($abs)) unlink($abs);
        }

        echo mysqli_query($con, "DELETE FROM certifications WHERE id=$id")
            ? json_encode(['success' => true,  'message' => 'Certification deleted.'])
            : json_encode(['success' => false, 'message' => 'DB error.']);
        break;

    case 'toggle_status':
        $id = intval($_POST['id'] ?? 0);
        if (!$id) { echo json_encode(['success' => false, 'message' => 'Invalid ID.']); break; }
        mysqli_query($con, "UPDATE certifications SET status = IF(status='active','inactive','active') WHERE id=$id");
        $res = mysqli_query($con, "SELECT status FROM certifications WHERE id=$id");
        $row = mysqli_fetch_assoc($res);
        echo json_encode(['success' => true, 'status' => $row['status']]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}