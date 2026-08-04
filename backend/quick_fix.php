<?php
// backend/quick_fix.php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // 1. Connect to MySQL server (no DB selected)
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to MySQL.<br>";

    // 2. Create Database
    $conn->exec("CREATE DATABASE IF NOT EXISTS ai_interview_db");
    echo "Database 'ai_interview_db' checked/created.<br>";

    // 3. Select Database
    $conn->exec("USE ai_interview_db");

    // 4. Create Tables
    $conn->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $conn->exec("CREATE TABLE IF NOT EXISTS roles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL UNIQUE,
        description TEXT,
        icon VARCHAR(50) DEFAULT 'FaCode',
        question_count INT DEFAULT 20
    )");
    echo "Tables ensured.<br>";

    // 5. Populate Roles
    $roles_data = [
        "Prompt Engineer" => "Design and optimize prompts to improve AI model accuracy and response quality.",
        "MLOps Engineer" => "Manage, deploy, and monitor machine learning models in production environments.",
        "Blockchain Developer" => "Build decentralized applications using blockchain technologies and protocols.",
        "Web3 / Smart Contract Developer" => "Develop and deploy secure smart contracts and Web3-based applications.",
        "Game Developer" => "Design and develop interactive games using modern game engines and frameworks.",
        "AR / VR Developer" => "Create immersive augmented and virtual reality experiences across platforms.",
        "Embedded Systems Engineer" => "Develop software for hardware-based and real-time embedded systems.",
        "Robotics Engineer" => "Design intelligent robotic systems combining hardware, software, and AI.",
        "Data Engineer" => "Build and maintain scalable data pipelines and data processing systems.",
        "Business Intelligence (BI) Developer" => "Transform raw data into meaningful insights using BI tools and dashboards.",
        "Technical Program Manager (TPM)" => "Coordinate technical teams and manage large-scale engineering programs.",
        "API Integration Specialist" => "Integrate third-party APIs and services into scalable software systems.",
        "Platform Engineer" => "Build and maintain internal platforms to support scalable application development.",
        "Performance Engineer" => "Optimize application performance, scalability, and system efficiency.",
        "Security Operations (SOC) Analyst" => "Monitor, detect, and respond to cybersecurity threats in real time.",
        "Ethical Hacker / Penetration Tester" => "Identify and exploit security vulnerabilities to strengthen system defenses."
    ];

    $checkStmt = $conn->prepare("SELECT id FROM roles WHERE title = :title");
    $insertStmt = $conn->prepare("INSERT INTO roles (title, description) VALUES (:title, :desc)");
    $updateStmt = $conn->prepare("UPDATE roles SET description = :desc WHERE title = :title");

    foreach ($roles_data as $title => $desc) {
        $checkStmt->execute([':title' => $title]);
        if ($checkStmt->fetch()) {
            $updateStmt->execute([':title' => $title, ':desc' => $desc]);
            echo "Updated: $title<br>";
        } else {
            $insertStmt->execute([':title' => $title, ':desc' => $desc]);
            echo "Inserted: $title<br>";
        }
    }

    echo "ALL SYSTEMS GO. <a href='http://localhost:5173/sessions'>Go to Sessions</a>";

} catch (PDOException $e) {
    echo "CRITICAL ERROR: " . $e->getMessage();
}
?>
