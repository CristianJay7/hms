<?php
require_once 'includes/session.php';
require_once 'includes/config.php';
global $con;

header('Content-Type: application/json');

$action     = $_REQUEST['action'] ?? '';
$upload_dir = 'images/packages/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

function upload_package_image($file, $upload_dir) {
    $allowed = ['image/jpeg','image/png','image/webp','image/jpg'];
    if (!in_array(strtolower($file['type']), $allowed)) return null;
    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $name = uniqid('pkg_') . '.' . $ext;
    $dest = $upload_dir . $name;
    if (move_uploaded_file($file['tmp_name'], $dest)) return '/hms/admin/' . $dest;
    return null;
}

switch ($action) {

    case 'list':
        $res  = mysqli_query($con, "SELECT * FROM packages ORDER BY sort_order ASC, created_at DESC");
        $rows = [];
        while ($row = mysqli_fetch_assoc($res)) $rows[] = $row;
        echo json_encode($rows);
        break;

    case 'add':
        $title  = mysqli_real_escape_string($con, trim($_POST['title'] ?? ''));
        $status = in_array($_POST['status'] ?? '', ['active','inactive']) ? $_POST['status'] : 'active';
        $sort   = intval($_POST['sort_order'] ?? 0);

        if (empty($_FILES['image']['name'])) {
            echo json_encode(['success' => false, 'message' => 'An image is required.']); break;
        }

        $image = upload_package_image($_FILES['image'], $upload_dir);
        if (!$image) {
            echo json_encode(['success' => false, 'message' => 'Invalid image. JPG, PNG or WEBP only.']); break;
        }

        $sql = "INSERT INTO packages (title, image, sort_order, status)
                VALUES ('$title', '$image', $sort, '$status')";
        echo mysqli_query($con, $sql)
            ? json_encode(['success' => true, 'message' => 'Package added successfully!'])
            : json_encode(['success' => false, 'message' => 'DB error: ' . mysqli_error($con)]);
        break;

    case 'get_one':
        $id  = intval($_GET['id'] ?? 0);
        $res = mysqli_query($con, "SELECT * FROM packages WHERE id=$id");
        $row = mysqli_fetch_assoc($res);
        echo $row
            ? json_encode(['success' => true,  'package' => $row])
            : json_encode(['success' => false, 'message' => 'Not found.']);
        break;

    case 'edit':
        $id     = intval($_POST['id'] ?? 0);
        $title  = mysqli_real_escape_string($con, trim($_POST['title'] ?? ''));
        $status = in_array($_POST['status'] ?? '', ['active','inactive']) ? $_POST['status'] : 'active';
        $sort   = intval($_POST['sort_order'] ?? 0);

        if (!$id) { echo json_encode(['success' => false, 'message' => 'Invalid ID.']); break; }

        $image_sql      = '';
        $existing_image = trim($_POST['existing_image'] ?? '');
        $remove_image   = ($_POST['remove_image'] ?? '0') === '1';

        if (!empty($_FILES['image']['name'])) {
            $uploaded = upload_package_image($_FILES['image'], $upload_dir);
            if ($uploaded) {
                if (!empty($existing_image)) {
                    $abs = $_SERVER['DOCUMENT_ROOT'] . $existing_image;
                    if (file_exists($abs)) unlink($abs);
                }
                $esc   = mysqli_real_escape_string($con, $uploaded);
                $image_sql = ", image='$esc'";
            }
        } elseif ($remove_image && !empty($existing_image)) {
            $abs = $_SERVER['DOCUMENT_ROOT'] . $existing_image;
            if (file_exists($abs)) unlink($abs);
            $image_sql = ", image=''";
        }

        $sql = "UPDATE packages SET title='$title', sort_order=$sort, status='$status' $image_sql WHERE id=$id";
        echo mysqli_query($con, $sql)
            ? json_encode(['success' => true,  'message' => 'Package updated successfully!'])
            : json_encode(['success' => false, 'message' => 'DB error: ' . mysqli_error($con)]);
        break;

    case 'delete':
        $id = intval($_POST['id'] ?? 0);
        if (!$id) { echo json_encode(['success' => false, 'message' => 'Invalid ID.']); break; }

        $res = mysqli_query($con, "SELECT image FROM packages WHERE id=$id");
        $row = mysqli_fetch_assoc($res);
        if (!empty($row['image'])) {
            $abs = $_SERVER['DOCUMENT_ROOT'] . $row['image'];
            if (file_exists($abs)) unlink($abs);
        }

        echo mysqli_query($con, "DELETE FROM packages WHERE id=$id")
            ? json_encode(['success' => true,  'message' => 'Package deleted.'])
            : json_encode(['success' => false, 'message' => 'DB error.']);
        break;

    case 'toggle_status':
        $id = intval($_POST['id'] ?? 0);
        if (!$id) { echo json_encode(['success' => false, 'message' => 'Invalid ID.']); break; }
        $sql = "UPDATE packages SET status = IF(status='active','inactive','active') WHERE id=$id";
        mysqli_query($con, $sql);
        $res = mysqli_query($con, "SELECT status FROM packages WHERE id=$id");
        $row = mysqli_fetch_assoc($res);
        echo json_encode(['success' => true, 'status' => $row['status']]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}