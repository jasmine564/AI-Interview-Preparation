<?php
// diagnose_issue.php
include 'load_env.php';

echo "--- DIAGNOSTIC START ---\n";
$apiKey = getenv('AI_API_KEY');

if (!$apiKey) {
    echo "API Key Status: MISSING\n";
    exit();
}

$len = strlen($apiKey);
echo "API Key Status: FOUND\n";
echo "API Key Length: $len characters\n";
echo "API Key Preview: " . substr($apiKey, 0, 10) . "..." . substr($apiKey, -10) . "\n";

// Expected length check (User's key is ~164 chars)
if ($len > 170) {
    echo "WARNING: Key seems suspiciously long. Previous merge error?\n";
}

echo "\nTesting OpenAI API (v1/chat/completions)...\n";

$data = [
    "model" => "gpt-4o-mini",
    "messages" => [
        ["role" => "user", "content" => "Hello"]
    ],
    "max_tokens" => 5
];

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . $apiKey
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Status: $httpCode\n";
if ($error) {
    echo "CURL Error: $error\n";
}
echo "Raw Response:\n$result\n";
echo "--- DIAGNOSTIC END ---\n";
?>
