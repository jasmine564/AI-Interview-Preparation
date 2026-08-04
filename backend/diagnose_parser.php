<?php
// diagnose_parser.php
header('Content-Type: text/plain');

echo "--- PHP Environment Diagnosis ---\n";
echo "PHP Version: " . phpversion() . "\n";

// Check ZIP
if (class_exists('ZipArchive')) {
    echo "[OK] ZipArchive class exists.\n";
} else {
    echo "[FAIL] ZipArchive class NOT found. DOCX parsing will crash.\n";
}

// Check DOM
if (class_exists('DOMDocument')) {
    echo "[OK] DOMDocument class exists.\n";
} else {
    echo "[FAIL] DOMDocument class NOT found. DOCX parsing will crash.\n";
}

// Check ZLIB (for PDF gzuncompress)
if (function_exists('gzuncompress')) {
    echo "[OK] gzuncompress function exists.\n";
} else {
    echo "[FAIL] gzuncompress function NOT found. PDF parsing will fail.\n";
}

// Check Upload Directory Permissions
$uploadDir = __DIR__ . '/uploads';
if (is_dir($uploadDir)) {
    if (is_writable($uploadDir)) {
        echo "[OK] Upload directory is writable.\n";
    } else {
        echo "[FAIL] Upload directory exists but is NOT writable.\n";
    }
} else {
    echo "[INFO] Upload directory does not exist (will try to create).\n";
}

echo "--- End Diagnosis ---\n";
?>
