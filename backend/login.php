<?php
// login.php


include_once 'cors.php';
include_once 'db.php';

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => 'localhost',
    'secure' => false, // Set to true if using HTTPS
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

if (!isset($data->email) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data. Required: email, password"]);
    exit();
}

$email = trim(htmlspecialchars(strip_tags($data->email)));
$password = $data->password;

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["message" => "Fields cannot be empty"]);
    exit();
}

$query = "SELECT id, full_name, password FROM users WHERE email = :email LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $email);

try {
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $id = $row['id'];
        $full_name = $row['full_name'];
        $stored_password_hash = $row['password'];


        if (password_verify($password, $stored_password_hash)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['email'] = $email;
            
            http_response_code(200);
            echo json_encode([
                "message" => "Login successful",
                "user" => [
                    "id" => $id,
                    "full_name" => $full_name,
                    "email" => $email
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Invalid email or password"]);
        }
    } else {
        http_response_code(401);
        echo json_encode(["message" => "Invalid email or password"]);
    }
} catch (PDOException $e) {
    error_log("Database error login: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["message" => "Internal server error"]);
}
?>
