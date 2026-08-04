<?php
require_once 'cors.php';

require_once 'db.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["message" => "Problem ID is required"]);
    exit();
}

$id = $_GET['id'];

try {
    $query = "SELECT * FROM coding_problems WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    
    $problem = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($problem) {
        // Decode JSON fields if they are strings
        // (Depends on driver, but safe to verify)
        // Actually, JSON columns in MySQL are returned as strings in PHP.
        // We deliver raw strings or objects depending on frontend need.
        // Let's decode to ensure clean JSON structure in response.
        $problem['starter_code'] = json_decode($problem['starter_code']);
        $problem['examples'] = json_decode($problem['examples']);

        // Remove sensitive fields or those used only for execution
        unset($problem['test_cases']);
        unset($problem['driver_code']);
        unset($problem['solution_code']); // Security: only show solution if explicitly requested via specific endpoint (future)

        echo json_encode($problem);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Problem not found"]);
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Database error: " . $e->getMessage()]);
}
?>
