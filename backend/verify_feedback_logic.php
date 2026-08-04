<?php
// verify_feedback_logic.php
// Validates the behavior of get_interview_feedback.php directly

echo "Endpoint: backend/get_interview_feedback.php\n";
echo "---------------------------------------------------\n";

function test($name, $payload) {
    echo "\nTEST: $name\n";
    
    $ch = curl_init('http://localhost/ai-interview-project/backend/get_interview_feedback.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "HTTP Code: $httpCode\n";
    $json = json_decode($response, true);
    
    if (!$json) {
        echo "Response: " . $response . "\n";
        echo "FAIL: Invalid JSON\n";
        return;
    }

    echo "Relevance: " . ($json['relevance'] ?? 'N/A') . "\n";
    echo "Score: " . ($json['score'] ?? 'N/A') . "\n";
    echo "Feedback Snippet: " . substr($json['feedback_narrative'] ?? '', 0, 100) . "...\n";
    
    // Validations
    if ($payload['answer'] === "") {
        if (($json['score'] ?? -1) === 0 && strpos($json['feedback_narrative'], "did not provide an answer") !== false) {
             echo "PASS: Empty answer handled correctly (Score 0, Correct Message).\n";
        } else {
             echo "FAIL: Empty answer didn't match strict rules.\n";
        }
    } else {
        if (($json['score'] ?? 0) > 0 || strpos($json['feedback_narrative'], "We could not reach") !== false) {
             echo "PASS: Non-empty answer attempted processing (or hit fallback).\n";
        }
    }
}

// 1. Empty Answer
test("Empty Answer Check", [
    "question" => "Tell me about yourself.",
    "answer" => "",
    "role" => "Software Engineer"
]);

// 2. Non-Empty Answer
test("Normal Answer Check", [
    "question" => "Tell me about yourself.",
    "answer" => "I am a software engineer with 5 years of experience...",
    "role" => "Software Engineer"
]);
?>
