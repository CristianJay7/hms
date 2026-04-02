<?php
include('./includes/session.php');
include('./includes/config.php');
global $con;

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'add' || $action === 'edit') {
    $title = trim($_POST['title'] ?? '');

    if (!$title) {
        echo json_encode(['success' => false, 'message' => 'Title is required.']);
        exit;
    }

    $image = 'admin/images/default.jpg';
    if ($action === 'edit') {
        $existing = trim($_POST['existing_image'] ?? '');
        $image    = $existing ?: 'admin/images/default.jpg';
    }

    if (!empty($_FILES['image']['name'])) {
        $upload_dir = __DIR__ . '/images/gallery/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed)) {
            echo json_encode(['success' => false, 'message' => 'Only JPG, PNG, or WEBP allowed.']);
            exit;
        }

        $filename = 'gallery_' . time() . '_' . rand(100, 999) . '.' . $ext;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
            $image = 'admin/images/gallery/' . $filename;
        } else {
            echo json_encode(['success' => false, 'message' => 'Upload failed. Check folder permissions.']);
            exit;
        }
    }

    $title = mysqli_real_escape_string($con, $title);
    $image = mysqli_real_escape_string($con, $image);

    if ($action === 'add') {
        $sql = "INSERT INTO gallery (title, image) VALUES ('$title','$image')";
        mysqli_query($con, $sql);
        echo json_encode(['success' => true, 'message' => 'Image added successfully.', 'id' => mysqli_insert_id($con)]);
    } else {
        $id  = intval($_POST['id'] ?? 0);
        $sql = "UPDATE gallery SET title='$title', image='$image' WHERE id=$id";
        if (mysqli_query($con, $sql)) {
            echo json_encode(['success' => true, 'message' => 'Image updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => mysqli_error($con)]);
        }
    }
    exit;
}

if ($action === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    if (mysqli_query($con, "DELETE FROM gallery WHERE id=$id")) {
        echo json_encode(['success' => true, 'message' => 'Image deleted.']);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($con)]);
    }
    exit;
}

if ($action === 'fetch') {
    $items  = [];
    $result = mysqli_query($con, "SELECT * FROM gallery ORDER BY created_at DESC");
    while ($row = mysqli_fetch_assoc($result)) $items[] = $row;
    echo json_encode(['success' => true, 'gallery' => $items]);
    exit;
}

if ($action === 'get_one') {
    $id = intval($_GET['id'] ?? 0);
    $r  = mysqli_query($con, "SELECT * FROM gallery WHERE id=$id");
    $d  = mysqli_fetch_assoc($r);
    echo json_encode(['success' => true, 'item' => $d]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unknown action.']);