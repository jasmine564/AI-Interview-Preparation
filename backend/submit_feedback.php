<?php
// submit_feedback.php
require_once 'cors.php';
require_once 'db.php';

header('Content-Type: application/json');

// Get JSON input
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(["error" => "Invalid input"]);
    exit;
}

$user_id = isset($data['user_id']) ? $data['user_id'] : null;
$user_name = isset($data['user_name']) ? $data['user_name'] : 'Anonymous';
$rating = isset($data['rating']) ? (int)$data['rating'] : 0;
$category = isset($data['category']) ? $data['category'] : 'general';
$text = isset($data['text']) ? $data['text'] : '';

if ($rating < 1 || $rating > 5) {
    echo json_encode(["error" => "Rating must be between 1 and 5"]);
    exit;
}

try {
    // If user_id is provided but 0 or invalid (e.g. from frontend default), make it null
    if ($user_id === 0) $user_id = null;

    $stmt = $conn->prepare("INSERT INTO user_feedback (user_id, user_name, rating, category, feedback_text, is_public) VALUES (:uid, :uname, :rating, :cat, :txt, 1)");
    $stmt->execute([
        ':uid' => $user_id,
        ':uname' => $user_name,
        ':rating' => $rating,
        ':cat' => $category,
        ':txt' => $text
    ]);

    echo json_encode(["success" => true, "message" => "Feedback submitted successfully!"]);

} catch (PDOException $e) {
    error_log("Feedback Error: " . $e->getMessage());
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}

