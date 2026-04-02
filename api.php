<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'includes/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$input  = json_decode(file_get_contents('php://input'), true);

switch ($method) {

    // ── GET all doctors ──────────────────────────────
    case 'GET':
        $result = $conn->query("SELECT * FROM doctors ORDER BY created_at DESC");
        $doctors = [];
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }
        echo json_encode($doctors);
        break;

    // ── POST create new doctor ───────────────────────
    case 'POST':
        $name           = $conn->real_escape_string($input['name'] ?? '');
        $specialization = $conn->real_escape_string($input['specialization'] ?? '');
        $clinic_hours   = $conn->real_escape_string($input['clinic_hours'] ?? '');
        $availability   = $conn->real_escape_string($input['availability'] ?? '');
        $image          = $conn->real_escape_string($input['image'] ?? '');

        if (!$name || !$specialization || !$clinic_hours || !$availability) {
            echo json_encode(['error' => 'All fields are required.']);
            break;
        }

        $sql = "INSERT INTO doctors (name, specialization, clinic_hours, availability, image)
                VALUES ('$name', '$specialization', '$clinic_hours', '$availability', '$image')";

        if ($conn->query($sql)) {
            echo json_encode(['success' => true, 'id' => $conn->insert_id]);
        } else {
            echo json_encode(['error' => $conn->error]);
        }
        break;

    // ── PUT update doctor ────────────────────────────
    case 'PUT':
        $id             = intval($input['id'] ?? 0);
        $name           = $conn->real_escape_string($input['name'] ?? '');
        $specialization = $conn->real_escape_string($input['specialization'] ?? '');
        $clinic_hours   = $conn->real_escape_string($input['clinic_hours'] ?? '');
        $availability   = $conn->real_escape_string($input['availability'] ?? '');
        $image          = $conn->real_escape_string($input['image'] ?? '');

        if (!$id || !$name || !$specialization || !$clinic_hours || !$availability) {
            echo json_encode(['error' => 'All fields are required.']);
            break;
        }

        $sql = "UPDATE doctors SET
                    name='$name',
                    specialization='$specialization',
                    clinic_hours='$clinic_hours',
                    availability='$availability',
                    image='$image'
                WHERE id=$id";

        if ($conn->query($sql)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => $conn->error]);
        }
        break;

    // ── DELETE doctor ────────────────────────────────
    case 'DELETE':
        $id = intval($input['id'] ?? 0);

        if (!$id) {
            echo json_encode(['error' => 'Invalid ID.']);
            break;
        }

        if ($conn->query("DELETE FROM doctors WHERE id=$id")) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => $conn->error]);
        }
        break;

    default:
        echo json_encode(['error' => 'Method not allowed.']);
        break;
}

$conn->close();
?>