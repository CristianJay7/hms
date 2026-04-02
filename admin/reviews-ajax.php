<?php
require_once 'includes/config.php';
header('Content-Type: application/json');

$action     = $_REQUEST['action'] ?? '';
$upload_dir = 'images/reviews/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

function upload_photo($file, $upload_dir) {
    $allowed = ['image/jpeg','image/png','image/webp'];
    if (!in_array($file['type'], $allowed)) return null;
    $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
    $name = uniqid('review_') . '.' . $ext;
    $dest = $upload_dir . $name;
    if (move_uploaded_file($file['tmp_name'], $dest)) return 'admin/' . $dest;
    return null;
}

switch ($action) {

    case 'list':
        $res  = mysqli_query($con, "SELECT * FROM reviews ORDER BY review_date DESC");
        $rows = [];
        while ($row = mysqli_fetch_assoc($res)) $rows[] = $row;
        echo json_encode($rows);
        break;

    case 'get_one':
        $id  = intval($_GET['id'] ?? 0);
        $res = mysqli_query($con, "SELECT * FROM reviews WHERE id=$id");
        $row = mysqli_fetch_assoc($res);
        echo $row
            ? json_encode(['success' => true,  'review'  => $row])
            : json_encode(['success' => false, 'message' => 'Review not found.']);
        break;

    case 'create':
        $name   = mysqli_real_escape_string($con, trim($_POST['patient_name'] ?? ''));
        $text   = mysqli_real_escape_string($con, trim($_POST['review_text']  ?? ''));
        $rating = intval($_POST['rating']      ?? 5);
        $date   = mysqli_real_escape_string($con, trim($_POST['review_date']  ?? ''));
        $photo  = '';

        if (!$name) $name = 'Anonymous';
        if (!$text || !$date) {
            echo json_encode(['success' => false, 'message' => 'Please fill all required fields.']);
            break;
        }

        if (!empty($_FILES['photo']['name']))
            $photo = upload_photo($_FILES['photo'], $upload_dir) ?? '';

        $sql = "INSERT INTO reviews (patient_name, review_text, rating, photo, review_date)
                VALUES ('$name','$text',$rating,'$photo','$date')";
        echo mysqli_query($con, $sql)
            ? json_encode(['success' => true,  'message' => 'Review added successfully!'])
            : json_encode(['success' => false, 'message' => 'DB error: ' . mysqli_error($con)]);
        break;

    case 'update':
        $id     = intval($_POST['id']          ?? 0);
        $name   = mysqli_real_escape_string($con, trim($_POST['patient_name'] ?? ''));
        $text   = mysqli_real_escape_string($con, trim($_POST['review_text']  ?? ''));
        $rating = intval($_POST['rating']      ?? 5);
        $date   = mysqli_real_escape_string($con, trim($_POST['review_date']  ?? ''));

        if (!$id || !$name || !$text || !$date) {
            echo json_encode(['success' => false, 'message' => 'Invalid data.']);
            break;
        }

        $photo_sql = '';
        if (!empty($_FILES['photo']['name'])) {
            $uploaded = upload_photo($_FILES['photo'], $upload_dir);
            if ($uploaded) $photo_sql = ", photo='$uploaded'";
        }

        $sql = "UPDATE reviews SET
                    patient_name='$name',
                    review_text='$text',
                    rating=$rating,
                    review_date='$date'
                    $photo_sql
                WHERE id=$id";
        echo mysqli_query($con, $sql)
            ? json_encode(['success' => true,  'message' => 'Review updated successfully!'])
            : json_encode(['success' => false, 'message' => 'DB error: ' . mysqli_error($con)]);
        break;

    case 'delete':
        $id = intval($_POST['id'] ?? 0);
        if (!$id) { echo json_encode(['success' => false, 'message' => 'Invalid ID.']); break; }

        $res = mysqli_query($con, "SELECT photo FROM reviews WHERE id=$id");
        $row = mysqli_fetch_assoc($res);
        if (!empty($row['photo']) && file_exists('../' . $row['photo']))
            unlink('../' . $row['photo']);

        echo mysqli_query($con, "DELETE FROM reviews WHERE id=$id")
            ? json_encode(['success' => true,  'message' => 'Review deleted successfully!'])
            : json_encode(['success' => false, 'message' => 'DB error.']);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}