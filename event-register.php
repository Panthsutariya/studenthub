<?php
/**
 * Student Event Registration Handler
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: events.php?error=Invalid request.');
    exit;
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    header('Location: events.php?error=Session expired. Please try again.');
    exit;
}

$eventId = (int)($_POST['event_id'] ?? 0);
$userId = (int)$_SESSION['user_id'];

if ($eventId <= 0) {
    header('Location: events.php?error=Invalid event selected.');
    exit;
}

// Find student ID linked with this user
$stmtSt = $conn->prepare("SELECT id, full_name FROM students WHERE user_id = ? LIMIT 1");
$stmtSt->bind_param("i", $userId);
$stmtSt->execute();
$student = $stmtSt->get_result()->fetch_assoc();
$stmtSt->close();

if (!$student) {
    header('Location: events.php?error=No linked student profile found for your account. Please update profile.');
    exit;
}

$studentId = (int)$student['id'];

// Check if event is open
$stmtEv = $conn->prepare("SELECT title, status FROM events WHERE id = ? LIMIT 1");
$stmtEv->bind_param("i", $eventId);
$stmtEv->execute();
$event = $stmtEv->get_result()->fetch_assoc();
$stmtEv->close();

if (!$event || $event['status'] !== 'open') {
    header('Location: events.php?error=This event is closed or unavailable for registration.');
    exit;
}

// Check if already registered
$checkStmt = $conn->prepare("SELECT id FROM registrations WHERE student_id = ? AND event_id = ? LIMIT 1");
$checkStmt->bind_param("ii", $studentId, $eventId);
$checkStmt->execute();
if ($checkStmt->get_result()->num_rows > 0) {
    $checkStmt->close();
    header('Location: events.php?error=You are already registered for this event.');
    exit;
}
$checkStmt->close();

// Register student
try {
    $regStmt = $conn->prepare("INSERT INTO registrations (student_id, event_id, status) VALUES (?, ?, 'registered')");
    $regStmt->bind_param("ii", $studentId, $eventId);
    $regStmt->execute();
    $regStmt->close();

    log_audit($conn, 'EVENT_REGISTER', "Student {$student['full_name']} registered for event '{$event['title']}'");

    header('Location: events.php?success=' . urlencode("Successfully registered for '{$event['title']}'!"));
} catch (mysqli_sql_exception $e) {
    header('Location: events.php?error=' . urlencode('Registration failed: ' . $e->getMessage()));
}
exit;
