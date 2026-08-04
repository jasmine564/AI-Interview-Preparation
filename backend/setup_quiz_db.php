<?php
require_once 'db.php';

try {
    // 1. Create Quizzes Table
    $conn->exec("CREATE TABLE IF NOT EXISTS quizzes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL,
        description TEXT,
        difficulty ENUM('Easy', 'Medium', 'Hard') DEFAULT 'Medium',
        category VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Table 'quizzes' ready.\n";

    // 2. Create Questions Table
    $conn->exec("CREATE TABLE IF NOT EXISTS quiz_questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        quiz_id INT NOT NULL,
        question_text TEXT NOT NULL,
        options JSON NOT NULL, -- Stores array of strings like ['A', 'B', 'C', 'D']
        correct_index INT NOT NULL, -- 0 to 3
        explanation TEXT,
        FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
    )");
    echo "Table 'quiz_questions' ready.\n";

    // 3. Create Results Table
    $conn->exec("CREATE TABLE IF NOT EXISTS user_quiz_results (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        quiz_id INT NOT NULL,
        score INT NOT NULL,
        total_questions INT NOT NULL,
        completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
        -- user_id FK omitted for simplicity/flexibility if users table varies, but enforced in code
    )");
    echo "Table 'user_quiz_results' ready.\n";

    // 4. Seed Data function
    function seedQuiz($conn, $title, $diff, $cat, $desc, $questions) {
        // Check if quiz exists
        $stmt = $conn->prepare("SELECT id FROM quizzes WHERE title = ?");
        $stmt->execute([$title]);
        $quizId = $stmt->fetchColumn();

        if (!$quizId) {
            $stmt = $conn->prepare("INSERT INTO quizzes (title, difficulty, category, description) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $diff, $cat, $desc]);
            $quizId = $conn->lastInsertId();
            echo "Created Quiz: $title (ID: $quizId)\n";
        } else {
            echo "Quiz '$title' already exists (ID: $quizId). Skipping creation.\n";
            // Optional: Delete existing questions to re-seed? Let's just skip for now to avoid duplications on re-run
            return;
        }

        foreach ($questions as $q) {
            $stmt = $conn->prepare("INSERT INTO quiz_questions (quiz_id, question_text, options, correct_index, explanation) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $quizId,
                $q['text'],
                json_encode($q['options']),
                $q['correct'],
                $q['explain']
            ]);
        }
        echo " -> Added " . count($questions) . " questions.\n";
    }

    // --- SEED CONTENT ---

    // React Quiz
    seedQuiz($conn, "React Basics", "Easy", "Frontend", "Test your knowledge of React fundamentals.", [
        [
            "text" => "Which hook is used to manage state in a functional component?",
            "options" => ["useEffect", "useState", "useContext", "useReducer"],
            "correct" => 1,
            "explain" => "useState is the primary hook for adding state variables to functional components."
        ],
        [
            "text" => "What is the virtual DOM?",
            "options" => ["A direct copy of the browser DOM", "A lightweight representation of the real DOM", "A database for React", "A browser extension"],
            "correct" => 1,
            "explain" => "The virtual DOM is a lightweight copy of the real DOM that React uses to optimize updates."
        ],
        [
            "text" => "How do you pass data to a child component?",
            "options" => ["State", "Props", "Context", "Redux"],
            "correct" => 1,
            "explain" => "Props (properties) are the standard way to pass data from parent to child components."
        ],
         [
            "text" => "What is the correct syntax to import React?",
            "options" => ["import React from 'react'", "include React from 'react'", "require React", "import * as React"],
            "correct" => 0,
            "explain" => "The standard ES6 import syntax is `import React from 'react'`."
        ],
        [
            "text" => "Which hook performs side effects?",
            "options" => ["useState", "useMemo", "useEffect", "useCallback"],
            "correct" => 2,
            "explain" => "useEffect is designed to handle side effects like data fetching, subscriptions, or DOM manipulation."
        ]
    ]);

    // JavaScript Quiz
    seedQuiz($conn, "JavaScript Essentials", "Medium", "Frontend", "Core JavaScript concepts including closures and async.", [
        [
            "text" => "What is the output of `console.log(typeof null)`?",
            "options" => ["null", "undefined", "object", "number"],
            "correct" => 2,
            "explain" => "This is a known bug in JavaScript; `typeof null` returns 'object'."
        ],
        [
            "text" => "Which keyword creates a block-scoped variable?",
            "options" => ["var", "let", "global", "def"],
            "correct" => 1,
            "explain" => "`let` and `const` are block-scoped, whereas `var` is function-scoped."
        ],
        [
            "text" => "What does `NaN` stand for?",
            "options" => ["Not a Null", "Not a Number", "No Action Needed", "New And Null"],
            "correct" => 1,
            "explain" => "NaN stands for Not-a-Number, representing a value that is not a legal number."
        ],
        [
            "text" => "How do you create a Promise?",
            "options" => ["new Promise((resolve, reject) => ...)", "Promise.create()", "function Promise() {}", "await new Promise()"],
            "correct" => 0,
            "explain" => "A Promise is created using `new Promise(executor)` where executor takes resolve and reject functions."
        ],
        [
            "text" => "What acts as the context for `this` in an arrow function?",
            "options" => ["The function itself", "The global object", "The enclosing lexical scope", "The caller object"],
            "correct" => 2,
            "explain" => "Arrow functions do not have their own `this`; they inherit it from the enclosing text (lexical scope)."
        ]
    ]);

    // SQL Quiz
    seedQuiz($conn, "SQL Fundamentals", "Medium", "Backend", "Database querying basics.", [
        [
            "text" => "Which statement is used to fetch data?",
            "options" => ["GET", "FETCH", "SELECT", "RETRIEVE"],
            "correct" => 2,
            "explain" => "SELECT is the standard SQL command to query data from a database."
        ],
        [
            "text" => "Which clause filters records?",
            "options" => ["LIMIT", "WHERE", "GROUP BY", "ORDER BY"],
            "correct" => 1,
            "explain" => "The WHERE clause is used to filter records that satisfy a specified condition."
        ],
         [
            "text" => "What does SQL stand for?",
            "options" => ["Structured Question Language", "Structured Query Language", "Simple Query List", "Standard Query Logic"],
            "correct" => 1,
            "explain" => "SQL stands for Structured Query Language."
        ],
        [
            "text" => "Which command removes a table?",
            "options" => ["DELETE TABLE", "REMOVE TABLE", "DROP TABLE", "CLEAR TABLE"],
            "correct" => 2,
            "explain" => "DROP TABLE completely removes the table structure and its data."
        ],
        [
            "text" => "Which key creates a relationship between tables?",
            "options" => ["Primary Key", "Foreign Key", "Unique Key", "Super Key"],
            "correct" => 1,
            "explain" => "A Foreign Key is a field that links to the Primary Key of another table, establishing a relationship."
        ]
    ]);

    // React, JS, SQL quizzes (Previous content maintained above)

    // 1. Emerging Technologies Fundamentals
    seedQuiz($conn, "Emerging Technologies", "Medium", "Tech Trends", "Overview of AI, Blockchain, IoT, and Cloud.", [
        [
            "text" => "What does 'IoT' stand for?",
            "options" => ["Internet of Technologies", "Input of Tools", "Internet of Things", "Internal of Things"],
            "correct" => 2,
            "explain" => "IoT stands for Internet of Things, referring to the network of physical objects embedded with sensors and software."
        ],
        [
            "text" => "Which technology is the foundation of Bitcoin?",
            "options" => ["Cloud Computing", "Blockchain", "Machine Learning", "Big Data"],
            "correct" => 1,
            "explain" => "Blockchain is the decentralized ledger technology that underlies cryptocurrencies like Bitcoin."
        ],
        [
            "text" => "What is the primary goal of Machine Learning?",
            "options" => ["To store data", "To allow computers to learn from data without explicit programming", "To speed up internet connection", "To encrypt passwords"],
            "correct" => 1,
            "explain" => "ML focuses on developing systems that can learn from and make decisions based on data."
        ],
        [
            "text" => "Which service model does AWS EC2 fall under?",
            "options" => ["SaaS (Software as a Service)", "PaaS (Platform as a Service)", "IaaS (Infrastructure as a Service)", "FaaS (Function as a Service)"],
            "correct" => 2,
            "explain" => "EC2 provides virtualized computing resources, which is a classic example of IaaS."
        ],
        [
            "text" => "What is 'Edge Computing'?",
            "options" => ["Computing at the data center", "Computing on the user's browser", "Computing closer to the source of data generation", "Quantum computing"],
            "correct" => 2,
            "explain" => "Edge computing involves processing data near the edge of the network, where the data is generated, rather than a centralized cloud."
        ]
    ]);

    // 2. CS Fundamentals (Interview Basics)
    seedQuiz($conn, "CS Fundamentals", "Medium", "Computer Science", "Core concepts: Data Structures, Algorithms, and Big O.", [
        [
            "text" => "What is the time complexity of a binary search?",
            "options" => ["O(n)", "O(n^2)", "O(log n)", "O(1)"],
            "correct" => 2,
            "explain" => "Binary search halves the search space with each step, resulting in logarithmic time complexity O(log n)."
        ],
        [
            "text" => "Which data structure follows LIFO (Last In, First Out)?",
            "options" => ["Queue", "Stack", "Array", "Linked List"],
            "correct" => 1,
            "explain" => "A Stack follows the LIFO principle, where the last element fetched is the first one to be removed."
        ],
        [
            "text" => "What is a 'deadlock' in operating systems?",
            "options" => ["When a process crashes", "When two processes wait for each other to release resources", "When memory is full", "When CPU is overheated"],
            "correct" => 1,
            "explain" => "Deadlock occurs when two or more processes are blocked forever, each waiting on the other."
        ],
        [
            "text" => "Which sorting algorithm is generally the fastest on average?",
            "options" => ["Bubble Sort", "Quick Sort", "Insertion Sort", "Selection Sort"],
            "correct" => 1,
            "explain" => "Quick Sort has an average case of O(n log n) and typically outperforms others like Bubble or Insertion sort."
        ],
        [
            "text" => "What is typical RAM access speed compared to Disk?",
            "options" => ["Slower", "Much Faster", "The same", "Slightly faster"],
            "correct" => 1,
            "explain" => "RAM (Random Access Memory) is orders of magnitude faster than disk storage (HDD/SSD)."
        ]
    ]);

    // 3. Backend & API Basics
    seedQuiz($conn, "Backend & API Basics", "Medium", "Backend", "HTTP, REST, JSON, and server-side concepts.", [
        [
            "text" => "Which HTTP method is typically used to update an existing resource?",
            "options" => ["GET", "POST", "PUT", "DELETE"],
            "correct" => 2,
            "explain" => "PUT (or PATCH) is used to update resources. POST is typically for creating new ones."
        ],
        [
            "text" => "What does 'REST' stand for?",
            "options" => ["Remote Execution State Transfer", "Representational State Transfer", "Real State Transmission", "Reliable Server Technology"],
            "correct" => 1,
            "explain" => "REST stands for Representational State Transfer, an architectural style for web services."
        ],
        [
            "text" => "Which status code indicates 'Not Found'?",
            "options" => ["200", "500", "403", "404"],
            "correct" => 3,
            "explain" => "404 is the standard HTTP status code for 'Not Found'."
        ],
        [
            "text" => "What format is most commonly used for API responses today?",
            "options" => ["XML", "JSON", "HTML", "CSV"],
            "correct" => 1,
            "explain" => "JSON (JavaScript Object Notation) is the standard format for modern web APIs."
        ],
        [
            "text" => "What is the purpose of an API Key?",
            "options" => ["To encrypt data", "To authenticate and throttle requests", "To speed up the server", "To compress images"],
            "correct" => 1,
            "explain" => "API Keys are used to identify the calling client, typically for authentication and rate limiting."
        ]
    ]);

    // 4. HTML & CSS Fundamentals
    seedQuiz($conn, "HTML & CSS Fundamentals", "Easy", "Frontend", "Building blocks of the web: Structure and Style.", [
        [
            "text" => "Which HTML tag is used for the largest heading?",
            "options" => ["<h6>", "<head>", "<h1>", "<heading>"],
            "correct" => 2,
            "explain" => "<h1> defines the most important (and usually largest) heading."
        ],
        [
            "text" => "What does CSS stand for?",
            "options" => ["Computer Style Sheets", "Cascading Style Sheets", "Creative Style System", "Colorful Style Sheets"],
            "correct" => 1,
            "explain" => "CSS stands for Cascading Style Sheets."
        ],
        [
            "text" => "Which CSS property changes the text color?",
            "options" => ["text-color", "color", "font-color", "text-style"],
            "correct" => 1,
            "explain" => "The 'color' property sets the color of the text."
        ],
        [
            "text" => "In the Box Model, what wraps effectively around the content (inside the border)?",
            "options" => ["Margin", "Padding", "Outline", "Background"],
            "correct" => 1,
            "explain" => "Padding is the space between the content and the border."
        ],
        [
            "text" => "Which Flexbox property aligns items vertically (in a row layout)?",
            "options" => ["justify-content", "align-items", "flex-direction", "display"],
            "correct" => 1,
            "explain" => "align-items controls alignment on the cross axis (vertical for row)."
        ]
    ]);

    // 5. Systems, Security & Infrastructure
    seedQuiz($conn, "Systems & Security", "Hard", "DevOps/Security", "Networks, servers, and security protocols.", [
        [
            "text" => "What does DNS stand for?",
            "options" => ["Data Network Service", "Domain Name System", "Digital Name Server", "Direct Network Surface"],
            "correct" => 1,
            "explain" => "DNS (Domain Name System) translates human-readable domain names into IP addresses."
        ],
        [
            "text" => "Which protocol ensures secure communication over the web?",
            "options" => ["HTTP", "FTP", "HTTPS", "SMTP"],
            "correct" => 2,
            "explain" => "HTTPS (Hypertext Transfer Protocol Secure) uses encryption (TLS/SSL) for secure communication."
        ],
        [
            "text" => "What is a Denial of Service (DoS) attack?",
            "options" => ["Stealing passwords", "Flooding a server to make it unavailable", "Injecting malicious SQL", "Phishing emails"],
            "correct" => 1,
            "explain" => "A DoS attack aims to shut down a machine or network, making it inaccessible to its intended users."
        ],
        [
            "text" => "What is the purpose of a Load Balancer?",
            "options" => ["To store data", "To distribute network traffic across multiple servers", "To encrypt passwords", "To run background tasks"],
            "correct" => 1,
            "explain" => "Load balancers distribute incoming network traffic across a group of servers to ensure no single server bears too much load."
        ],
        [
            "text" => "What is 'hashing' used for in password security?",
            "options" => ["Making passwords readable", "Compressing passwords", "Transforming passwords into a fixed-size string", "Sending passwords via email"],
            "correct" => 2,
            "explain" => "Hashing converts a password into a fixed-string of characters, which is secure because it cannot be easily reversed."
        ]
    ]);

    // 6. Modern Application & Automation
    seedQuiz($conn, "Modern App & Automation", "Medium", "DevOps", "CI/CD, Docker, and modern workflows.", [
        [
            "text" => "What does CI/CD stand for?",
            "options" => ["Computer Interface / Computer Design", "Continuous Integration / Continuous Deployment", "Code Inspection / Code Delivery", "Cloud Integration / Cloud Data"],
            "correct" => 1,
            "explain" => "CI/CD stands for Continuous Integration and Continuous Deployment (or Delivery)."
        ],
        [
            "text" => "What is Docker primarily used for?",
            "options" => ["Virtual Reality", "Containerization", "Database Management", "UI Design"],
            "correct" => 1,
            "explain" => "Docker is a platform for developing, shipping, and running applications in containers."
        ],
        [
            "text" => "What is a 'Microservices' architecture?",
            "options" => ["One large monolithic application", "A collection of loosely coupled services", "Using very small servers", "Only using mobile apps"],
            "correct" => 1,
            "explain" => "Microservices structure an application as a collection of services that are highly maintainable and testable."
        ],
        [
            "text" => "In Git, which command saves changes to the local repository?",
            "options" => ["git save", "git push", "git commit", "git add"],
            "correct" => 2,
            "explain" => "git commit saves your staged changes to the local repository history."
        ],
        [
            "text" => "What is 'Infrastructure as Code' (IaC)?",
            "options" => ["Writing code exclusively on servers", "Managing infrastructure through code files rather than manual configuration", "Building physical servers", "Using complex variable names"],
            "correct" => 1,
            "explain" => "IaC manages and provision computer data centers through machine-readable definition files."
        ]
    ]);

    echo "\nDatabase setup and seeding completed successfully!\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
