<?php
// backend/restore_roles.php
include 'db.php';

try {
    // Ensure table exists
    $conn->exec("CREATE TABLE IF NOT EXISTS roles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL UNIQUE,
        description TEXT,
        icon VARCHAR(50) DEFAULT 'FaCode',
        question_count INT DEFAULT 20
    )");

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

    echo "Starting restoration...<br>";

    foreach ($roles_data as $title => $desc) {
        // UPSERT (Insert or Update)
        $stmt = $conn->prepare("INSERT INTO roles (title, description, icon, question_count) values (:title, :desc, 'FaCode', 20) ON DUPLICATE KEY UPDATE description = :desc2");
        $stmt->execute([':title' => $title, ':desc' => $desc, ':desc2' => $desc]);
        echo "Restored: $title<br>";
    }

    echo "Done. Roles populated.";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
