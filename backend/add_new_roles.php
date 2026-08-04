<?php
include_once 'db.php';

$roles = [
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

try {
    echo "Updating role descriptions...<br>";
    
    // UPSERT Query: Inserts if new, Updates description if title exists
    $sql = "INSERT INTO roles (title, description, icon, question_count) VALUES (:title, :description, 'FaCode', 20) 
            ON DUPLICATE KEY UPDATE description = VALUES(description)";
    
    $stmt = $conn->prepare($sql);

    foreach ($roles as $title => $description) {
        $stmt->execute([
            ':title' => $title,
            ':description' => $description
        ]);
        echo "Updated/Verified: " . $title . "<br>";
    }
    
    echo "<br><b>SUCCESS: All role descriptions have been updated.</b>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
