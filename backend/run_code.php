<?php
require_once 'cors.php';
require_once 'load_env.php';
require_once 'db.php';

ini_set('display_errors', 0);
error_reporting(E_ALL);

/* ---------------- DEBUG ---------------- */
function logDebug($msg) {
    file_put_contents(
        'debug_run_code.log',
        date('[Y-m-d H:i:s] ') . $msg . PHP_EOL,
        FILE_APPEND
    );
}

/* -------- PREFLIGHT -------- */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

/* ---------- LOCAL EXECUTION (PY / JS) ---------- */
function runLocal($language, $code, $input) {
    logDebug("Starting local execution for: $language");

    $map = [
        'python' => ['cmd' => 'python', 'ext' => '.py'], // Ensure 'python' is in PATH or use absolute path
        'javascript' => ['cmd' => 'node', 'ext' => '.js']
    ];

    if (!isset($map[$language])) {
        logDebug("Unsupported local language: $language");
        return ['stderr' => 'Unsupported local language'];
    }

    $tmpFile = sys_get_temp_dir() . '/code_' . uniqid() . $map[$language]['ext'];
    logDebug("Writing code to temp file: $tmpFile");
    
    if (file_put_contents($tmpFile, $code) === false) {
         logDebug("Failed to write code to temp file");
         return ['stderr' => 'Failed to write code to temp file'];
    }

    $desc = [
        0 => ['pipe', 'r'], // stdin
        1 => ['pipe', 'w'], // stdout
        2 => ['pipe', 'w']  // stderr
    ];

    $cmd = $map[$language]['cmd'] . ' ' . escapeshellarg($tmpFile);
    logDebug("Executing command: $cmd");

    $process = proc_open($cmd, $desc, $pipes);

    if (!is_resource($process)) {
        logDebug("Failed to start process");
        unlink($tmpFile);
        return ['stderr' => 'Failed to start execution process'];
    }

    // Write input to stdin
    if (!empty($input)) {
        fwrite($pipes[0], $input);
    }
    fclose($pipes[0]);

    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);

    fclose($pipes[1]);
    fclose($pipes[2]);

    $exitCode = proc_close($process);
    
    logDebug("Execution finished. Exit code: $exitCode");
    logDebug("STDOUT: " . substr($stdout, 0, 100) . (strlen($stdout) > 100 ? '...' : ''));
    logDebug("STDERR: " . $stderr);

    unlink($tmpFile);

    return [
        'stdout' => $stdout,
        'stderr' => $stderr,
        'compile_output' => ''
    ];
}

/* ---------- DOCKER EXECUTION (C / C++ / JAVA) ---------- */
function runDocker($language, $code, $input) {
    $tmpDir = sys_get_temp_dir() . '/docker_' . uniqid();
    mkdir($tmpDir);

    file_put_contents("$tmpDir/input.txt", $input);

    switch ($language) {
        case 'c':
            file_put_contents("$tmpDir/main.c", $code);
            $cmd = "gcc /app/main.c -o /app/a.out && /app/a.out";
            break;

        case 'cpp':
            file_put_contents("$tmpDir/main.cpp", $code);
            $cmd = "g++ /app/main.cpp -o /app/a.out && /app/a.out";
            break;

        case 'java':
            file_put_contents("$tmpDir/Main.java", $code);
            $cmd = "javac /app/Main.java && java -cp /app Main";
            break;

        default:
            return ['stderr' => 'Unsupported compiled language'];
    }

    $dockerCmd =
        "docker run --rm " .
        "-v \"$tmpDir:/app\" " .
        "code-runner bash -c \"$cmd < /app/input.txt\"";

    logDebug("Docker CMD: $dockerCmd");

    $output = shell_exec($dockerCmd . " 2>&1");

    array_map('unlink', glob("$tmpDir/*"));
    rmdir($tmpDir);

    return [
        'stdout' => $output,
        'stderr' => '',
        'compile_output' => ''
    ];
}

/* ================= MAIN ================= */
try {
    $raw = file_get_contents("php://input");
    logDebug("INPUT: $raw");

    $data = json_decode($raw);

    if (!isset($data->code, $data->language, $data->problem_id)) {
        http_response_code(400);
        echo json_encode(['message' => 'Missing required fields']);
        exit;
    }

    $language = $data->language;
    $code = $data->code;
    $problem_id = $data->problem_id;
    $mode = $data->mode ?? 'submit';

    /* ---- FETCH TEST CASE ---- */
    $stmt = $conn->prepare("SELECT test_cases FROM coding_problems WHERE id = ?");
    $stmt->execute([$problem_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $stdin = '';
    if ($row && $row['test_cases']) {
        $tc = json_decode($row['test_cases'], true);
        $stdin = $tc[0]['input'] ?? '';
    }

    if ($stdin === '') {
        echo json_encode(['message' => 'No input provided']);
        exit;
    }

    /* ---- EXECUTION ---- */
    if (in_array($language, ['c', 'cpp', 'java'])) {
        $result = runDocker($language, $code, $stdin);
    } else {
        $result = runLocal($language, $code, $stdin);
    }

    $stdout = $result['stdout'] ?? '';
    $stderr = $result['stderr'] ?? '';
    $compile_output = $result['compile_output'] ?? '';

    /* ---- STATUS ---- */
    if ($mode === 'run') {
        $status = 'Run Completed';
        $solved = false;
    } else {
        if ($stderr === '' && $compile_output === '') {
            $status = 'Solved';
            $solved = true;
        } else {
            $status = 'Error';
            $solved = false;
        }
    }

    /* ---- SAVE PROGRESS ---- */
    if ($solved && $mode === 'submit') {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            $stmt = $conn->prepare(
                "INSERT IGNORE INTO solved_problems (user_id, problem_id, solved_at)
                 VALUES (?, ?, NOW())"
            );
            $stmt->execute([$_SESSION['user_id'], $problem_id]);
        }
    }

    echo json_encode([
        'stdout' => $stdout,
        'stderr' => $stderr,
        'compile_output' => $compile_output,
        'status' => $status,
        'solved' => $solved,
        'mode' => $mode
    ]);

} catch (Exception $e) {
    logDebug("ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['message' => 'Internal Server Error']);
}
