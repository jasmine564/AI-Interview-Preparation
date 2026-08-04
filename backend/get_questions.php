<?php
// get_questions.php
ini_set('display_errors', 0);
include_once 'cors.php';
define('SUPPRESS_DB_ERROR', true);
include_once 'db.php';
include_once 'ai_service.php';

session_start();

// Check params or session
$role_id = $_REQUEST['role_id'] ?? $_SESSION['role_id'] ?? null;

if (!$role_id || $role_id === 'undefined') {
    http_response_code(400);
    echo json_encode(["message" => "Missing role_id"]);
    exit();
}

$role_id = intval($role_id);
$_SESSION['role_id'] = $role_id; // Persist for navigation context
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$experience = isset($_GET['experience']) ? $_GET['experience'] : 'Mid-Level';

// FALLBACK MAPPING (defined early for access)
$fallback_roles = [
    1 => "Frontend Developer",
    2 => "Backend Developer",
    3 => "AI/ML Engineer",
    
    4 => "Prompt Engineer",
    5 => "MLOps Engineer",
    6 => "Blockchain Developer",
    7 => "Web3 / Smart Contract Developer",
    8 => "Game Developer",
    9 => "AR / VR Developer",
    10 => "Embedded Systems Engineer",
    11 => "Robotics Engineer",
    12 => "Data Engineer",
    13 => "Business Intelligence (BI) Developer",
    14 => "Technical Program Manager (TPM)",
    15 => "API Integration Specialist",
    16 => "Platform Engineer",
    17 => "Performance Engineer",
    18 => "Security Operations (SOC) Analyst",
    19 => "Ethical Hacker / Penetration Tester"
];

// OFFLINE MODE CHECK
if (!isset($conn) || $conn === null) {
    if (isset($fallback_roles[$role_id])) {
        $role_title = $fallback_roles[$role_id];
        $aiService = new AIService();
        $questions = $aiService->generateQuestions($role_title, $experience, $page);
        
        echo json_encode([
            "role" => $role_title,
            "page" => $page,
            "questions" => $questions
        ]);
        exit(); // Done
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Role not found (Offline Mode)"]);
        exit();
    }
}

try {
    $stmt = $conn->prepare("SELECT title FROM roles WHERE id = :id");
    $stmt->bindParam(':id', $role_id);
    $stmt->execute();
    
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(["message" => "Role not found"]);
        exit();
    }
    
    $role = $stmt->fetch(PDO::FETCH_ASSOC);
    $role_title = $role['title'];
    
    $aiService = new AIService();
    $questions = $aiService->generateQuestions($role_title, $experience, $page);
    
    if (isset($questions['error'])) {
        http_response_code(500);
        echo json_encode(["message" => $questions['error']]);
        exit();
    }// Restore error check to prevent silent failures with 200 OK
    if (isset($questions['error'])) {
        http_response_code(500);
        echo json_encode(["message" => $questions['error']]);
        exit();
    }
    
    echo json_encode([
        "role" => $role_title,
        "page" => $page,
        "questions" => $questions
    ]);

} catch(PDOException $e) {
    // FALLBACK
    http_response_code(500);
    echo json_encode(["message" => "Database error: " . $e->getMessage()]);
}
?>
