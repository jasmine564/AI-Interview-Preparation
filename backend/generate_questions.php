<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$data = json_decode(file_get_contents('php://input'), true);
$jd = $data['jobDescription'] ?? '';
$role = $data['role'] ?? 'Custom';

$questions = [];

// --- Smart Detection Logic (Mocking AI) ---
$jd_lower = strtolower($jd);

if (strpos($jd_lower, 'react') !== false) {
    $questions = [
        ['id' => 1, 'text' => 'Can you explain the Virtual DOM in React and how it improves performance?'],
        ['id' => 2, 'text' => 'Describe a challenging bug you encountered in a React application and how you resolved it.'],
        ['id' => 3, 'text' => 'How do you handle state management in complex applications? Do you prefer Context API, Redux, or something else?'],
        ['id' => 4, 'text' => 'Explain the concept of Higher-Order Components and provide a use case.'],
        ['id' => 5, 'text' => 'Tell me about a time you had to optimize a slow-rendering component.']
    ];
} elseif (strpos($jd_lower, 'python') !== false || strpos($jd_lower, 'django') !== false) {
    $questions = [
        ['id' => 1, 'text' => 'What are Python decorators and how have you used them in your projects?'],
        ['id' => 2, 'text' => 'Explain the difference between deep copy and shallow copy in Python.'],
        ['id' => 3, 'text' => 'How do you optimize database queries in Django/Flask?'],
        ['id' => 4, 'text' => 'Describe a situation where you had to handle concurrent data processing in Python.'],
        ['id' => 5, 'text' => 'Tell me about a REST API you built. How did you handle authentication?']
    ];
} elseif (strpos($jd_lower, 'java') !== false || strpos($jd_lower, 'spring') !== false) {
    $questions = [
        ['id' => 1, 'text' => 'Explain the Spring Boot dependency injection mechanism.'],
        ['id' => 2, 'text' => 'What is the difference between an interface and an abstract class in Java? When would you use each?'],
        ['id' => 3, 'text' => 'How do you handle exceptions in a multi-threaded Java application?'],
        ['id' => 4, 'text' => 'Describe a time you refactored legacy Java code. What was your approach?'],
        ['id' => 5, 'text' => 'Can you explain the JVM memory model?']
    ];
} elseif (strpos($jd_lower, 'manager') !== false || strpos($jd_lower, 'lead') !== false) {
    $questions = [
        ['id' => 1, 'text' => 'Tell me about a time you had to manage a conflict between team members.'],
        ['id' => 2, 'text' => 'How do you prioritize tasks when everything seems urgent?'],
        ['id' => 3, 'text' => 'Describe your approach to mentoring junior developers.'],
        ['id' => 4, 'text' => 'Tell me about a project that failed. What did you learn from it?'],
        ['id' => 5, 'text' => 'How do you handle underperformance in your team?']
    ];
} else {
    // Generic / Fallback based on text length or just general behavioral
    $questions = [
        ['id' => 1, 'text' => 'Based on the job description you provided, tell me why you are the best fit for this role.'],
        ['id' => 2, 'text' => 'Describe a complex problem you solved in your previous role.'],
        ['id' => 3, 'text' => 'How do you handle tight deadlines and pressure?'],
        ['id' => 4, 'text' => 'Tell me about a time you specifically demonstrated the skills identified in this job description.'],
        ['id' => 5, 'text' => 'Where do you see yourself in 5 years relative to this career path?']
    ];
}

echo json_encode(['questions' => $questions]);
?>
