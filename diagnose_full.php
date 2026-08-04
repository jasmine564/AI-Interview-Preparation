<?php
// diagnose_full.php
include 'backend/ai_service.php';

error_reporting(E_ALL);

echo "--- FULL SIMULATION START ---\n";

$ai = new AIService();
echo "AIService instantiated.\n";

echo "Calling generateQuestions('Frontend Developer', 'Mid-Level', 1)...\n";
$start = microtime(true);
$questions = $ai->generateQuestions('Frontend Developer', 'Mid-Level', 1);
$end = microtime(true);

echo "Time taken: " . round($end - $start, 2) . "s\n";

echo "Result Type: " . gettype($questions) . "\n";
print_r($questions);

if (isset($questions['error'])) {
    echo "\nFAILURE DETECTED: " . json_encode($questions['error']) . "\n";
} else {
    echo "\nSUCCESS: Generated " . count($questions) . " questions.\n";
}

echo "--- FULL SIMULATION END ---\n";
?>
