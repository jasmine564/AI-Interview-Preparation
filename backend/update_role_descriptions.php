<?php
include_once 'db.php';

$descriptions = [
    "Prompt Engineer" => "Master the art of crafting inputs to guide AI behavior and output quality.",
    "MLOps Engineer" => "Bridge the gap between ML model development and scalable production deployment.",
    "Blockchain Developer" => "Architect decentralized solutions and secure digital ledgers.",
    "Web3 / Smart Contract Developer" => "Build the next generation of the decentralized web and automated contracts.",
    "Game Developer" => "Design and program immersive interactive experiences and game mechanics.",
    "AR / VR Developer" => "Create cutting-edge augmented and virtual reality environments.",
    "Embedded Systems Engineer" => "Develop low-level code for hardware constraints and real-time performance.",
    "Robotics Engineer" => "Program intelligent machines for perception, navigation, and control.",
    "Data Engineer" => "Construct robust pipelines to collect, store, and analyze massive datasets.",
    "Business Intelligence (BI) Developer" => "Turn complex data into clear, actionable visual insights for decision making.",
    "Technical Program Manager (TPM)" => "Drive complex technical projects from concept to delivery across teams.",
    "API Integration Specialist" => "Connect disparate systems with secure, efficient, and scalable APIs.",
    "Security Operations (SOC) Analyst" => "Monitor, detect, and respond to cyber threats in real-time.",
    "Ethical Hacker / Penetration Tester" => "Simulate cyberattacks to identify and fix security vulnerabilities."
];

try {
    $stmt = $conn->prepare("UPDATE roles SET description = :desc WHERE title = :title");
    
    foreach ($descriptions as $title => $desc) {
        $stmt->bindParam(':desc', $desc);
        $stmt->bindParam(':title', $title);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            echo "Updated: " . $title . "<br>";
        } else {
            echo "No Change (or not found): " . $title . "<br>";
        }
    }
    echo "Description updates complete.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
