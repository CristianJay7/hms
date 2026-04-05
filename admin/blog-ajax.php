<?php
require_once 'includes/config.php';
header('Content-Type: application/json');

$action     = $_REQUEST['action'] ?? '';
$upload_dir = 'images/blogs/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

function upload_image($file, $upload_dir) {
    $allowed = ['image/jpeg','image/png','image/webp','image/jpg'];
    if (!in_array(strtolower($file['type']), $allowed)) return null;
    $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
    $name = uniqid('blog_') . '.' . $ext;
    $dest = $upload_dir . $name;
    if (move_uploaded_file($file['tmp_name'], $dest)) return '/hms/admin/' . $dest;
    return null;
}

switch ($action) {

    case 'list':
        $res  = mysqli_query($con, "SELECT * FROM blogs ORDER BY published_date DESC, created_at DESC");
        $rows = [];
        while ($row = mysqli_fetch_assoc($res)) $rows[] = $row;
        echo json_encode($rows);
        break;

    case 'get_one':
        $id  = intval($_GET['id'] ?? 0);
        $res = mysqli_query($con, "SELECT * FROM blogs WHERE id=$id");
        $row = mysqli_fetch_assoc($res);
        echo $row
            ? json_encode(['success' => true,  'blog'    => $row])
            : json_encode(['success' => false, 'message' => 'Post not found.']);
        break;

    case 'create':
        $title   = mysqli_real_escape_string($con, trim($_POST['title']          ?? ''));
        $excerpt = mysqli_real_escape_string($con, trim($_POST['excerpt']        ?? ''));
        $content = mysqli_real_escape_string($con, trim($_POST['content']        ?? ''));
        $date    = mysqli_real_escape_string($con, trim($_POST['published_date'] ?? ''));
        $status  = in_array($_POST['status'] ?? '', ['published','draft']) ? $_POST['status'] : 'published';
        $image   = '';

        if (!$title) { echo json_encode(['success' => false, 'message' => 'Title is required.']); break; }
        if (!$date)  { echo json_encode(['success' => false, 'message' => 'Date is required.']);  break; }

        if (!empty($_FILES['image']['name']))
            $image = upload_image($_FILES['image'], $upload_dir) ?? '';

        $sql = "INSERT INTO blogs (title, excerpt, content, image, published_date, status)
                VALUES ('$title','$excerpt','$content','$image','$date','$status')";
        echo mysqli_query($con, $sql)
            ? json_encode(['success' => true,  'message' => 'Post added successfully!'])
            : json_encode(['success' => false, 'message' => 'DB error: ' . mysqli_error($con)]);
        break;

    case 'update':
        $id      = intval($_POST['id']             ?? 0);
        $title   = mysqli_real_escape_string($con, trim($_POST['title']          ?? ''));
        $excerpt = mysqli_real_escape_string($con, trim($_POST['excerpt']        ?? ''));
        $content = mysqli_real_escape_string($con, trim($_POST['content']        ?? ''));
        $date    = mysqli_real_escape_string($con, trim($_POST['published_date'] ?? ''));
        $status  = in_array($_POST['status'] ?? '', ['published','draft']) ? $_POST['status'] : 'published';

        if (!$id || !$title) { echo json_encode(['success' => false, 'message' => 'Invalid data.']); break; }

        $existing_image = trim($_POST['existing_image'] ?? '');
        $image_sql = '';

        if (!empty($_FILES['image']['name'])) {
            // New image uploaded — delete old file first
            $uploaded = upload_image($_FILES['image'], $upload_dir);
            if ($uploaded) {
                if (!empty($existing_image)) {
                    $old_abs = $_SERVER['DOCUMENT_ROOT'] . $existing_image;
                    if (file_exists($old_abs)) unlink($old_abs);
                }
                $esc_uploaded = mysqli_real_escape_string($con, $uploaded);
                $image_sql = ", image='$esc_uploaded'";
            }
        } elseif ($existing_image === '') {
            // "Remove image" was clicked — clear image in DB
            $res_old = mysqli_query($con, "SELECT image FROM blogs WHERE id=$id");
            $row_old = mysqli_fetch_assoc($res_old);
            if (!empty($row_old['image'])) {
                $old_abs = $_SERVER['DOCUMENT_ROOT'] . $row_old['image'];
                if (file_exists($old_abs)) unlink($old_abs);
            }
            $image_sql = ", image=''";
        }

        $sql = "UPDATE blogs SET
                    title='$title', excerpt='$excerpt', content='$content',
                    published_date='$date', status='$status'
                    $image_sql
                WHERE id=$id";
        echo mysqli_query($con, $sql)
            ? json_encode(['success' => true,  'message' => 'Post updated successfully!'])
            : json_encode(['success' => false, 'message' => 'DB error: ' . mysqli_error($con)]);
        break;

    case 'delete':
        $id = intval($_POST['id'] ?? 0);
        if (!$id) { echo json_encode(['success' => false, 'message' => 'Invalid ID.']); break; }

        $res = mysqli_query($con, "SELECT image FROM blogs WHERE id=$id");
        $row = mysqli_fetch_assoc($res);
        if (!empty($row['image']) && file_exists( '/hms/admin' . $row['image'])) unlink('/hms/admin/' .  $row['image']);

        echo mysqli_query($con, "DELETE FROM blogs WHERE id=$id")
            ? json_encode(['success' => true,  'message' => 'Post deleted successfully!'])
            : json_encode(['success' => false, 'message' => 'DB error.']);
        break;


        case 'get_photos':
            $blog_id = intval($_GET['id'] ?? 0);
            $res     = mysqli_query($con, "SELECT * FROM blog_photos WHERE blog_id=$blog_id ORDER BY sort_order ASC, created_at ASC");
            $photos  = [];
            while ($row = mysqli_fetch_assoc($res)) $photos[] = $row;
            echo json_encode(['success' => true, 'photos' => $photos]);
            break;
     
        case 'add_photos':
            $blog_id = intval($_POST['blog_id'] ?? 0);
            if (!$blog_id) { echo json_encode(['success' => false, 'message' => 'Invalid blog ID.']); break; }
     
            $allowed  = ['image/jpeg','image/png','image/webp','image/jpg'];
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
     
                    $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $name = uniqid('gallery_') . '.' . $ext;
                    $dest = 'images/blogs/' . $name;
     
                    if (move_uploaded_file($file['tmp_name'], $dest)) {
                        $path = mysqli_real_escape_string($con, '/hms/admin/' . $dest);
                        mysqli_query($con, "INSERT INTO blog_photos (blog_id, photo) VALUES ($blog_id, '$path')");
                        $uploaded++;
                    } else {
                        $failed++;
                    }
                }
            }
     
            if ($uploaded > 0) {
                echo json_encode(['success' => true, 'message' => "$uploaded photo(s) uploaded successfully!"]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No photos were uploaded.']);
            }
            break;
     
        case 'delete_photo':
            $photo_id = intval($_POST['photo_id'] ?? 0);
            if (!$photo_id) { echo json_encode(['success' => false, 'message' => 'Invalid photo ID.']); break; }
     
            $res = mysqli_query($con, "SELECT photo FROM blog_photos WHERE id=$photo_id");
            $row = mysqli_fetch_assoc($res);
            if (!empty($row['photo']) && file_exists('../' . $row['photo'])) unlink('../' . $row['photo']);
     
            echo mysqli_query($con, "DELETE FROM blog_photos WHERE id=$photo_id")
                ? json_encode(['success' => true,  'message' => 'Photo deleted.'])
                : json_encode(['success' => false, 'message' => 'DB error.']);
            break;







    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}