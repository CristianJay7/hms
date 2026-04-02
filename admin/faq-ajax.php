<?php
require_once 'includes/config.php';
header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? '';

switch ($action) {

    case 'list':
        $res  = mysqli_query($con, "SELECT * FROM faqs ORDER BY sort_order ASC, created_at ASC");
        $rows = [];
        while ($row = mysqli_fetch_assoc($res)) $rows[] = $row;
        echo json_encode($rows);
        break;

    case 'get_one':
        $id  = intval($_GET['id'] ?? 0);
        $res = mysqli_query($con, "SELECT * FROM faqs WHERE id=$id");
        $row = mysqli_fetch_assoc($res);
        echo $row
            ? json_encode(['success' => true,  'faq'     => $row])
            : json_encode(['success' => false, 'message' => 'FAQ not found.']);
        break;

    case 'create':
        $question = mysqli_real_escape_string($con, trim($_POST['question']   ?? ''));
        $answer   = mysqli_real_escape_string($con, trim($_POST['answer']     ?? ''));
        $order    = intval($_POST['sort_order'] ?? 0);
        $status   = in_array($_POST['status']??'',['published','draft']) ? $_POST['status'] : 'published';

        if (!$question || !$answer) {
            echo json_encode(['success' => false, 'message' => 'Question and answer are required.']);
            break;
        }

        $sql = "INSERT INTO faqs (question, answer, sort_order, status)
                VALUES ('$question','$answer',$order,'$status')";
        echo mysqli_query($con, $sql)
            ? json_encode(['success' => true,  'message' => 'FAQ added successfully!'])
            : json_encode(['success' => false, 'message' => 'DB error: ' . mysqli_error($con)]);
        break;

    case 'update':
        $id       = intval($_POST['id'] ?? 0);
        $question = mysqli_real_escape_string($con, trim($_POST['question']   ?? ''));
        $answer   = mysqli_real_escape_string($con, trim($_POST['answer']     ?? ''));
        $order    = intval($_POST['sort_order'] ?? 0);
        $status   = in_array($_POST['status']??'',['published','draft']) ? $_POST['status'] : 'published';

        if (!$id || !$question || !$answer) {
            echo json_encode(['success' => false, 'message' => 'Invalid data.']);
            break;
        }

        $sql = "UPDATE faqs SET question='$question', answer='$answer', sort_order=$order, status='$status' WHERE id=$id";
        echo mysqli_query($con, $sql)
            ? json_encode(['success' => true,  'message' => 'FAQ updated successfully!'])
            : json_encode(['success' => false, 'message' => 'DB error: ' . mysqli_error($con)]);
        break;

    case 'delete':
        $id = intval($_POST['id'] ?? 0);
        if (!$id) { echo json_encode(['success' => false, 'message' => 'Invalid ID.']); break; }
        echo mysqli_query($con, "DELETE FROM faqs WHERE id=$id")
            ? json_encode(['success' => true,  'message' => 'FAQ deleted successfully!'])
            : json_encode(['success' => false, 'message' => 'DB error.']);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}