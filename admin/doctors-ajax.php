<?php
include('./includes/session.php');
include('./includes/config.php');
global $con;

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'add' || $action === 'edit') {
    $name           = trim($_POST['name'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $clinic_hours   = trim($_POST['clinic_hours'] ?? '');
    $availability   = trim($_POST['availability'] ?? '');

    if (!$name || !$specialization || !$clinic_hours || !$availability) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
        exit;
    }

    // Default image
    $image = 'admin/images/default.jpg';
    if ($action === 'edit') {
        $existing = trim($_POST['existing_image'] ?? '');
        $image    = $existing ?: 'admin/images/default.jpg';
    }

    if (!empty($_FILES['image']['name'])) {
        $upload_dir = __DIR__ . '/images/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed)) {
            echo json_encode(['success' => false, 'message' => 'Only JPG, PNG, or WEBP allowed.']);
            exit;
        }

        $filename = 'doctor_' . time() . '_' . rand(100, 999) . '.' . $ext;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
            $image = 'admin/images/' . $filename;
        } else {
            echo json_encode(['success' => false, 'message' => 'Upload failed. Check folder permissions.']);
            exit;
        }
    }

    $name           = mysqli_real_escape_string($con, $name);
    $specialization = mysqli_real_escape_string($con, $specialization);
    $clinic_hours   = mysqli_real_escape_string($con, $clinic_hours);
    $availability   = mysqli_real_escape_string($con, $availability);
    $image          = mysqli_real_escape_string($con, $image);

    if ($action === 'add') {
        $sql = "INSERT INTO doctors (name, specialization, clinic_hours, availability, image)
                VALUES ('$name','$specialization','$clinic_hours','$availability','$image')";
        mysqli_query($con, $sql);
        echo json_encode(['success' => true, 'message' => 'Doctor added successfully.', 'id' => mysqli_insert_id($con)]);
    } else {
        $id  = intval($_POST['id'] ?? 0);
        $sql = "UPDATE doctors SET name='$name', specialization='$specialization',
                    clinic_hours='$clinic_hours', availability='$availability', image='$image'
                WHERE id=$id";
        if (mysqli_query($con, $sql)) {
            echo json_encode(['success' => true, 'message' => 'Doctor updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => mysqli_error($con)]);
        }
    }
    exit;
}

if ($action === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    if (mysqli_query($con, "DELETE FROM doctors WHERE id=$id")) {
        echo json_encode(['success' => true, 'message' => 'Doctor deleted.']);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($con)]);
    }
    exit;
}

if ($action === 'fetch') {
    $doctors = [];
    $result  = mysqli_query($con, "SELECT * FROM doctors ORDER BY created_at DESC");
    while ($row = mysqli_fetch_assoc($result)) $doctors[] = $row;
    echo json_encode(['success' => true, 'doctors' => $doctors]);
    exit;
}

if ($action === 'get_one') {
    $id = intval($_GET['id'] ?? 0);
    $r  = mysqli_query($con, "SELECT * FROM doctors WHERE id=$id");
    $d  = mysqli_fetch_assoc($r);
    echo json_encode(['success' => true, 'doctor' => $d]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unknown action.']);