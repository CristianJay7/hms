<?php
include('./includes/session.php');
include('./includes/config.php');
global $con;

header('Content-Type: application/json');

$action     = $_POST['action'] ?? $_GET['action'] ?? '';
$upload_dir = __DIR__ . '/images/facilities/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

function upload_photo_fac($file, $upload_dir) {
    $allowed = ['jpg','jpeg','png','webp'];
    $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) return null;
    $name = 'fac_' . uniqid() . '.' . $ext;
    if (move_uploaded_file($file['tmp_name'], $upload_dir . $name))
        return '/hms/admin/images/facilities/' . $name;
    return null;
}

// ── Add / Edit ──
if ($action === 'add' || $action === 'edit') {
    $name             = mysqli_real_escape_string($con, trim($_POST['name']             ?? ''));
    $description      = mysqli_real_escape_string($con, trim($_POST['description']      ?? ''));
    $services_offered = mysqli_real_escape_string($con, trim($_POST['services_offered'] ?? ''));
    $schedules        = mysqli_real_escape_string($con, trim($_POST['schedules']         ?? ''));

    if (!$name) { echo json_encode(['success'=>false,'message'=>'Facility name is required.']); exit; }

    $image = '/hms/admin/images/default.jpg';
    if ($action === 'edit') {
        $existing = trim($_POST['existing_image'] ?? '');
        $image    = $existing ?: '/hms/admin/images/default.jpg';
    }

    if (!empty($_FILES['image']['name'])) {
        $uploaded = upload_photo_fac($_FILES['image'], $upload_dir);
        if ($uploaded) $image = $uploaded;
        else { echo json_encode(['success'=>false,'message'=>'Upload failed.']); exit; }
    }

    $image = mysqli_real_escape_string($con, $image);

    if ($action === 'add') {
        $sql = "INSERT INTO facilities (name, description, image, services_offered, schedules)
                VALUES ('$name','$description','$image','$services_offered','$schedules')";
        mysqli_query($con, $sql);
        echo json_encode(['success'=>true,'message'=>'Facility added successfully.','id'=>mysqli_insert_id($con)]);
    } else {
        $id  = intval($_POST['id'] ?? 0);
        $sql = "UPDATE facilities SET name='$name', description='$description', image='$image',
                services_offered='$services_offered', schedules='$schedules' WHERE id=$id";
        echo mysqli_query($con, $sql)
            ? json_encode(['success'=>true, 'message'=>'Facility updated successfully.'])
            : json_encode(['success'=>false,'message'=>mysqli_error($con)]);
    }
    exit;
}

// ── Delete ──
if ($action === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    echo mysqli_query($con, "DELETE FROM facilities WHERE id=$id")
        ? json_encode(['success'=>true, 'message'=>'Facility deleted.'])
        : json_encode(['success'=>false,'message'=>mysqli_error($con)]);
    exit;
}

// ── Fetch all ──
if ($action === 'fetch') {
    $facilities = [];
    $result = mysqli_query($con, "SELECT * FROM facilities ORDER BY created_at DESC");
    while ($row = mysqli_fetch_assoc($result)) $facilities[] = $row;
    echo json_encode(['success'=>true,'facilities'=>$facilities]);
    exit;
}

// ── Get one ──
if ($action === 'get_one') {
    $id = intval($_GET['id'] ?? 0);
    $r  = mysqli_query($con, "SELECT * FROM facilities WHERE id=$id");
    $d  = mysqli_fetch_assoc($r);
    echo json_encode(['success'=>true,'facility'=>$d]);
    exit;
}

// ── Get photos ──
if ($action === 'get_photos') {
    $id     = intval($_GET['id'] ?? 0);
    $res    = mysqli_query($con, "SELECT * FROM facility_photos WHERE facility_id=$id ORDER BY sort_order ASC, created_at ASC");
    $photos = [];
    while ($row = mysqli_fetch_assoc($res)) $photos[] = $row;
    echo json_encode(['success'=>true,'photos'=>$photos]);
    exit;
}

// ── Add photos ──
if ($action === 'add_photos') {
    $facility_id = intval($_POST['facility_id'] ?? 0);
    if (!$facility_id) { echo json_encode(['success'=>false,'message'=>'Invalid ID.']); exit; }

    $allowed  = ['jpg','jpeg','png','webp'];
    $uploaded = 0;
    if (!empty($_FILES['photos']['name'][0])) {
        $count = count($_FILES['photos']['name']);
        for ($i = 0; $i < $count; $i++) {
            $file = [
                'name'     => $_FILES['photos']['name'][$i],
                'type'     => $_FILES['photos']['type'][$i],
                'tmp_name' => $_FILES['photos']['tmp_name'][$i],
                'error'    => $_FILES['photos']['error'][$i],
            ];
            if ($file['error'] !== 0) continue;
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) continue;
            $name = 'fac_gallery_' . uniqid() . '.' . $ext;
            $dest = $upload_dir . $name;
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $path = mysqli_real_escape_string($con, '/hms/admin/images/facilities/' . $name);
                mysqli_query($con, "INSERT INTO facility_photos (facility_id, photo) VALUES ($facility_id, '$path')");
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
    $res = mysqli_query($con, "SELECT photo FROM facility_photos WHERE id=$photo_id");
    $row = mysqli_fetch_assoc($res);
    if (!empty($row['photo'])) {
        $path = str_replace('/hms/admin/', __DIR__ . '/', $row['photo']);
        if (file_exists($path)) unlink($path);
    }
    echo mysqli_query($con, "DELETE FROM facility_photos WHERE id=$photo_id")
        ? json_encode(['success'=>true, 'message'=>'Photo deleted.'])
        : json_encode(['success'=>false,'message'=>'DB error.']);
    exit;
}

echo json_encode(['success'=>false,'message'=>'Unknown action.']);