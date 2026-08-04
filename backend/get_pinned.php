<?php
// get_pinned.php
include_once 'cors.php';
include_once 'db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["message" => "Unauthorized"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$role_id = $_GET['role_id'] ?? null;

if (!$role_id) {
    http_response_code(400);
    echo json_encode(["message" => "Missing role_id"]);
    exit();
}

try {
    $stmt = $conn->prepare("SELECT question_data FROM pinned_questions WHERE user_id = ? AND role_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id, $role_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $questions = [];
    foreach ($rows as $row) {
        $q = json_decode($row['question_data'], true);
        if ($q) {
            $questions[] = $q;
        }
    }

    echo json_encode(["questions" => $questions]);

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Database error: " . $e->getMessage()]);
}
?>
