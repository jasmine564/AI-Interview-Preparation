<?php
// save_pin.php
include 'cors.php';
include 'db.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['role_title']) || !isset($data['question_id']) || !isset($data['question_data'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$user_id = $_SESSION['user_id'];
$role_title = $data['role_title'];
$question_id = $data['question_id']; // This is the identifier
$question_json = json_encode($data['question_data']);

try {
    // Check if pin exists
    $stmt = $conn->prepare("SELECT id FROM pinned_questions WHERE user_id = ? AND role_title = ? AND question_identifier = ?");
    $stmt->execute([$user_id, $role_title, $question_id]);
    $existing = $stmt->fetch();

    if ($existing) {
        // User wants to UNPIN (toggle interaction usually implies this, or we can have explicit action. 
        // Assuming toggle behavior or we normally just add. 
        // The prompt asked for 'save_pin', implying persistence. 
        // Let's implement explicit 'save' or 'remove'.
        // If the same pin is sent, we probably want to remove it? Or just ensure it's saved?
        // Let's assume this endpoint handles TOGGLE for simplicity on frontend, or just SAVE.
        // Prompt said "save_pin.php". Let's check "success criteria: Pin once -> always visible".
        // Use an 'action' field or just Toggle.
        // I will implement Toggle logic: if exists, delete. If not, insert.
        
        $del = $conn->prepare("DELETE FROM pinned_questions WHERE id = ?");
        $del->execute([$existing['id']]);
        echo json_encode(['status' => 'unpinned', 'id' => $question_id]);
    } else {
        // Insert
        $ins = $conn->prepare("INSERT INTO pinned_questions (user_id, role_title, question_identifier, question_data) VALUES (?, ?, ?, ?)");
        $ins->execute([$user_id, $role_title, $question_id, $question_json]);
        echo json_encode(['status' => 'pinned', 'id' => $question_id]);
    }

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
