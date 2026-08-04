<?php
// db.php

$host = '127.0.0.1';
$db_name = 'ai_interview_db';
$username = 'root';
$password = '';

try {
    // Suppress display errors to prevent HTML/warnings from breaking JSON responses
    ini_set('display_errors', 0);

    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password, [
        PDO::ATTR_TIMEOUT => 5, // Increase timeout to 5 seconds
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch(PDOException $e) {
    // Log error to server logs instead of exposing details
    error_log("Connection failed: " . $e->getMessage());
    // Use @ to suppress warnings if file is not writable
    @file_put_contents(__DIR__ . '/db_connection_error.log', date('Y-m-d H:i:s') . " - Connection failed: " . $e->getMessage() . "\n", FILE_APPEND);
    
    // Allow scripts to handle the error (Fallback Mode) instead of hard exit
    if (defined('SUPPRESS_DB_ERROR') && SUPPRESS_DB_ERROR) {
        $conn = null; // Ensure variable is null
    } else {
        // Ensure proper content type for JSON response
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
        http_response_code(500);
        echo json_encode(["message" => "Database connection failed. Please ensure XAMPP MySQL is running.", "error" => "Database connection failed"]); // Use 'message' for frontend compatibility
        exit();
    }
}

