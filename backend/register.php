<?php
// register.php

include_once 'cors.php';
include_once 'db.php';


session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => 'localhost',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"));

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(["message" => "Invalid JSON data"]);
    exit();
}

if (!isset($data->full_name) || !isset($data->email) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data. Required: full_name, email, password"]);
    exit();
}

$full_name = trim(htmlspecialchars(strip_tags($data->full_name)));
$email = trim(htmlspecialchars(strip_tags($data->email)));
$password = $data->password;

if (empty($full_name) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["message" => "Fields cannot be empty"]);
    exit();
}

// Check if email already exists
$check_email_query = "SELECT id FROM users WHERE email = :email LIMIT 1";
$stmt = $conn->prepare($check_email_query);
$stmt->bindParam(':email', $email);

try {
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        http_response_code(409); // Conflict
        echo json_encode(["message" => "Email already exists"]);
        exit();
    }
} catch (PDOException $e) {
    error_log("Database error check email: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["message" => "Internal server error"]);
    exit();
}

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$query = "INSERT INTO users (full_name, email, password) VALUES (:full_name, :email, :password)";
$stmt = $conn->prepare($query);

$stmt->bindParam(':full_name', $full_name);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':password', $password_hash);

try {
    if ($stmt->execute()) {
        $user_id = $conn->lastInsertId();
        
        // Auto-login: Set session variables
        $_SESSION['user_id'] = $user_id;
        $_SESSION['full_name'] = $full_name;
        $_SESSION['email'] = $email;

        http_response_code(201);
        echo json_encode([
            "message" => "User registered successfully",
            "user" => [
                "id" => $user_id,
                "full_name" => $full_name,
                "email" => $email
            ]
        ]);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Unable to register user"]);
    }
} catch (PDOException $e) {
    error_log("Database error insert user: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["message" => "Internal server error"]);
}
?>
