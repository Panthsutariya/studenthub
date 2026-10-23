<?php
/**
 * Practical 11: Add Student
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_admin(); // Admins only can add students

require_once __DIR__ . '/includes/csrf.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Session security token expired. Please retry.';
    } else {
        $studentNumber = strtoupper(trim($_POST['student_number'] ?? ''));
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $mobile = trim($_POST['mobile'] ?? '');
        $branch = trim($_POST['branch'] ?? '');
        $year = trim($_POST['year'] ?? '');
        $gender = trim($_POST['gender'] ?? '');
        $status = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';

        if (!preg_match('/^[0-9]{2}[A-Za-z]{2}[0-9]{3}$/', $studentNumber)) {
            $errors[] = 'Invalid Student Number pattern (e.g. 23IT001).';
        }
        if (empty($fullName) || strlen($fullName) < 2) {
            $errors[] = 'Full name must contain at least 2 characters.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required.';
        }
        if (!preg_match('/^[6-9]\d{9}$/', $mobile)) {
            $errors[] = 'Valid 10-digit mobile number starting with 6-9 is required.';
        }

        if (empty($errors)) {
            try {
                // Check duplicate student_number
                $stmt = $conn->prepare("SELECT id FROM students WHERE student_number = ? OR email = ?");
                $stmt->bind_param("ss", $studentNumber, $email);
                $stmt->execute();
                if ($stmt->get_result()->num_rows > 0) {
                    $errors[] = 'A student with this Student Number or Email already exists.';
                }
                $stmt->close();

                if (empty($errors)) {
                    $insertStmt = $conn->prepare("INSERT INTO students (student_number, full_name, email, mobile, branch, year, gender, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $insertStmt->bind_param("ssssssss", $studentNumber, $fullName, $email, $mobile, $branch, $year, $gender, $status);
                    $insertStmt->execute();
                    $insertStmt->close();

                    log_audit($conn, 'CREATE_STUDENT', "Admin created student {$fullName} ({$studentNumber})");

                    header('Location: students.php?success=' . urlencode("Student {$fullName} added successfully."));
                    exit;
                }
            } catch (mysqli_sql_exception $e) {
                $errors[] = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

$pageTitle = 'Add New Student - Practical 11';
$activePage = 'students';
$breadcrumbs = ['Students' => 'students.php', 'Add Student' => ''];
require_once __DIR__ . '/includes/header.php';
?>

<div class="card p-4" style="max-width: 700px; margin: 0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1.5rem;">
        <h1 class="card-title" style="font-size:1.5rem; margin:0;">Add New Student Record</h1>
        <a href="students.php" class="btn btn-outline btn-sm">&larr; Back to List</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul style="margin:0; padding-left:1.25rem;">
                <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="student-add.php" method="POST">
        <?= csrf_input_field() ?>

        <div class="grid-2">
            <div class="form-group">
                <label class="form-label" for="student_number">Student Number (ID) *</label>
                <input type="text" id="student_number" name="student_number" class="form-control" value="<?= htmlspecialchars($_POST['student_number'] ?? '') ?>" placeholder="e.g. 23IT025" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="full_name">Full Name *</label>
                <input type="text" id="full_name" name="full_name" class="form-control" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" placeholder="e.g. Sneha Patel" required>
            </div>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label class="form-label" for="email">Email *</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="e.g. sneha.patel@charusat.edu.in" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="mobile">Mobile Number *</label>
                <input type="tel" id="mobile" name="mobile" class="form-control" value="<?= htmlspecialchars($_POST['mobile'] ?? '') ?>" placeholder="10-digit mobile" required>
            </div>
        </div>

        <div class="grid-3">
            <div class="form-group">
                <label class="form-label" for="branch">Branch *</label>
                <select id="branch" name="branch" class="form-control" required>
                    <option value="Information Technology">Information Technology</option>
                    <option value="Computer Engineering">Computer Engineering</option>
                    <option value="Mechanical Engineering">Mechanical Engineering</option>
                    <option value="Civil Engineering">Civil Engineering</option>
                    <option value="Electrical Engineering">Electrical Engineering</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="year">Year *</label>
                <select id="year" name="year" class="form-control" required>
                    <option value="1st Year">1st Year</option>
                    <option value="2nd Year">2nd Year</option>
                    <option value="3rd Year" selected>3rd Year</option>
                    <option value="4th Year">4th Year</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Gender *</label>
                <div style="display:flex; gap:0.75rem; margin-top:0.4rem;">
                    <label><input type="radio" name="gender" value="Male" checked> Male</label>
                    <label><input type="radio" name="gender" value="Female"> Female</label>
                    <label><input type="radio" name="gender" value="Other"> Other</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="status">Account Status</label>
            <select id="status" name="status" class="form-control" style="max-width:200px;">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="padding: 0.7rem 1.8rem;">Save Student &rarr;</button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
