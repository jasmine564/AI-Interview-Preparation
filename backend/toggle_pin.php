<?php
// toggle_pin.php
include_once 'cors.php';
include_once 'db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["message" => "Unauthorized"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['role_id']) || !isset($data['question_id'])) {
    http_response_code(400);
    echo json_encode(["message" => "Missing role_id or question_id"]);
    exit();
}

$role_id = $data['role_id'];
$question_id = $data['question_id'];
$question_data = isset($data['question_data']) ? json_encode($data['question_data']) : null;

try {
    // Check if already pinned
    $stmt = $conn->prepare("SELECT id FROM pinned_questions WHERE user_id = ? AND role_id = ? AND question_identifier = ?");
    $stmt->execute([$user_id, $role_id, $question_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        // Unpin
        $del = $conn->prepare("DELETE FROM pinned_questions WHERE id = ?");
        $del->execute([$existing['id']]);
        echo json_encode(["status" => "unpinned", "message" => "Question unpinned"]);
    } else {
        // Pin
        if (!$question_data) {
            http_response_code(400);
            echo json_encode(["message" => "Missing question data for pinning"]);
            exit();
        }
        $ins = $conn->prepare("INSERT INTO pinned_questions (user_id, role_id, question_identifier, question_data) VALUES (?, ?, ?, ?)");
        $ins->execute([$user_id, $role_id, $question_id, $question_data]);
        echo json_encode(["status" => "pinned", "message" => "Question pinned"]);
    }

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Database error: " . $e->getMessage()]);
}
?>
