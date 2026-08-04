<?php
// debug_ai.php
include 'load_env.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>AI Debugger</h2>";

// 1. Check API Key
$apiKey = getenv('AI_API_KEY');
if ($apiKey) {
    echo "<p style='color:green'>[PASS] AI_API_KEY found in environment.</p>";
    $masked = substr($apiKey, 0, 7) . '...' . substr($apiKey, -4);
    echo "Key: $masked<br>";
} else {
    echo "<p style='color:red'>[FAIL] AI_API_KEY NOT found.</p>";
    exit();
}

// 2. Test Connection
echo "Testing connection to OpenAI...<br>";
$ch = curl_init('https://api.openai.com/v1/models');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . $apiKey
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$result = curl_exec($ch);
$error = curl_error($ch);
$info = curl_getinfo($ch);
curl_close($ch);

if ($error) {
    echo "<p style='color:red'>[FAIL] Curl Error: $error</p>";
} else {
    $httpCode = $info['http_code'];
    if ($httpCode === 200) {
        echo "<p style='color:green'>[PASS] Connection successful (HTTP 200).</p>";
        $data = json_decode($result, true);
        echo "Found " . count($data['data']) . " models.<br>";
    } else {
        echo "<p style='color:red'>[FAIL] Connection failed (HTTP $httpCode).</p>";
        echo "Response: $result";
    }
}
?>
