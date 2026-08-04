<?php
// get_roles.php
include_once 'cors.php';
define('SUPPRESS_DB_ERROR', true); // Enable offline mode if DB is dead
include_once 'db.php';

session_start();

// Fallback Data Definition (moved up for global scope access)
$fallback_roles = [
    ["id" => 1, "title" => "Frontend Developer", "description" => "Build responsive and interactive user interfaces using modern web technologies.", "icon" => "FaCode", "question_count" => 20],
    ["id" => 2, "title" => "Backend Developer", "description" => "Design server-side logic, APIs, and databases for scalable applications.", "icon" => "FaCode", "question_count" => 20],
    ["id" => 3, "title" => "AI/ML Engineer", "description" => "Develop intelligent systems and machine learning models.", "icon" => "FaRobot", "question_count" => 20],
    ["id" => 4, "title" => "Prompt Engineer", "description" => "Design and optimize prompts to improve AI model accuracy and response quality.", "icon" => "FaRobot", "question_count" => 20],
    ["id" => 5, "title" => "MLOps Engineer", "description" => "Manage, deploy, and monitor machine learning models in production environments.", "icon" => "FaRobot", "question_count" => 20],
    ["id" => 6, "title" => "Blockchain Developer", "description" => "Build decentralized applications using blockchain technologies and protocols.", "icon" => "FaNetworkWired", "question_count" => 20],
    ["id" => 7, "title" => "Web3 / Smart Contract Developer", "description" => "Develop and deploy secure smart contracts and Web3-based applications.", "icon" => "FaNetworkWired", "question_count" => 20],
    ["id" => 8, "title" => "Game Developer", "description" => "Design and develop interactive games using modern game engines and frameworks.", "icon" => "FaGamepad", "question_count" => 20],
    ["id" => 9, "title" => "AR / VR Developer", "description" => "Create immersive augmented and virtual reality experiences across platforms.", "icon" => "FaGamepad", "question_count" => 20],
    ["id" => 10, "title" => "Embedded Systems Engineer", "description" => "Develop software for hardware-based and real-time embedded systems.", "icon" => "FaCogs", "question_count" => 20],
    ["id" => 11, "title" => "Robotics Engineer", "description" => "Design intelligent robotic systems combining hardware, software, and AI.", "icon" => "FaRobot", "question_count" => 20],
    ["id" => 12, "title" => "Data Engineer", "description" => "Build and maintain scalable data pipelines and data processing systems.", "icon" => "FaDatabase", "question_count" => 20],
    ["id" => 13, "title" => "Business Intelligence (BI) Developer", "description" => "Transform raw data into meaningful insights using BI tools and dashboards.", "icon" => "FaChartLine", "question_count" => 20],
    ["id" => 14, "title" => "Technical Program Manager (TPM)", "description" => "Coordinate technical teams and manage large-scale engineering programs.", "icon" => "FaProjectDiagram", "question_count" => 20],
    ["id" => 15, "title" => "API Integration Specialist", "description" => "Integrate third-party APIs and services into scalable software systems.", "icon" => "FaNetworkWired", "question_count" => 20],
    ["id" => 16, "title" => "Platform Engineer", "description" => "Build and maintain internal platforms to support scalable application development.", "icon" => "FaCogs", "question_count" => 20],
    ["id" => 17, "title" => "Performance Engineer", "description" => "Optimize application performance, scalability, and system efficiency.", "icon" => "FaCogs", "question_count" => 20],
    ["id" => 18, "title" => "Security Operations (SOC) Analyst", "description" => "Monitor, detect, and respond to cybersecurity threats in real time.", "icon" => "FaShieldAlt", "question_count" => 20],
    ["id" => 19, "title" => "Ethical Hacker / Penetration Tester", "description" => "Identify and exploit security vulnerabilities to strengthen system defenses.", "icon" => "FaShieldAlt", "question_count" => 20]
];

// Check authentication
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["message" => "Unauthorized"]);
    exit();
}

// CHECK IF DB CONNECTION FAILED
if (!isset($conn) || $conn === null) {
    echo json_encode($fallback_roles);
    exit();
}

try {
    $stmt = $conn->query("SELECT * FROM roles ORDER BY title ASC");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // SAFEGUARD: Ensure unique IDs and valid structure
    $seenIds = [];
    $safeRoles = [];

    foreach ($result as $role) {
        // Skip if ID missing or duplicate
        if (!isset($role['id']) || in_array($role['id'], $seenIds)) continue;
        
        $safeRole = [
            "id" => (int)$role['id'],
            "title" => isset($role['title']) ? (string)$role['title'] : "Unknown Role",
            "description" => (!empty($role['description'])) ? (string)$role['description'] : "Prepare for your interview with AI.",
            "icon" => isset($role['icon']) ? (string)$role['icon'] : "FaCode",
            "question_count" => isset($role['question_count']) ? (int)$role['question_count'] : 20
        ];

        $seenIds[] = $role['id'];
        $safeRoles[] = $safeRole;
    }
    
    echo json_encode($safeRoles);

} catch(PDOException $e) {
    // FALLBACK (Redundant but safe)
    echo json_encode($fallback_roles); 
}
?>
