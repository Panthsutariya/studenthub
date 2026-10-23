<?php
/**
 * Practical 11: Student Delete (with Soft Delete Extension)
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_admin();
require_once __DIR__ . '/includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: students.php?error=Invalid delete request method.');
    exit;
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    header('Location: students.php?error=Security session token expired.');
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$mode = $_POST['mode'] ?? 'soft'; // 'soft' or 'permanent'

if ($id <= 0) {
    header('Location: students.php?error=Invalid student record ID.');
    exit;
}

try {
    // Get student info for audit
    $stmt = $conn->prepare("SELECT full_name, student_number FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $st = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$st) {
        header('Location: students.php?error=Student record not found.');
        exit;
    }

    if ($mode === 'permanent') {
        $delStmt = $conn->prepare("DELETE FROM students WHERE id = ?");
        $delStmt->bind_param("i", $id);
        $delStmt->execute();
        $delStmt->close();
        log_audit($conn, 'DELETE_STUDENT_PERMANENT', "Admin permanently deleted student ID {$id} ({$st['student_number']})");
        header('Location: students.php?success=' . urlencode("Student {$st['full_name']} permanently deleted."));
    } else {
        // Soft delete: status set to 'inactive'
        $softStmt = $conn->prepare("UPDATE students SET status = 'inactive' WHERE id = ?");
        $softStmt->bind_param("i", $id);
        $softStmt->execute();
        $softStmt->close();
        log_audit($conn, 'DELETE_STUDENT_SOFT', "Admin soft-deleted (deactivated) student ID {$id} ({$st['student_number']})");
        header('Location: students.php?success=' . urlencode("Student {$st['full_name']} marked inactive (soft deleted)."));
    }
} catch (mysqli_sql_exception $e) {
    header('Location: students.php?error=' . urlencode('Delete operation failed: ' . $e->getMessage()));
}
exit;
