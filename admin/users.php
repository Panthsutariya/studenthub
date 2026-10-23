<?php
/**
 * Practical 14 Intermediate Extension: Manage Users & Active/Inactive Status Toggle
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_once __DIR__ . '/../includes/csrf.php';

// Handle Toggle Status Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_id'])) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        header('Location: users.php?error=Security session expired.');
        exit;
    }

    $toggleId = (int)$_POST['toggle_id'];
    if ($toggleId === (int)$_SESSION['user_id']) {
        header('Location: users.php?error=You cannot deactivate your own administrative account.');
        exit;
    }

    $stmt = $conn->prepare("SELECT username, status FROM users WHERE id = ?");
    $stmt->bind_param("i", $toggleId);
    $stmt->execute();
    $u = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($u) {
        $newStatus = ($u['status'] === 'active') ? 'inactive' : 'active';
        $up = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
        $up->bind_param("si", $newStatus, $toggleId);
        $up->execute();
        $up->close();

        log_audit($conn, 'USER_STATUS_TOGGLE', "Toggled status of user {$u['username']} to {$newStatus}");
        header('Location: users.php?success=' . urlencode("User {$u['username']} status updated to {$newStatus}."));
        exit;
    }
}

// Fetch all users
$users = $conn->query("
    SELECT u.id, u.username, u.email, u.role, u.status, u.last_login, u.created_at, s.student_number, s.full_name
    FROM users u
    LEFT JOIN students s ON s.user_id = u.id
    ORDER BY u.id ASC
")->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'Manage Portal Users - Practical 14';
$activePage = 'admin';
$breadcrumbs = ['Admin Panel' => 'dashboard.php', 'Users' => ''];
require_once __DIR__ . '/../includes/header.php';
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
    <div>
        <h1 class="card-title" style="font-size:1.75rem; margin:0;">User Accounts & RBAC Roles</h1>
        <p class="text-muted">Manage authenticated users and toggle account statuses (Active / Inactive).</p>
    </div>
    <div>
        <a href="dashboard.php" class="btn btn-outline btn-sm">&larr; Back to Dashboard</a>
    </div>
</div>

<div class="card p-0" style="overflow:hidden;">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Linked Student</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th style="text-align:right;">Status Toggle</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><code>#<?= $u['id'] ?></code></td>
                        <td><strong><?= htmlspecialchars($u['username']) ?></strong></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <?php if (!empty($u['full_name'])): ?>
                                <?= htmlspecialchars($u['full_name']) ?> (<code><?= htmlspecialchars($u['student_number']) ?></code>)
                            <?php else: ?>
                                <span class="text-muted">N/A (Staff/Admin)</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?= $u['role'] === 'admin' ? 'badge-danger' : 'badge-primary' ?>">
                                <?= strtoupper(htmlspecialchars($u['role'])) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= $u['status'] === 'active' ? 'badge-success' : 'badge-danger' ?>">
                                <?= ucfirst(htmlspecialchars($u['status'])) ?>
                            </span>
                        </td>
                        <td><small class="text-muted"><?= $u['last_login'] ? date('d M Y, H:i', strtotime($u['last_login'])) : 'Never' ?></small></td>
                        <td style="text-align:right;">
                            <?php if ($u['id'] !== (int)$_SESSION['user_id']): ?>
                                <form action="users.php" method="POST" style="display:inline;">
                                    <?= csrf_input_field() ?>
                                    <input type="hidden" name="toggle_id" value="<?= $u['id'] ?>">
                                    <button type="submit" class="btn btn-sm <?= $u['status'] === 'active' ? 'btn-danger' : 'btn-success' ?>">
                                        <?= $u['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                                    </button>
                                </form>
                            <?php else: ?>
                                <small class="text-muted">Current User</small>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
