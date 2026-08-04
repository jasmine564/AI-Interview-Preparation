<?php
include_once 'db.php';

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

try {
    // Prepare statements
    $checkStmt = $conn->prepare("SELECT id FROM roles WHERE title = :title");
    $updateStmt = $conn->prepare("UPDATE roles SET description = :desc WHERE title = :title");
    $insertStmt = $conn->prepare("INSERT INTO roles (title, description) VALUES (:title, :desc)");

    foreach ($roles_data as $title => $desc) {
        // Check if role exists
        $checkStmt->bindParam(':title', $title);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() > 0) {
            // Update
            $updateStmt->bindParam(':desc', $desc);
            $updateStmt->bindParam(':title', $title);
            $updateStmt->execute();
            echo "Updated: " . $title . "\n";
        } else {
            // Insert
            $insertStmt->bindParam(':title', $title);
            $insertStmt->bindParam(':desc', $desc);
            $insertStmt->execute();
            echo "Inserted: " . $title . "\n";
        }
    }
    echo "All roles processed successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
