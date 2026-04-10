<?php
include('./includes/session.php');
include('./includes/config.php');
global $con;

header('Content-Type: application/json');

$action     = $_POST['action'] ?? $_GET['action'] ?? '';
$upload_dir = __DIR__ . '/images/services/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

function upload_photo_svc($file, $upload_dir) {
    $allowed = ['jpg','jpeg','png','webp'];
    $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) return null;
    $name = 'svc_' . uniqid() . '.' . $ext;
    if (move_uploaded_file($file['tmp_name'], $upload_dir . $name))
        return '/hms/admin/images/services/' . $name;
    return null;
}

// ── Add / Edit ──
if ($action === 'add' || $action === 'edit') {
    $name             = mysqli_real_escape_string($con, trim($_POST['name']             ?? ''));
    $description      = mysqli_real_escape_string($con, trim($_POST['description']      ?? ''));
    $icon             = mysqli_real_escape_string($con, trim($_POST['icon']             ?? 'fa-solid fa-stethoscope'));
    $services_offered = mysqli_real_escape_string($con, trim($_POST['services_offered'] ?? ''));
    $schedules        = mysqli_real_escape_string($con, trim($_POST['schedules']         ?? ''));

    if (!$name) { echo json_encode(['success'=>false,'message'=>'Service name is required.']); exit; }

    $image = '/hms/admin/images/default.jpg';
    if ($action === 'edit') {
        $existing = trim($_POST['existing_image'] ?? '');
        $image    = $existing ?: '/hms/admin/images/default.jpg';
    }

    if (!empty($_FILES['image']['name'])) {
        $uploaded = upload_photo_svc($_FILES['image'], $upload_dir);
        if ($uploaded) $image = $uploaded;
        else { echo json_encode(['success'=>false,'message'=>'Upload failed.']); exit; }
    }

    $image = mysqli_real_escape_string($con, $image);

    if ($action === 'add') {
        $sql = "INSERT INTO services (name, description, image, icon, services_offered, schedules)
                VALUES ('$name','$description','$image','$icon','$services_offered','$schedules')";
        mysqli_query($con, $sql);
        echo json_encode(['success'=>true,'message'=>'Service added successfully.','id'=>mysqli_insert_id($con)]);
    } else {
        $id  = intval($_POST['id'] ?? 0);
        $sql = "UPDATE services SET name='$name', description='$description', image='$image',
                icon='$icon', services_offered='$services_offered', schedules='$schedules' WHERE id=$id";
        echo mysqli_query($con, $sql)
            ? json_encode(['success'=>true, 'message'=>'Service updated successfully.'])
            : json_encode(['success'=>false,'message'=>mysqli_error($con)]);
    }
    exit;
}

if ($action === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    echo mysqli_query($con, "DELETE FROM services WHERE id=$id")
        ? json_encode(['success'=>true, 'message'=>'Service deleted.'])
        : json_encode(['success'=>false,'message'=>mysqli_error($con)]);
    exit;
}

if ($action === 'fetch') {
    $services = [];
    $result   = mysqli_query($con, "SELECT * FROM services ORDER BY created_at DESC");
    while ($row = mysqli_fetch_assoc($result)) $services[] = $row;
    echo json_encode(['success'=>true,'services'=>$services]);
    exit;
}

if ($action === 'get_one') {
    $id = intval($_GET['id'] ?? 0);
    $r  = mysqli_query($con, "SELECT * FROM services WHERE id=$id");
    $d  = mysqli_fetch_assoc($r);
    echo json_encode(['success'=>true,'service'=>$d]);
    exit;
}

// ── Get photos ──
if ($action === 'get_photos') {
    $id     = intval($_GET['id'] ?? 0);
    $res    = mysqli_query($con, "SELECT * FROM service_photos WHERE service_id=$id ORDER BY sort_order ASC, created_at ASC");
    $photos = [];
    while ($row = mysqli_fetch_assoc($res)) $photos[] = $row;
    echo json_encode(['success'=>true,'photos'=>$photos]);
    exit;
}

// ── Add photos ──
if ($action === 'add_photos') {
    $service_id = intval($_POST['service_id'] ?? 0);
    if (!$service_id) { echo json_encode(['success'=>false,'message'=>'Invalid ID.']); exit; }

    $allowed  = ['jpg','jpeg','png','webp'];
    $uploaded = 0;
    if (!empty($_FILES['photos']['name'][0])) {
        $count = count($_FILES['photos']['name']);
        for ($i = 0; $i < $count; $i++) {
            $file = [
                'name'     => $_FILES['photos']['name'][$i],
                'tmp_name' => $_FILES['photos']['tmp_name'][$i],
                'error'    => $_FILES['photos']['error'][$i],
            ];
            if ($file['error'] !== 0) continue;
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) continue;
            $name = 'svc_gallery_' . uniqid() . '.' . $ext;
            $dest = $upload_dir . $name;
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $path = mysqli_real_escape_string($con, '/hms/admin/images/services/' . $name);
                mysqli_query($con, "INSERT INTO service_photos (service_id, photo) VALUES ($service_id, '$path')");
                $uploaded++;
            }
        }
    }
    echo $uploaded > 0
        ? json_encode(['success'=>true, 'message'=>"$uploaded photo(s) uploaded!"])
        : json_encode(['success'=>false,'message'=>'No photos uploaded.']);
    exit;
}

// ── Delete photo ──
if ($action === 'delete_photo') {
    $photo_id = intval($_POST['photo_id'] ?? 0);
    $res = mysqli_query($con, "SELECT photo FROM service_photos WHERE id=$photo_id");
    $row = mysqli_fetch_assoc($res);
    if (!empty($row['photo'])) {
        $path = str_replace('/hms/admin/', __DIR__ . '/', $row['photo']);
        if (file_exists($path)) unlink($path);
    }
    echo mysqli_query($con, "DELETE FROM service_photos WHERE id=$photo_id")
        ? json_encode(['success'=>true, 'message'=>'Photo deleted.'])
        : json_encode(['success'=>false,'message'=>'DB error.']);
    exit;
}

echo json_encode(['success'=>false,'message'=>'Unknown action.']);