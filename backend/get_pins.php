<?php
// get_pins.php
include 'cors.php';
include 'db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$role_title = $_GET['role_title'] ?? '';

if (empty($role_title)) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing role_title']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("SELECT question_data FROM pinned_questions WHERE user_id = ? AND role_title = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id, $role_title]);
    
    $questions = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $questions[] = json_decode($row['question_data'], true);
    }

    echo json_encode(['questions' => $questions]);

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
