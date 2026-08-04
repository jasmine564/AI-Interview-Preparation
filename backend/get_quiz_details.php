<?php
// get_quiz_details.php
require_once 'cors.php';
require_once 'db.php';

header('Content-Type: application/json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid Quiz ID"]);
    exit;
}

try {
    // 1. Get Quiz Metadata
    $stmt = $conn->prepare("SELECT title, description, difficulty FROM quizzes WHERE id = ?");
    $stmt->execute([$id]);
    $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$quiz) {
        http_response_code(404);
        echo json_encode(["error" => "Quiz not found"]);
        exit;
    }

    // 2. Get Questions (Exclude correct_index/explanation to prevent cheating on client side if desired, 
    //    but for instant feedback mode we might send them. Let's send them so frontend can show results instantly.)
    //    Ideally, we should only send them if we trust the client or if it's 'practice mode'.
    //    For this MVP, we will send everything so the UI can handle instant checking.
    
    $stmt = $conn->prepare("SELECT id, question_text, options, correct_index, explanation FROM quiz_questions WHERE quiz_id = ?");
    $stmt->execute([$id]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Decode options JSON
    foreach ($questions as &$q) {
        $q['options'] = json_decode($q['options']);
    }

    echo json_encode([
        "quiz" => $quiz,
        "questions" => $questions
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
