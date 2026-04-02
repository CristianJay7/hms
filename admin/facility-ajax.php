<?php
include('./includes/session.php');
include('./includes/config.php');
global $con;

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'add' || $action === 'edit') {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (!$name) {
        echo json_encode(['success' => false, 'message' => 'Facility name is required.']);
        exit;
    }

    $image = '/hms/admin/images/default.jpg';
    if ($action === 'edit') {
        $existing = trim($_POST['existing_image'] ?? '');
        $image    = $existing ?: '/hms/admin/images/default.jpg';
    }

    if (!empty($_FILES['image']['name'])) {
        $upload_dir = __DIR__ . '/images/facilities/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed)) {
            echo json_encode(['success' => false, 'message' => 'Only JPG, PNG, or WEBP allowed.']);
            exit;
        }

        $filename = 'facility_' . time() . '_' . rand(100, 999) . '.' . $ext;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
            $image = '/hms/admin/images/facilities/' . $filename;
        } else {
            echo json_encode(['success' => false, 'message' => 'Upload failed. Check folder permissions.']);
            exit;
        }
    }

    $name        = mysqli_real_escape_string($con, $name);
    $description = mysqli_real_escape_string($con, $description);
    $image       = mysqli_real_escape_string($con, $image);

    if ($action === 'add') {
        $sql = "INSERT INTO facilities (name, description, image) VALUES ('$name','$description','$image')";
        mysqli_query($con, $sql);
        echo json_encode(['success' => true, 'message' => 'Facility added successfully.', 'id' => mysqli_insert_id($con)]);
    } else {
        $id  = intval($_POST['id'] ?? 0);
        $sql = "UPDATE facilities SET name='$name', description='$description', image='$image' WHERE id=$id";
        if (mysqli_query($con, $sql)) {
            echo json_encode(['success' => true, 'message' => 'Facility updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => mysqli_error($con)]);
        }
    }
    exit;
}

if ($action === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    if (mysqli_query($con, "DELETE FROM facilities WHERE id=$id")) {
        echo json_encode(['success' => true, 'message' => 'Facility deleted.']);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($con)]);
    }
    exit;
}

if ($action === 'fetch') {
    $facilities = [];
    $result     = mysqli_query($con, "SELECT * FROM facilities ORDER BY created_at DESC");
    while ($row = mysqli_fetch_assoc($result)) $facilities[] = $row;
    echo json_encode(['success' => true, 'facilities' => $facilities]);
    exit;
}

if ($action === 'get_one') {
    $id = intval($_GET['id'] ?? 0);
    $r  = mysqli_query($con, "SELECT * FROM facilities WHERE id=$id");
    $d  = mysqli_fetch_assoc($r);
    echo json_encode(['success' => true, 'facility' => $d]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unknown action.']);