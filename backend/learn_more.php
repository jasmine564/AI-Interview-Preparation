<?php
// learn_more.php
include_once 'cors.php';
define('SUPPRESS_DB_ERROR', true);
include_once 'db.php';
include_once 'ai_service.php';

session_start();

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->question) || !isset($data->role)) {
    http_response_code(400);
    echo json_encode(["message" => "Missing question or role"]);
    exit();
}

$question = $data->question;
$role = $data->role;

$aiService = new AIService();
$response = $aiService->generateExplanation($question, $role);

// Response is now just { "content": "markdown string" } from the service
echo json_encode($response);
?>
