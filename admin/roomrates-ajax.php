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

        $existing_image = trim($_POST['existing_image'] ?? '');
        $remove_image   = ($_POST['remove_image'] ?? '0') === '1';
        $image_sql = '';

        if (!empty($_FILES['image']['name'])) {
            // New image uploaded — delete old file first, then save new one
            $uploaded = upload_image($_FILES['image'], $upload_dir);
            if ($uploaded) {
                if (!empty($existing_image)) {
                    $old_abs = $_SERVER['DOCUMENT_ROOT'] . $existing_image;
                    if (file_exists($old_abs)) unlink($old_abs);
                }
                $esc_img   = mysqli_real_escape_string($con, $uploaded);
                $image_sql = ", image='$esc_img'";
            }
        } elseif ($remove_image) {
            // "Remove image" button was explicitly clicked — clear DB and delete file
            $res_old = mysqli_query($con, "SELECT image FROM roomrates WHERE id=$id");
            $row_old = mysqli_fetch_assoc($res_old);
            if (!empty($row_old['image'])) {
                $old_abs = $_SERVER['DOCUMENT_ROOT'] . $row_old['image'];
                if (file_exists($old_abs)) unlink($old_abs);
            }
            $image_sql = ", image=''";
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
    
        case 'get_photos':
            $room_id = intval($_GET['id'] ?? 0);
            $res     = mysqli_query($con, "SELECT * FROM room_photos WHERE room_id=$room_id ORDER BY created_at ASC");
            $photos  = [];
            while ($row = mysqli_fetch_assoc($res)) $photos[] = $row;
            echo json_encode(['success' => true, 'photos' => $photos]);
            break;
     
        case 'add_photos':
            $room_id = intval($_POST['room_id'] ?? 0);
            if (!$room_id) { echo json_encode(['success' => false, 'message' => 'Invalid room ID.']); break; }
     
            $allowed  = ['image/jpeg','image/png','image/webp','image/jpg'];
            $photo_dir = 'images/roomrates/';
            if (!is_dir($photo_dir)) mkdir($photo_dir, 0755, true);
            $uploaded = 0;
            $failed   = 0;
     
            if (!empty($_FILES['photos']['name'][0])) {
                $count = count($_FILES['photos']['name']);
                for ($i = 0; $i < $count; $i++) {
                    $file = [
                        'name'     => $_FILES['photos']['name'][$i],
                        'type'     => $_FILES['photos']['type'][$i],
                        'tmp_name' => $_FILES['photos']['tmp_name'][$i],
                        'error'    => $_FILES['photos']['error'][$i],
                        'size'     => $_FILES['photos']['size'][$i],
                    ];
                    if ($file['error'] !== 0) { $failed++; continue; }
                    if (!in_array(strtolower($file['type']), $allowed)) { $failed++; continue; }
     
                    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $name = uniqid('room_gallery_') . '.' . $ext;
                    $dest = $photo_dir . $name;
     
                    if (move_uploaded_file($file['tmp_name'], $dest)) {
                        $path = mysqli_real_escape_string($con, '/hms/admin/' . $dest);
                        mysqli_query($con, "INSERT INTO room_photos (room_id, photo) VALUES ($room_id, '$path')");
                        $uploaded++;
                    } else {
                        $failed++;
                    }
                }
            }
     
            echo $uploaded > 0
                ? json_encode(['success' => true,  'message' => "$uploaded photo(s) uploaded successfully!"])
                : json_encode(['success' => false, 'message' => 'No photos were uploaded.']);
            break;
     
        case 'delete_photo':
            $photo_id = intval($_POST['photo_id'] ?? 0);
            if (!$photo_id) { echo json_encode(['success' => false, 'message' => 'Invalid photo ID.']); break; }
     
            $res = mysqli_query($con, "SELECT photo FROM room_photos WHERE id=$photo_id");
            $row = mysqli_fetch_assoc($res);
            if (!empty($row['photo'])) {
                $abs = $_SERVER['DOCUMENT_ROOT'] . $row['photo'];
                if (file_exists($abs)) unlink($abs);
            }
     
            echo mysqli_query($con, "DELETE FROM room_photos WHERE id=$photo_id")
                ? json_encode(['success' => true,  'message' => 'Photo deleted.'])
                : json_encode(['success' => false, 'message' => 'DB error.']);
            break;
     
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}