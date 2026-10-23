<?php
/**
 * Practical 11: Edit Student
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_admin();
require_once __DIR__ . '/includes/csrf.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: students.php?error=Invalid student record ID.');
    exit;
}

// Fetch existing record
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$student) {
    header('Location: students.php?error=Student record not found.');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Session security token expired. Please retry.';
    } else {
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $mobile = trim($_POST['mobile'] ?? '');
        $branch = trim($_POST['branch'] ?? '');
        $year = trim($_POST['year'] ?? '');
        $gender = trim($_POST['gender'] ?? '');
        $status = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';

        if (empty($fullName) || strlen($fullName) < 2) {
            $errors[] = 'Full name must contain at least 2 characters.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required.';
        }
        if (!preg_match('/^[6-9]\d{9}$/', $mobile)) {
            $errors[] = 'Valid 10-digit mobile number is required.';
        }

        // Duplicate check for email except current student
        if (empty($errors)) {
            $dupStmt = $conn->prepare("SELECT id FROM students WHERE email = ? AND id != ?");
            $dupStmt->bind_param("si", $email, $id);
            $dupStmt->execute();
            if ($dupStmt->get_result()->num_rows > 0) {
                $errors[] = 'This email is already in use by another student.';
            }
            $dupStmt->close();
        }

        if (empty($errors)) {
            try {
                $upStmt = $conn->prepare("UPDATE students SET full_name = ?, email = ?, mobile = ?, branch = ?, year = ?, gender = ?, status = ? WHERE id = ?");
                $upStmt->bind_param("sssssssi", $fullName, $email, $mobile, $branch, $year, $gender, $status, $id);
                $upStmt->execute();
                $upStmt->close();

                log_audit($conn, 'UPDATE_STUDENT', "Admin updated student ID {$id}: {$fullName}");

                header('Location: students.php?success=' . urlencode("Student {$fullName} updated successfully."));
                exit;
            } catch (mysqli_sql_exception $e) {
                $errors[] = 'Update failed: ' . $e->getMessage();
            }
        }
    }
}

$pageTitle = 'Edit Student - Practical 11';
$activePage = 'students';
$breadcrumbs = ['Students' => 'students.php', 'Edit Student' => ''];
require_once __DIR__ . '/includes/header.php';
?>

<div class="card p-4" style="max-width: 700px; margin: 0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1.5rem;">
        <div>
            <h1 class="card-title" style="font-size:1.5rem; margin:0;">Edit Student: <?= htmlspecialchars($student['full_name']) ?></h1>
            <p class="text-muted" style="font-size:0.85rem;">Student Number: <code><?= htmlspecialchars($student['student_number']) ?></code></p>
        </div>
        <a href="students.php" class="btn btn-outline btn-sm">&larr; Back to List</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul style="margin:0; padding-left:1.25rem;">
                <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="student-edit.php?id=<?= $id ?>" method="POST">
        <?= csrf_input_field() ?>

        <div class="grid-2">
            <div class="form-group">
                <label class="form-label" for="full_name">Full Name *</label>
                <input type="text" id="full_name" name="full_name" class="form-control" value="<?= htmlspecialchars($_POST['full_name'] ?? $student['full_name']) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="email">University Email *</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? $student['email']) ?>" required>
            </div>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label class="form-label" for="mobile">Mobile Number *</label>
                <input type="tel" id="mobile" name="mobile" class="form-control" value="<?= htmlspecialchars($_POST['mobile'] ?? $student['mobile']) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="branch">Branch *</label>
                <select id="branch" name="branch" class="form-control" required>
                    <?php
                    $branches = ['Information Technology', 'Computer Engineering', 'Mechanical Engineering', 'Civil Engineering', 'Electrical Engineering'];
                    $currBranch = $_POST['branch'] ?? $student['branch'];
                    foreach ($branches as $b) {
                        echo '<option value="' . htmlspecialchars($b) . '"' . ($currBranch === $b ? ' selected' : '') . '>' . htmlspecialchars($b) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="grid-3">
            <div class="form-group">
                <label class="form-label" for="year">Year *</label>
                <select id="year" name="year" class="form-control" required>
                    <?php
                    $years = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
                    $currYear = $_POST['year'] ?? $student['year'];
                    foreach ($years as $y) {
                        echo '<option value="' . htmlspecialchars($y) . '"' . ($currYear === $y ? ' selected' : '') . '>' . htmlspecialchars($y) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Gender *</label>
                <div style="display:flex; gap:0.75rem; margin-top:0.4rem;">
                    <?php $currGender = $_POST['gender'] ?? $student['gender']; ?>
                    <label><input type="radio" name="gender" value="Male" <?= $currGender === 'Male' ? 'checked' : '' ?>> Male</label>
                    <label><input type="radio" name="gender" value="Female" <?= $currGender === 'Female' ? 'checked' : '' ?>> Female</label>
                    <label><input type="radio" name="gender" value="Other" <?= $currGender === 'Other' ? 'checked' : '' ?>> Other</label>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <?php $currStatus = $_POST['status'] ?? $student['status']; ?>
                    <option value="active" <?= $currStatus === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $currStatus === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="padding: 0.7rem 1.8rem;">Update Student &rarr;</button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
