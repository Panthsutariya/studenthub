<?php
/**
 * Practical 8: Database Connection using MySQLi
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

$dbHost = 'localhost';
$dbName = 'studenthub';
$dbUser = 'root';
$dbPassword = '';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    error_log('MySQLi Connection Error: ' . $e->getMessage());
    // In production/API we return JSON or clean message
    if (defined('API_REQUEST') && API_REQUEST === true) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]);
        exit;
    }
}
