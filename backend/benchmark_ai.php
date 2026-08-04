<?php
// Test Script for AI Performance

require_once 'cors.php';
require_once 'load_env.php';
require_once 'ai_service.php';

$aiService = new AIService();

echo "Starting Benchmark (gpt-4o-mini, 3 questions)...\n";
$start = microtime(true);

$questions = $aiService->generateQuestions("AI/ML Engineer", "Mid-Level", 1);

$end = microtime(true);
$duration = $end - $start;

echo "Duration: " . number_format($duration, 2) . " seconds\n";

if (isset($questions['error'])) {
    echo "Error: " . print_r($questions['error'], true) . "\n";
} else {
    echo "Generated " . count($questions) . " questions.\n";
    // echo print_r($questions, true);
}
?>
