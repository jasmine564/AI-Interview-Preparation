<?php
// backend/upload_resume.php

require_once 'cors.php';
require_once 'db.php';
require_once 'ResumeParser.php';
require_once 'ai_service.php';

header("Content-Type: application/json");

// 1. Check Request Method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

// 2. Check File Upload
if (!isset($_FILES['resume_file']) || $_FILES['resume_file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(["error" => "No file uploaded or upload error."]);
    exit;
}

$file = $_FILES['resume_file'];
$jobDescription = $_POST['job_description'] ?? null;

// Validate File Type
$allowedExtensions = ['pdf', 'docx'];
$fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($fileExt, $allowedExtensions)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid file type. Only PDF and DOCX are allowed."]);
    exit;
}

// Validate File Size (Max 5MB)
if ($file['size'] > 5 * 1024 * 1024) {
    http_response_code(400);
    echo json_encode(["error" => "File too large. Max 5MB allowed."]);
    exit;
}

// 3. Move File to Temp/Uploads
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$newFileName = uniqid('resume_', true) . '.' . $fileExt;
$destination = $uploadDir . $newFileName;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to save uploaded file."]);
    exit;
}

try {
    // 4. Extract Text
    $parser = new ResumeParser();
    $extractedText = "";
    
    try {
        $extractedText = $parser->parse($destination);
    } catch (Exception $e) {
        // Log the specific error
        error_log("Resume Parsing Error: " . $e->getMessage());
        // We can't proceed if we can't read the file
        http_response_code(422); // Unprocessable Entity
        echo json_encode([
            "error" => "Could not read text from document. Please ensure it is a text-based PDF/DOCX (not a scanned image) and try again. Internal: " . $e->getMessage()
        ]);
        unlink($destination); // Clean up
        exit;
    }

    // DEBUG LOGGING
    file_put_contents(__DIR__ . '/debug_extracted_text.txt', $extractedText);

    // CRITICAL: Fail fast if text is empty or too short
    if (strlen(trim($extractedText)) < 50) {
        http_response_code(422);
        echo json_encode([
            "error" => "The uploaded file appears to be empty or unreadable (text length < 50 chars). Please upload a valid text-based resume."
        ]);
        unlink($destination); // Clean up
        exit;
    }

    // 5. Analyze with AI
    $aiService = new AIService();
    $analysisResult = $aiService->analyzeResume($extractedText, $jobDescription);

    // DEBUG LOGGING
    file_put_contents(__DIR__ . '/debug_ai_response.json', json_encode($analysisResult, JSON_PRETTY_PRINT));

    if (!$analysisResult) {
        throw new Exception("AI Analysis failed to return a valid response.");
    }

    // 6. Return Response
    echo json_encode([
        "success" => true,
        "data" => $analysisResult,
        "extracted_text_preview" => substr($extractedText, 0, 200) . "..." // Optional debug info
    ]);

    // Cleanup successful upload too
    unlink($destination); 

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
