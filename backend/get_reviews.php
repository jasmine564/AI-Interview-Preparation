<?php
// get_reviews.php
require_once 'cors.php';
require_once 'db.php';

header('Content-Type: application/json');


try {
    // Fetch latest 5 reviews with 4 or 5 star ratings
    // Join with users table if needed, but we stored user_name in feedback table for simplicity/caching
    $stmt = $conn->query("SELECT user_name, rating, feedback_text, created_at FROM user_feedback WHERE is_public = 1 AND rating >= 4 ORDER BY created_at DESC LIMIT 6");
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($reviews);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

