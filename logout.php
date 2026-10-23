<?php
/**
 * Practical 10: Secure Logout
 * Destroys session, clears session cookies, removes remember token, and logs audit
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

if (is_logged_in() && isset($conn) && $conn instanceof mysqli) {
    $userId = (int)$_SESSION['user_id'];
    $username = $_SESSION['username'] ?? 'unknown';

    // Clear remember token in DB
    try {
        $stmt = $conn->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    } catch (Throwable $e) {}

    // Audit log
    log_audit($conn, 'LOGOUT', "User {$username} logged out.");
}

// Clear remember-me cookie
if (isset($_COOKIE['studenthub_remember'])) {
    setcookie('studenthub_remember', '', time() - 3600, '/studenthub');
}

// Clear session
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

header('Location: login.php?success=' . urlencode('You have been logged out securely.'));
exit;
