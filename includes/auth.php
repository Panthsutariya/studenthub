<?php
/**
 * Practical 10 & 14: Authentication, RBAC, Session Timeout, and Audit Logging
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

// Configure secure session cookie parameters
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    session_start();
}

require_once __DIR__ . '/../db.php';

// Inactivity timeout: 15 minutes (900 seconds) as required by Practical 10
define('SESSION_TIMEOUT_SECONDS', 900);

if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT_SECONDS)) {
        // Session has expired
        $expiredUserId = $_SESSION['user_id'];
        $expiredUsername = $_SESSION['username'] ?? 'unknown';
        
        // Log timeout
        if (isset($conn) && $conn instanceof mysqli) {
            log_audit($conn, 'SESSION_TIMEOUT', "User {$expiredUsername} timed out due to 15 min inactivity.");
        }

        // Destroy session
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header('Location: /studenthub/login.php?error=Session timed out due to 15 minutes of inactivity. Please log in again.');
        exit;
    }
    // Update last activity timestamp
    $_SESSION['last_activity'] = time();
}

/**
 * Audit Logging Helper (Practical 14)
 */
function log_audit(mysqli $conn, string $action, string $details): void {
    try {
        $userId = $_SESSION['user_id'] ?? null;
        $username = $_SESSION['username'] ?? 'GUEST';
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

        $stmt = $conn->prepare("INSERT INTO audit_logs (user_id, username, action, details, ip_address) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $userId, $username, $action, $details, $ip);
        $stmt->execute();
        $stmt->close();
    } catch (Throwable $e) {
        error_log("Audit log failed: " . $e->getMessage());
    }
}

/**
 * Authentication Check Helpers
 */
function is_logged_in(): bool {
    return !empty($_SESSION['user_id']);
}

function is_admin(): bool {
    return is_logged_in() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function current_username(): string {
    return $_SESSION['username'] ?? 'Guest';
}

function current_user_role(): string {
    return $_SESSION['role'] ?? 'guest';
}

function require_login(string $redirect = 'login.php'): void {
    if (!is_logged_in()) {
        header("Location: /studenthub/{$redirect}?error=Please login to access this page.");
        exit;
    }
}

function require_admin(string $redirect = 'login.php'): void {
    require_login($redirect);
    if (!is_admin()) {
        http_response_code(403);
        echo '<!DOCTYPE html><html><head><title>Access Denied</title><link rel="stylesheet" href="/studenthub/css/style.css"></head><body>';
        echo '<div class="container my-5" style="max-width:600px; margin:4rem auto;"><div class="card p-4 alert-danger">';
        echo '<h2>403 - Forbidden: Admin Access Required</h2>';
        echo '<p>You do not have administrative privileges to access this area.</p>';
        echo '<a href="/studenthub/index.php" class="btn btn-primary mt-3">&larr; Return to Home</a>';
        echo '</div></div></body></html>';
        exit;
    }
}
