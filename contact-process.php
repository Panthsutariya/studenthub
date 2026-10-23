<?php
/**
 * Practical 7: PHP Form Processing with Server-Side Validation, Sanitization,
 * and dual CSV/JSON File Storage with file locking.
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php?error=Invalid request method.');
    exit;
}

// 1. Verify CSRF Token (Practical 7 Advanced Extension)
$csrfToken = $_POST['csrf_token'] ?? '';
if (!verify_csrf_token($csrfToken)) {
    header('Location: contact.php?error=Security token expired or invalid. Please try again.');
    exit;
}

// 2. Sanitize and Validate Inputs Server-Side
$name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
$category = trim(filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'General');
$message = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');

$errors = [];

if (empty($name) || strlen($name) < 2 || strlen($name) > 100) {
    $errors[] = 'Name must be between 2 and 100 characters.';
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'A valid email address is required.';
}
if (empty($message) || strlen($message) < 10 || strlen($message) > 5000) {
    $errors[] = 'Message must be between 10 and 5000 characters.';
}

if (!empty($errors)) {
    $errorMsg = urlencode(implode(' ', $errors));
    header("Location: contact.php?error={$errorMsg}");
    exit;
}

// 3. Prepare Record Data
$submissionId = uniqid('sub_', true);
$timestamp = date('Y-m-d H:i:s');
$clientIp = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

$record = [
    'id'        => $submissionId,
    'timestamp' => $timestamp,
    'name'      => $name,
    'email'     => $email,
    'category'  => $category,
    'message'   => $message,
    'ip'        => $clientIp
];

$storageDir = __DIR__ . '/data/submissions';
if (!is_dir($storageDir)) {
    mkdir($storageDir, 0755, true);
}

// 4. Store in JSON Format with flock
$jsonFile = $storageDir . '/contact-submissions.json';
$fpJson = @fopen($jsonFile, 'c+');
if ($fpJson && flock($fpJson, LOCK_EX)) {
    $content = stream_get_contents($fpJson);
    $data = [];
    if (!empty($content)) {
        $decoded = json_decode($content, true);
        if (is_array($decoded)) {
            $data = $decoded;
        }
    }
    $data[] = $record;
    ftruncate($fpJson, 0);
    rewind($fpJson);
    fwrite($fpJson, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    fflush($fpJson);
    flock($fpJson, LOCK_UN);
    fclose($fpJson);
}

// 5. Store in CSV Format with flock
$csvFile = $storageDir . '/contact-submissions.csv';
$fpCsv = @fopen($csvFile, 'a+');
if ($fpCsv && flock($fpCsv, LOCK_EX)) {
    if (filesize($csvFile) === 0) {
        // Write CSV header
        fputcsv($fpCsv, ['ID', 'Timestamp', 'Name', 'Email', 'Category', 'Message', 'IP']);
    }
    fputcsv($fpCsv, [
        $record['id'],
        $record['timestamp'],
        $record['name'],
        $record['email'],
        $record['category'],
        str_replace(["\r", "\n"], ' ', $record['message']),
        $record['ip']
    ]);
    fflush($fpCsv);
    flock($fpCsv, LOCK_UN);
    fclose($fpCsv);
}

// Success redirection
header('Location: contact.php?success=Thank you! Your message has been safely recorded in both JSON and CSV files.');
exit;
