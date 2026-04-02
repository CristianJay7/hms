<?php
include('./includes/session.php');
include('./includes/config.php');
global $con;

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'add' || $action === 'edit') {
    $name        = trim($_POST['name']        ?? '');
    $description = trim($_POST['description'] ?? '');
    $icon        = trim($_POST['icon']        ?? 'fa-solid fa-stethoscope'); // ← NEW

    if (!$name) {
        echo json_encode(['success' => false, 'message' => 'Service name is required.']);
        exit;
    }

    $image = '/hms/admin/images/default.jpg';
    if ($action === 'edit') {
        $existing = trim($_POST['existing_image'] ?? '');
        $image    = $existing ?: '/hms/admin/images/default.jpg';
    }

    if (!empty($_FILES['image']['name'])) {
        $upload_dir = __DIR__ . '/images/services/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed)) {
            echo json_encode(['success' => false, 'message' => 'Only JPG, PNG, or WEBP allowed.']);
            exit;
        }

        $filename = 'service_' . time() . '_' . rand(100, 999) . '.' . $ext;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
            $image = '/hms/admin/images/services/' . $filename;
        } else {
            echo json_encode(['success' => false, 'message' => 'Upload failed. Check folder permissions.']);
            exit;
        }
    }

    $name        = mysqli_real_escape_string($con, $name);
    $description = mysqli_real_escape_string($con, $description);
    $icon        = mysqli_real_escape_string($con, $icon); // ← NEW
    $image       = mysqli_real_escape_string($con, $image);

    if ($action === 'add') {
        $sql = "INSERT INTO services (name, description, image, icon) VALUES ('$name','$description','$image','$icon')"; // ← NEW
        mysqli_query($con, $sql);
        echo json_encode(['success' => true, 'message' => 'Service added successfully.', 'id' => mysqli_insert_id($con)]);
    } else {
        $id  = intval($_POST['id'] ?? 0);
        $sql = "UPDATE services SET name='$name', description='$description', image='$image', icon='$icon' WHERE id=$id"; // ← NEW
        if (mysqli_query($con, $sql)) {
            echo json_encode(['success' => true, 'message' => 'Service updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => mysqli_error($con)]);
        }
    }
    exit;
}

if ($action === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    if (mysqli_query($con, "DELETE FROM services WHERE id=$id")) {
        echo json_encode(['success' => true, 'message' => 'Service deleted.']);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($con)]);
    }
    exit;
}

if ($action === 'fetch') {
    $services = [];
    $result   = mysqli_query($con, "SELECT * FROM services ORDER BY created_at DESC");
    while ($row = mysqli_fetch_assoc($result)) $services[] = $row;
    echo json_encode(['success' => true, 'services' => $services]);
    exit;
}

if ($action === 'get_one') {
    $id = intval($_GET['id'] ?? 0);
    $r  = mysqli_query($con, "SELECT * FROM services WHERE id=$id");
    $d  = mysqli_fetch_assoc($r);
    echo json_encode(['success' => true, 'service' => $d]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unknown action.']);