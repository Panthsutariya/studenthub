<?php
/**
 * Helper to render authenticated views for automated headless screenshot capture
 */
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page = $_GET['page'] ?? '';
$role = $_GET['role'] ?? 'admin';

if ($role === 'admin') {
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'admin';
    $_SESSION['email'] = 'admin@charusat.edu.in';
    $_SESSION['role'] = 'admin';
    $_SESSION['last_activity'] = time();
} else {
    $_SESSION['user_id'] = 2;
    $_SESSION['username'] = 'aarav';
    $_SESSION['email'] = 'aarav.patel@charusat.edu.in';
    $_SESSION['role'] = 'student';
    $_SESSION['last_activity'] = time();
}

switch ($page) {
    case 'admin_dashboard':
        require __DIR__ . '/admin/dashboard.php';
        break;
    case 'audit_logs':
        require __DIR__ . '/admin/audit-logs.php';
        break;
    case 'users':
        require __DIR__ . '/admin/users.php';
        break;
    case 'students':
        require __DIR__ . '/students.php';
        break;
    case 'student_add':
        require __DIR__ . '/student-add.php';
        break;
    case 'events':
        require __DIR__ . '/events.php';
        break;
    case 'event_add':
        require __DIR__ . '/event-add.php';
        break;
    case 'events_ajax':
        require __DIR__ . '/events-ajax.php';
        break;
    case 'profile':
        require __DIR__ . '/profile.php';
        break;
    default:
        require __DIR__ . '/index.php';
        break;
}
