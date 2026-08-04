<?php
require_once 'cors.php';

require_once 'db.php';

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params(['lifetime'=>0, 'path'=>'/', 'domain'=>'localhost', 'secure'=>false, 'httponly'=>true, 'samesite'=>'Lax']);
        session_start();
    }

    $user_id = $_SESSION['user_id'] ?? null;

    if ($user_id) {
        $query = "SELECT p.id, p.title, p.slug, p.difficulty, p.topic, p.created_at, 
                  (CASE WHEN sp.problem_id IS NOT NULL THEN 1 ELSE 0 END) as is_solved 
                  FROM coding_problems p 
                  LEFT JOIN solved_problems sp ON p.id = sp.problem_id AND sp.user_id = :uid 
                  ORDER BY p.id ASC";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':uid', $user_id);
    } else {
        $query = "SELECT id, title, slug, difficulty, topic, created_at, 0 as is_solved 
                  FROM coding_problems 
                  ORDER BY id ASC";
        $stmt = $conn->prepare($query);
    }

    $stmt->execute();
    
    $problems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($problems);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Database error: " . $e->getMessage()]);
}
?>
