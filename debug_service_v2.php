<?php
// debug_service_v2.php
include 'backend/ai_service.php';

echo "--- AIService DEBUG START ---\n";

$ai = new AIService();
$reflector = new ReflectionClass($ai);
$property = $reflector->getProperty('apiKey');
$property->setAccessible(true);
$key = $property->getValue($ai);

echo "Loaded API Key Length: " . strlen($key) . "\n";
if (strlen($key) < 50) {
    echo "CRITICAL: API Key is suspiciously short or missing!\n";
} else {
    echo "API Key check passed (Length > 50).\n";
}

echo "Calling generateQuestions...\n";
$questions = $ai->generateQuestions('Debug Role', 'Debug Experience', 1);

echo "Returned Type: " . gettype($questions) . "\n";
print_r($questions);

if (isset($questions['error'])) {
    echo "\nERROR DETECTED: " . $questions['error'] . "\n";
    // We can't see the internal error log here easily unless we cat the file or modify the service.
    // However, ai_service.php logs to error_log.
}

echo "--- AIService DEBUG END ---\n";
?>
