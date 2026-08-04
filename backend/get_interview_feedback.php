<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

include_once 'ai_service.php';

$data = json_decode(file_get_contents('php://input'), true);
$question = $data['question'] ?? '';
$answer = $data['answer'] ?? '';
$role = $data['role'] ?? 'candidate';

// STRICT RULE: Empty check
if (empty(trim($answer)) || trim($answer) === "No answer provided.") {
    // User did NOT answer
    echo json_encode([
        "relevance" => "relevant",
        "score" => 0,
        "feedback_narrative" => "The user did not provide an answer for this question.", // Logic Rule 2
        "strengths" => "No answer provided.",
        "weaknesses" => "Attempt to answer the question to receive feedback.",
        "missing_points" => "Full answer missing.",
        "star_analysis" => "N/A",
        "sample_response_text" => "A strong answer would follow the STAR method. For example: \"In my previous role, I encountered [Situation]... I decided to [Task]... So I [Action]... which led to [Result].\" This structure ensures clarity and impact.",
        "sample_response_type" => "perfect"
    ]);
    exit;
}

// Instantiate AI Service
$ai = new AIService();
$result = $ai->analyzeInterviewAnswer($question, $answer, $role);

if (isset($result['error'])) {
    // FALLBACK TO MOCK OPTIONALLY OR RETURN ERROR
    // For now, let's return a basic fallback error or simple mock to avoid crashing
    $score = 5;
    $feedbackText = "We could not reach the AI service at this moment. However, a good answer generally includes specific examples and follows the STAR method.";
    
    echo json_encode([
        "relevance" => "relevant",
        "score" => $score,
        "feedback_narrative" => $feedbackText,
        "strengths" => "N/A (Offline)",
        "weaknesses" => "N/A (Offline)",
        "missing_points" => "Connection to AI failed.",
        "star_analysis" => "N/A",
        "sample_response_text" => "Please check your backend configuration or internet connection.",
        "sample_response_type" => "perfect"
    ]);
    exit;
}

// Success - Map AI result to Frontend
echo json_encode([
    "relevance" => $result['is_off_topic'] ? "off_topic" : "relevant",
    "score" => $result['score'],
    "feedback_narrative" => $result['feedback_narrative'],
    "strengths" => $result['strengths'],
    "weaknesses" => $result['weaknesses'],
    "missing_points" => $result['missing_points'],
    "star_analysis" => $result['star_analysis'],
    "sample_response_text" => $result['sample_response_text'],
    "sample_response_type" => $result['sample_response_type']
]);
?>
