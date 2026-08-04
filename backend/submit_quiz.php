<?php
// submit_quiz.php
require_once 'cors.php';
require_once 'db.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    echo json_encode(["error" => "Invalid input"]);
    exit;
}

$user_id = isset($input['user_id']) ? (int)$input['user_id'] : 0; // 0 for anonymous
$quiz_id = isset($input['quiz_id']) ? (int)$input['quiz_id'] : 0;
$score = isset($input['score']) ? (int)$input['score'] : 0;
$total = isset($input['total']) ? (int)$input['total'] : 0;

if ($quiz_id <= 0 || $total <= 0) {
    echo json_encode(["error" => "Invalid quiz data"]);
    exit;
}

try {
    // Only save if user is logged in (user_id > 0)
    // You can enable anonymous saving if you have a way to track them, but standard is logged in.
    if ($user_id > 0) {
        $stmt = $conn->prepare("INSERT INTO user_quiz_results (user_id, quiz_id, score, total_questions, completed_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $quiz_id, $score, $total]);
        echo json_encode(["success" => true, "message" => "Result saved successfully"]);
    } else {
        echo json_encode(["success" => true, "message" => "Functionality available but result not saved (Guest mode)"]);
    }

} catch (PDOException $e) {
    error_log("Quiz Submit Error: " . $e->getMessage());
    echo json_encode(["error" => "Database Error: " . $e->getMessage()]);
}
?>
