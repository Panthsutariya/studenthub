<?php
/**
 * Practical 10: Secure Login with Sessions, Role-Based Access, Timeout & Remember-Me
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/auth.php';

// If already logged in, redirect based on role
if (is_logged_in()) {
    if (is_admin()) {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: index.php');
    }
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($csrfToken)) {
        $error = 'Security session expired. Please refresh and try again.';
    } else {
        $loginInput = trim($_POST['username_or_email'] ?? '');
        $password = $_POST['password'] ?? '';
        $rememberMe = isset($_POST['remember_me']);

        if (empty($loginInput) || empty($password)) {
            $error = 'Please enter both username/email and password.';
        } else {
            try {
                // Find user by username or email
                $stmt = $conn->prepare("SELECT id, username, email, password, role, status FROM users WHERE username = ? OR email = ? LIMIT 1");
                $stmt->bind_param("ss", $loginInput, $loginInput);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                $stmt->close();

                if ($user && password_verify($password, $user['password'])) {
                    // Check if account is active
                    if ($user['status'] !== 'active') {
                        $error = 'Your account is deactivated. Please contact campus admin.';
                    } else {
                        // Secure session regeneration to prevent Session Fixation attack
                        session_regenerate_id(true);

                        $_SESSION['user_id'] = (int)$user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['role'] = $user['role'];
                        $_SESSION['last_activity'] = time();

                        // Practical 10 Intermediate Extension: Update last_login timestamp
                        $updateStmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                        $updateStmt->bind_param("i", $user['id']);
                        $updateStmt->execute();
                        $updateStmt->close();

                        // Practical 10 Advanced Extension: Remember-Me Token using random hashed token
                        if ($rememberMe) {
                            $rawToken = bin2hex(random_bytes(32));
                            $tokenHash = hash('sha256', $rawToken);
                            
                            $remStmt = $conn->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                            $remStmt->bind_param("si", $tokenHash, $user['id']);
                            $remStmt->execute();
                            $remStmt->close();

                            // Set cookie for 30 days
                            setcookie(
                                'studenthub_remember',
                                $user['id'] . ':' . $rawToken,
                                [
                                    'expires'  => time() + (30 * 86400),
                                    'path'     => '/studenthub',
                                    'httponly' => true,
                                    'samesite' => 'Lax'
                                ]
                            );
                        }

                        // Practical 14: Audit Log
                        log_audit($conn, 'LOGIN_SUCCESS', "User {$user['username']} logged in ({$user['role']}).");

                        // Role-Based Redirection
                        if ($user['role'] === 'admin') {
                            header('Location: admin/dashboard.php?success=Welcome back Administrator!');
                        } else {
                            header('Location: index.php?success=Welcome back, ' . urlencode($user['username']) . '!');
                        }
                        exit;
                    }
                } else {
                    $error = 'Invalid credentials. Please verify username/email and password.';
                    // Log failed attempt
                    log_audit($conn, 'LOGIN_FAILED', "Failed login attempt for identifier: {$loginInput}");
                }
            } catch (mysqli_sql_exception $e) {
                $error = 'Authentication error: ' . $e->getMessage();
            }
        }
    }
}

$pageTitle = 'Login - Practical 10';
$activePage = 'login';
$breadcrumbs = ['Login' => ''];
require_once __DIR__ . '/includes/header.php';
?>

<div class="card p-4 shadow-md" style="max-width: 450px; margin: 2rem auto;">
    <h1 class="card-title text-center" style="font-size: 1.6rem; text-align:center;">StudentHub Login</h1>
    <p class="text-muted text-center" style="text-align:center; margin-bottom: 1.5rem; font-size: 0.9rem;">
        Sign in with your university student or administrator account
    </p>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST" id="loginForm">
        <?= csrf_input_field() ?>

        <div class="form-group">
            <label class="form-label" for="username_or_email">Username or Email</label>
            <input type="text" id="username_or_email" name="username_or_email" class="form-control" value="<?= htmlspecialchars($_POST['username_or_email'] ?? '') ?>" placeholder="username or official email" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <div class="form-group" style="display:flex; justify-content:space-between; align-items:center;">
            <label style="font-size: 0.9rem; cursor:pointer;">
                <input type="checkbox" name="remember_me" id="remember_me"> Remember me
            </label>
            <a href="contact.php" style="font-size:0.85rem; text-decoration:none;">Need help?</a>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.7rem; font-size: 1rem; margin-top: 0.5rem;">
            Sign In &rarr;
        </button>
    </form>

    <div style="text-align:center; margin-top: 1.5rem; font-size: 0.9rem;">
        Don't have an account yet? <a href="register.php">Register here</a>
    </div>

    <hr style="border:0; border-top: 1px solid var(--border-color); margin: 1.5rem 0;">

    <div style="background:var(--bg-main); padding: 0.75rem; border-radius: 6px; font-size: 0.8rem;">
        <strong>Demo Test Credentials:</strong><br>
        👑 <strong>Admin</strong>: <code>admin</code> / <code>Admin@123</code><br>
        🎓 <strong>Student</strong>: <code>aarav</code> / <code>Student@123</code>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
