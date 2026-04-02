<?php
require_once 'includes/config.php';
header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? '';

switch ($action) {

    case 'list':
        $res  = mysqli_query($con, "SELECT * FROM career_jobs ORDER BY created_at DESC");
        $rows = [];
        while ($row = mysqli_fetch_assoc($res)) $rows[] = $row;
        echo json_encode($rows);
        break;

    case 'get_one':
        $id  = intval($_GET['id'] ?? 0);
        $res = mysqli_query($con, "SELECT * FROM career_jobs WHERE id=$id");
        $row = mysqli_fetch_assoc($res);
        echo $row
            ? json_encode(['success' => true,  'job'     => $row])
            : json_encode(['success' => false, 'message' => 'Job not found.']);
        break;

    case 'create':
        $title  = mysqli_real_escape_string($con, trim($_POST['title']       ?? ''));
        $dept   = mysqli_real_escape_string($con, trim($_POST['department']  ?? ''));
        $type   = in_array($_POST['type']??'',['Full-time','Part-time','Contract','Internship']) ? $_POST['type'] : 'Full-time';
        $loc    = mysqli_real_escape_string($con, trim($_POST['location']    ?? 'Zamboanga City'));
        $date   = mysqli_real_escape_string($con, trim($_POST['posted_date'] ?? ''));
        $status = in_array($_POST['status']??'',['open','closed']) ? $_POST['status'] : 'open';
        $desc   = mysqli_real_escape_string($con, trim($_POST['description'] ?? ''));
        $req    = mysqli_real_escape_string($con, trim($_POST['requirements']?? ''));

        if (!$title) { echo json_encode(['success'=>false,'message'=>'Title is required.']); break; }
        if (!$desc)  { echo json_encode(['success'=>false,'message'=>'Description is required.']); break; }

        $sql = "INSERT INTO career_jobs (title,department,type,location,posted_date,status,description,requirements)
                VALUES ('$title','$dept','$type','$loc','$date','$status','$desc','$req')";
        echo mysqli_query($con, $sql)
            ? json_encode(['success'=>true,  'message'=>'Job posted successfully!'])
            : json_encode(['success'=>false, 'message'=>'DB error: '.mysqli_error($con)]);
        break;

    case 'update':
        $id     = intval($_POST['id'] ?? 0);
        $title  = mysqli_real_escape_string($con, trim($_POST['title']       ?? ''));
        $dept   = mysqli_real_escape_string($con, trim($_POST['department']  ?? ''));
        $type   = in_array($_POST['type']??'',['Full-time','Part-time','Contract','Internship']) ? $_POST['type'] : 'Full-time';
        $loc    = mysqli_real_escape_string($con, trim($_POST['location']    ?? ''));
        $date   = mysqli_real_escape_string($con, trim($_POST['posted_date'] ?? ''));
        $status = in_array($_POST['status']??'',['open','closed']) ? $_POST['status'] : 'open';
        $desc   = mysqli_real_escape_string($con, trim($_POST['description'] ?? ''));
        $req    = mysqli_real_escape_string($con, trim($_POST['requirements']?? ''));

        if (!$id || !$title) { echo json_encode(['success'=>false,'message'=>'Invalid data.']); break; }

        $sql = "UPDATE career_jobs SET
                    title='$title', department='$dept', type='$type',
                    location='$loc', posted_date='$date', status='$status',
                    description='$desc', requirements='$req'
                WHERE id=$id";
        echo mysqli_query($con, $sql)
            ? json_encode(['success'=>true,  'message'=>'Job updated successfully!'])
            : json_encode(['success'=>false, 'message'=>'DB error: '.mysqli_error($con)]);
        break;

    case 'delete':
        $id = intval($_POST['id'] ?? 0);
        if (!$id) { echo json_encode(['success'=>false,'message'=>'Invalid ID.']); break; }
        echo mysqli_query($con, "DELETE FROM career_jobs WHERE id=$id")
            ? json_encode(['success'=>true,  'message'=>'Job deleted successfully!'])
            : json_encode(['success'=>false, 'message'=>'DB error.']);
        break;

    default:
        echo json_encode(['success'=>false,'message'=>'Invalid action.']);
}