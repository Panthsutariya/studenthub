<?php
/**
 * Practical 5 & Practical 9: Secure User Registration
 * Features: Frontend Regex Validation, Password Strength Meter, Duplicate Checks,
 * Bcrypt Hashing, Prepared Statements, Audit Log Entry
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/auth.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Verify CSRF Token
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid or expired session security token. Please resubmit.';
    }

    // 2. Sanitize and retrieve inputs
    $fullName = trim($_POST['fullname'] ?? '');
    $studentNumber = strtoupper(trim($_POST['student_number'] ?? ''));
    $email = trim($_POST['email'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $branch = trim($_POST['branch'] ?? '');
    $year = trim($_POST['year'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $terms = isset($_POST['terms']);

    // 3. Server-side Validations
    if (!preg_match('/^[a-zA-Z\s]{2,50}$/', $fullName)) {
        $errors[] = 'Full name must contain 2 to 50 alphabetic characters.';
    }
    if (!preg_match('/^[0-9]{2}[A-Za-z]{2}[0-9]{3}$/', $studentNumber)) {
        $errors[] = 'Student Number must follow university pattern (e.g., 23IT001).';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email address is required.';
    }
    if (!preg_match('/^[6-9]\d{9}$/', $mobile)) {
        $errors[] = 'Mobile number must be a 10-digit number starting with 6, 7, 8, or 9.';
    }
    if (empty($branch)) {
        $errors[] = 'Please select your academic branch.';
    }
    if (empty($year)) {
        $errors[] = 'Please select your current year.';
    }
    if (!in_array($gender, ['Male', 'Female', 'Other'], true)) {
        $errors[] = 'Please select a valid gender option.';
    }
    if (!preg_match('/^[a-zA-Z0-9_]{3,25}$/', $username)) {
        $errors[] = 'Username must be 3 to 25 alphanumeric characters.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters in length.';
    }
    if ($password !== $confirmPassword) {
        $errors[] = 'Password and Confirm Password do not match.';
    }
    if (!$terms) {
        $errors[] = 'You must accept the terms and conditions.';
    }

    // 4. Check for duplicate email, username, or student number (Practical 9)
    if (empty($errors)) {
        try {
            // Check username in users
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $errors[] = 'This username is already taken. Please choose another.';
            }
            $stmt->close();

            // Check email in users
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $errors[] = 'An account with this email address already exists.';
            }
            $stmt->close();

            // Check student_number in students
            $stmt = $conn->prepare("SELECT id FROM students WHERE student_number = ? LIMIT 1");
            $stmt->bind_param("s", $studentNumber);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $errors[] = 'This student number is already registered in the system.';
            }
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            $errors[] = 'Database verification error: ' . $e->getMessage();
        }
    }

    // 5. Insert User & Student Records using Prepared Statements and Password Hashing
    if (empty($errors)) {
        try {
            $conn->begin_transaction();

            // Hash password securely using Bcrypt (Practical 9 requirement)
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $role = 'student';
            $status = 'active';

            // Insert into users table
            $stmtUser = $conn->prepare("INSERT INTO users (username, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
            $stmtUser->bind_param("sssss", $username, $email, $hashedPassword, $role, $status);
            $stmtUser->execute();
            $newUserId = $conn->insert_id;
            $stmtUser->close();

            // Insert into students table
            $stmtStudent = $conn->prepare("INSERT INTO students (student_number, full_name, email, mobile, branch, year, gender, user_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtStudent->bind_param("sssssssis", $studentNumber, $fullName, $email, $mobile, $branch, $year, $gender, $newUserId, $status);
            $stmtStudent->execute();
            $stmtStudent->close();

            // Commit transaction
            $conn->commit();

            // Record audit log entry (Practical 14 requirement)
            log_audit($conn, 'USER_REGISTERED', "Student registered: {$username} ({$studentNumber})");

            header('Location: login.php?success=' . urlencode('Registration successful! You can now log in with your credentials.'));
            exit;
        } catch (mysqli_sql_exception $e) {
            $conn->rollback();
            $errors[] = 'Registration failed: ' . $e->getMessage();
        }
    }
}

$pageTitle = 'Student Registration - Practicals 5 & 9';
$activePage = 'register';
$breadcrumbs = ['Register' => ''];
$extraScripts = ['/studenthub/js/validation.js'];
require_once __DIR__ . '/includes/header.php';
?>

<div class="card p-4" style="max-width: 800px; margin: 0 auto;">
    <h1 class="card-title text-center" style="font-size: 1.75rem; text-align:center;">Student Registration Portal</h1>
    <p class="text-muted text-center" style="text-align:center; margin-bottom: 1.5rem;">
        Fill out the form below to create your student account. Real-time validation and secure hashing enabled.
    </p>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 1.25rem;">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="register.php" method="POST" id="registrationForm" novalidate>
        <?= csrf_input_field() ?>

        <div class="grid-2">
            <div class="form-group">
                <label class="form-label" for="fullname">Full Name *</label>
                <input type="text" id="fullname" name="fullname" class="form-control" value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>" placeholder="e.g. Diya Sharma" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="student_number">Student Number (ID) *</label>
                <input type="text" id="student_number" name="student_number" class="form-control" value="<?= htmlspecialchars($_POST['student_number'] ?? '') ?>" placeholder="e.g. 23IT001" required>
            </div>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label class="form-label" for="email">University Email *</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="e.g. diya.sharma@charusat.edu.in" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="mobile">Mobile Number *</label>
                <input type="tel" id="mobile" name="mobile" class="form-control" value="<?= htmlspecialchars($_POST['mobile'] ?? '') ?>" placeholder="10-digit mobile number" required>
            </div>
        </div>

        <div class="grid-3">
            <div class="form-group">
                <label class="form-label" for="branch">Academic Branch *</label>
                <select id="branch" name="branch" class="form-control" required>
                    <option value="">Select Branch</option>
                    <option value="Information Technology" <?= ($_POST['branch'] ?? '') === 'Information Technology' ? 'selected' : '' ?>>Information Technology</option>
                    <option value="Computer Engineering" <?= ($_POST['branch'] ?? '') === 'Computer Engineering' ? 'selected' : '' ?>>Computer Engineering</option>
                    <option value="Mechanical Engineering" <?= ($_POST['branch'] ?? '') === 'Mechanical Engineering' ? 'selected' : '' ?>>Mechanical Engineering</option>
                    <option value="Civil Engineering" <?= ($_POST['branch'] ?? '') === 'Civil Engineering' ? 'selected' : '' ?>>Civil Engineering</option>
                    <option value="Electrical Engineering" <?= ($_POST['branch'] ?? '') === 'Electrical Engineering' ? 'selected' : '' ?>>Electrical Engineering</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="year">Current Year *</label>
                <select id="year" name="year" class="form-control" required>
                    <option value="">Select Year</option>
                    <option value="1st Year" <?= ($_POST['year'] ?? '') === '1st Year' ? 'selected' : '' ?>>1st Year</option>
                    <option value="2nd Year" <?= ($_POST['year'] ?? '') === '2nd Year' ? 'selected' : '' ?>>2nd Year</option>
                    <option value="3rd Year" <?= ($_POST['year'] ?? '') === '3rd Year' ? 'selected' : '' ?>>3rd Year</option>
                    <option value="4th Year" <?= ($_POST['year'] ?? '') === '4th Year' ? 'selected' : '' ?>>4th Year</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Gender *</label>
                <div style="display:flex; gap: 1rem; margin-top: 0.5rem;">
                    <label><input type="radio" name="gender" value="Male" <?= ($_POST['gender'] ?? '') === 'Male' ? 'checked' : '' ?> required> Male</label>
                    <label><input type="radio" name="gender" value="Female" <?= ($_POST['gender'] ?? '') === 'Female' ? 'checked' : '' ?>> Female</label>
                    <label><input type="radio" name="gender" value="Other" <?= ($_POST['gender'] ?? '') === 'Other' ? 'checked' : '' ?>> Other</label>
                </div>
            </div>
        </div>

        <hr style="border:0; border-top: 1px solid var(--border-color); margin: 1.5rem 0;">

        <div class="grid-3">
            <div class="form-group">
                <label class="form-label" for="username">Login Username *</label>
                <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" placeholder="e.g. diya_sharma" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password *</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Min 8 characters" required>
                <div class="pwd-meter-bar"><div id="pwdMeterFill" class="pwd-meter-fill"></div></div>
                <small id="pwdMeterText" style="font-size:0.75rem; font-weight:bold;"></small>
            </div>

            <div class="form-group">
                <label class="form-label" for="confirm_password">Confirm Password *</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Re-type password" required>
            </div>
        </div>

        <!-- Practical 5 Advanced Extension: Canvas CAPTCHA -->
        <div class="form-group card p-3" style="background:var(--bg-main); border: 1px dashed var(--border-color); margin: 1.5rem 0;">
            <label class="form-label">Security Verification (Canvas CAPTCHA)</label>
            <div style="display:flex; align-items:center; gap: 1rem; flex-wrap: wrap;">
                <canvas id="captchaCanvas" width="160" height="42" style="border:1px solid #cbd5e1; border-radius:4px;"></canvas>
                <button type="button" id="refreshCaptcha" class="btn btn-outline btn-sm">↻ Refresh</button>
                <input type="text" id="captcha" name="captcha" class="form-control" placeholder="Enter CAPTCHA text" style="max-width: 200px;" required>
            </div>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" id="terms" name="terms" required <?= isset($_POST['terms']) ? 'checked' : '' ?>>
                I agree to the university academic code of conduct and terms of service.
            </label>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">Register Account &rarr;</button>
            <span>Already have an account? <a href="login.php">Log In</a></span>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
