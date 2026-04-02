<?php
include('./includes/session.php');
include('./includes/config.php');
global $con;

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'add' || $action === 'edit') {
    $name = trim($_POST['name'] ?? '');

    if (!$name) {
        echo json_encode(['success' => false, 'message' => 'Name is required.']);
        exit;
    }

    $logo = 'admin/images/default.jpg';
    if ($action === 'edit') {
        $existing = trim($_POST['existing_logo'] ?? '');
        $logo     = $existing ?: 'admin/images/default.jpg';
    }

    if (!empty($_FILES['logo']['name'])) {
        $upload_dir = __DIR__ . '/images/hmos/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $ext     = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

        if (!in_array($ext, $allowed)) {
            echo json_encode(['success' => false, 'message' => 'Only JPG, PNG, WEBP, or SVG allowed.']);
            exit;
        }

        $filename = 'hmo_' . time() . '_' . rand(100, 999) . '.' . $ext;
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $upload_dir . $filename)) {
            $logo = 'admin/images/hmos/' . $filename;
        } else {
            echo json_encode(['success' => false, 'message' => 'Upload failed. Check folder permissions.']);
            exit;
        }
    }

    $name = mysqli_real_escape_string($con, $name);
    $logo = mysqli_real_escape_string($con, $logo);

    if ($action === 'add') {
        $sql = "INSERT INTO hmos (name, logo) VALUES ('$name', '$logo')";
        mysqli_query($con, $sql);
        echo json_encode(['success' => true, 'message' => 'HMO added successfully.', 'id' => mysqli_insert_id($con)]);
    } else {
        $id  = intval($_POST['id'] ?? 0);
        $sql = "UPDATE hmos SET name='$name', logo='$logo' WHERE id=$id";
        if (mysqli_query($con, $sql)) {
            echo json_encode(['success' => true, 'message' => 'HMO updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => mysqli_error($con)]);
        }
    }
    exit;
}

if ($action === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    if (mysqli_query($con, "DELETE FROM hmos WHERE id=$id")) {
        echo json_encode(['success' => true, 'message' => 'HMO deleted.']);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($con)]);
    }
    exit;
}

if ($action === 'fetch') {
    $hmos   = [];
    $result = mysqli_query($con, "SELECT * FROM hmos ORDER BY created_at DESC");
    while ($row = mysqli_fetch_assoc($result)) $hmos[] = $row;
    echo json_encode(['success' => true, 'hmos' => $hmos]);
    exit;
}

if ($action === 'get_one') {
    $id = intval($_GET['id'] ?? 0);
    $r  = mysqli_query($con, "SELECT * FROM hmos WHERE id=$id");
    $d  = mysqli_fetch_assoc($r);
    echo json_encode(['success' => true, 'hmo' => $d]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unknown action.']);