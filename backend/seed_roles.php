<?php
// seed_roles.php
include_once 'db.php';

echo "Setting up Roles table...\n";

try {
    // 1. Create Roles Table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS roles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL UNIQUE,
        description VARCHAR(255) NOT NULL,
        icon VARCHAR(50) DEFAULT 'briefcase',
        question_count INT DEFAULT 10,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $conn->exec($sql);
    echo "Table 'roles' checked/created successfully.\n";

    // 2. Define Roles List
    $roles_data = [
        "Frontend Developer" => "Build responsive and interactive user interfaces using modern web technologies.",
        "Backend Developer" => "Design server-side logic, APIs, and databases for scalable applications.",
        "AI/ML Engineer" => "Develop intelligent systems and machine learning models.",
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

    // 3. Prepare Statement
    $insertSql = "INSERT INTO roles (title, description, icon, question_count) VALUES (:title, :description, 'FaCode', 20) ON DUPLICATE KEY UPDATE description = VALUES(description)";
    $insertStmt = $conn->prepare($insertSql);

    // 4. Insert/Update Roles
    foreach ($roles_data as $title => $desc) {
        $insertStmt->execute([
            ':title' => $title,
            ':description' => $desc
        ]);
    }
    echo "Inserted/Updated " . count($roles_data) . " roles.\n";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
