<?php
// get_quizzes.php
require_once 'cors.php';
require_once 'db.php';

header('Content-Type: application/json');

try {
    // Select quiz details along with a count of questions
    $sql = "SELECT q.id, q.title, q.difficulty, q.category, q.description, COUNT(qq.id) as question_count 
            FROM quizzes q 
            LEFT JOIN quiz_questions qq ON q.id = qq.quiz_id 
            GROUP BY q.id";
    
    $stmt = $conn->query($sql);
    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($quizzes);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
