<?php
require_once 'includes/config.php';
header('Content-Type: application/json');

$action     = $_REQUEST['action'] ?? '';
$upload_dir = 'images/roomrates/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

function upload_image($file, $upload_dir) {
    $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
    if (!in_array($file['type'], $allowed)) return null;
    $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
    $name = uniqid('room_') . '.' . $ext;
    $dest = $upload_dir . $name;
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return 'admin/' . $dest;
    }
    return null;
}

switch ($action) {

    case 'list':
        $result = mysqli_query($con, "SELECT * FROM roomrates ORDER BY created_at ASC");
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
        echo json_encode($rows);
        break;

    case 'get_one':
        $id  = intval($_GET['id'] ?? 0);
        $res = mysqli_query($con, "SELECT * FROM roomrates WHERE id=$id");
        $row = mysqli_fetch_assoc($res);
        if ($row) {
            echo json_encode(['success' => true, 'room' => $row]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Room not found.']);
        }
        break;

    case 'create':
        $name      = mysqli_real_escape_string($con, trim($_POST['name'] ?? ''));
        $price     = floatval($_POST['price'] ?? 0);
        $capacity  = mysqli_real_escape_string($con, trim($_POST['capacity'] ?? ''));
        $desc      = mysqli_real_escape_string($con, trim($_POST['description'] ?? ''));
        $amenities = mysqli_real_escape_string($con, trim($_POST['amenities'] ?? ''));
        $image     = '';

        if (!empty($_FILES['image']['name'])) {
            $uploaded = upload_image($_FILES['image'], $upload_dir);
            if ($uploaded) $image = $uploaded;
        }

        if (empty($name)) {
            echo json_encode(['success' => false, 'message' => 'Room name is required.']);
            break;
        }

        $sql = "INSERT INTO roomrates (name, description, price_per_night, capacity, amenities, image)
                VALUES ('$name', '$desc', $price, '$capacity', '$amenities', '$image')";

        if (mysqli_query($con, $sql)) {
            echo json_encode(['success' => true, 'message' => 'Room added successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($con)]);
        }
        break;

    case 'update':
        $id        = intval($_POST['id'] ?? 0);
        $name      = mysqli_real_escape_string($con, trim($_POST['name'] ?? ''));
        $price     = floatval($_POST['price'] ?? 0);
        $capacity  = mysqli_real_escape_string($con, trim($_POST['capacity'] ?? ''));
        $desc      = mysqli_real_escape_string($con, trim($_POST['description'] ?? ''));
        $amenities = mysqli_real_escape_string($con, trim($_POST['amenities'] ?? ''));

        $image_sql = '';
        if (!empty($_FILES['image']['name'])) {
            $uploaded = upload_image($_FILES['image'], $upload_dir);
            if ($uploaded) $image_sql = ", image='$uploaded'";
        }

        if (empty($name) || !$id) {
            echo json_encode(['success' => false, 'message' => 'Invalid data.']);
            break;
        }

        $sql = "UPDATE roomrates SET
                    name='$name',
                    description='$desc',
                    price_per_night=$price,
                    capacity='$capacity',
                    amenities='$amenities'
                    $image_sql
                WHERE id=$id";

        if (mysqli_query($con, $sql)) {
            echo json_encode(['success' => true, 'message' => 'Room updated successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($con)]);
        }
        break;

    case 'delete':
        $id = intval($_POST['id'] ?? 0);
        if (!$id) { echo json_encode(['success' => false, 'message' => 'Invalid ID.']); break; }

        $res = mysqli_query($con, "SELECT image FROM roomrates WHERE id=$id");
        $row = mysqli_fetch_assoc($res);
        if (!empty($row['image']) && file_exists('../' . $row['image'])) {
            unlink('../' . $row['image']);
        }

        if (mysqli_query($con, "DELETE FROM roomrates WHERE id=$id")) {
            echo json_encode(['success' => true, 'message' => 'Room deleted successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error.']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}