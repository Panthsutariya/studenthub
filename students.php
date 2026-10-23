<?php
/**
 * Practical 11: Student Management CRUD with Search and Filter
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_login(); // Students can view, Admins have full CRUD

require_once __DIR__ . '/includes/csrf.php';

// Parameters
$search = trim($_GET['search'] ?? '');
$filterBranch = trim($_GET['branch'] ?? '');
$filterYear = trim($_GET['year'] ?? '');
$filterStatus = trim($_GET['status'] ?? '');

$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 8;
$offset = ($page - 1) * $limit;

// Build query
$whereClauses = [];
$params = [];
$types = '';

if ($search !== '') {
    $whereClauses[] = "(full_name LIKE ? OR student_number LIKE ? OR email LIKE ?)";
    $like = "%{$search}%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= 'sss';
}
if ($filterBranch !== '') {
    $whereClauses[] = "branch = ?";
    $params[] = $filterBranch;
    $types .= 's';
}
if ($filterYear !== '') {
    $whereClauses[] = "year = ?";
    $params[] = $filterYear;
    $types .= 's';
}
if ($filterStatus !== '') {
    $whereClauses[] = "status = ?";
    $params[] = $filterStatus;
    $types .= 's';
}

$whereSql = $whereClauses ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

// Count total
$countSql = "SELECT COUNT(*) FROM students {$whereSql}";
$stmtCount = $conn->prepare($countSql);
if ($params) {
    $stmtCount->bind_param($types, ...$params);
}
$stmtCount->execute();
$totalRecords = (int)$stmtCount->get_result()->fetch_row()[0];
$stmtCount->close();

$totalPages = max(1, (int)ceil($totalRecords / $limit));

// Fetch records
$dataSql = "SELECT * FROM students {$whereSql} ORDER BY id DESC LIMIT ? OFFSET ?";
$paramsData = $params;
$paramsData[] = $limit;
$paramsData[] = $offset;
$typesData = $types . 'ii';

$stmtData = $conn->prepare($dataSql);
$stmtData->bind_param($typesData, ...$paramsData);
$stmtData->execute();
$students = $stmtData->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtData->close();

$pageTitle = 'Student Management CRUD - Practical 11';
$activePage = 'students';
$breadcrumbs = ['Students' => ''];
require_once __DIR__ . '/includes/header.php';
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
    <div>
        <h1 class="card-title" style="font-size:1.75rem; margin:0;">Student Records Management</h1>
        <p class="text-muted">Practical 11: Real-time search, multi-criteria filter, MySQLi prepared statements & pagination.</p>
    </div>
    <div style="display:flex; gap:0.5rem;">
        <a href="student-export.php" class="btn btn-outline btn-sm">📥 Export to CSV</a>
        <?php if (is_admin()): ?>
            <a href="student-add.php" class="btn btn-primary btn-sm">+ Add New Student</a>
        <?php endif; ?>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="card p-3 mb-4" style="margin-bottom: 1.5rem;">
    <form method="GET" action="students.php" style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end;">
        <div style="flex:2; min-width:200px;">
            <label class="form-label" for="search">Search</label>
            <input type="search" id="search" name="search" class="form-control" placeholder="Search by name, ID or email..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <div style="flex:1; min-width:160px;">
            <label class="form-label" for="branch">Branch</label>
            <select id="branch" name="branch" class="form-control">
                <option value="">All Branches</option>
                <?php
                $branches = ['Information Technology', 'Computer Engineering', 'Mechanical Engineering', 'Civil Engineering', 'Electrical Engineering'];
                foreach ($branches as $b) {
                    echo '<option value="' . htmlspecialchars($b) . '"' . ($filterBranch === $b ? ' selected' : '') . '>' . htmlspecialchars($b) . '</option>';
                }
                ?>
            </select>
        </div>
        <div style="flex:1; min-width:130px;">
            <label class="form-label" for="year">Year</label>
            <select id="year" name="year" class="form-control">
                <option value="">All Years</option>
                <?php
                $years = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
                foreach ($years as $y) {
                    echo '<option value="' . htmlspecialchars($y) . '"' . ($filterYear === $y ? ' selected' : '') . '>' . htmlspecialchars($y) . '</option>';
                }
                ?>
            </select>
        </div>
        <div style="flex:1; min-width:120px;">
            <label class="form-label" for="status">Status</label>
            <select id="status" name="status" class="form-control">
                <option value="">All Status</option>
                <option value="active" <?= $filterStatus === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $filterStatus === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="students.php" class="btn btn-outline">Reset</a>
        </div>
    </form>
</div>

<!-- Students Data Table -->
<div class="card p-0" style="overflow:hidden;">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Branch & Year</th>
                    <th>Status</th>
                    <?php if (is_admin()): ?>
                        <th style="text-align:right;">Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="<?= is_admin() ? '7' : '6' ?>" style="text-align:center; padding:2rem;" class="text-muted">
                            No student records found matching your filter criteria.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($students as $s): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($s['student_number']) ?></code></td>
                            <td>
                                <strong><?= htmlspecialchars($s['full_name']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($s['gender']) ?></small>
                            </td>
                            <td><?= htmlspecialchars($s['email']) ?></td>
                            <td><?= htmlspecialchars($s['mobile']) ?></td>
                            <td>
                                <?= htmlspecialchars($s['branch']) ?><br>
                                <small class="text-muted"><?= htmlspecialchars($s['year']) ?></small>
                            </td>
                            <td>
                                <span class="badge <?= $s['status'] === 'active' ? 'badge-success' : 'badge-danger' ?>">
                                    <?= ucfirst(htmlspecialchars($s['status'])) ?>
                                </span>
                            </td>
                            <?php if (is_admin()): ?>
                                <td style="text-align:right; white-space:nowrap;">
                                    <a href="student-edit.php?id=<?= $s['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $s['id'] ?>, '<?= htmlspecialchars(addslashes($s['full_name'])) ?>')">Delete</button>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-top:1.5rem; flex-wrap:wrap; gap:1rem;">
    <span class="text-muted" style="font-size:0.9rem;">
        Showing <?= min($offset + 1, $totalRecords) ?> to <?= min($offset + $limit, $totalRecords) ?> of <?= $totalRecords ?> student records
    </span>

    <div style="display:flex; gap:0.35rem;">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&branch=<?= urlencode($filterBranch) ?>&year=<?= urlencode($filterYear) ?>&status=<?= urlencode($filterStatus) ?>" class="btn btn-outline btn-sm">&larr; Prev</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&branch=<?= urlencode($filterBranch) ?>&year=<?= urlencode($filterYear) ?>&status=<?= urlencode($filterStatus) ?>" class="btn btn-sm <?= $i === $page ? 'btn-primary' : 'btn-outline' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&branch=<?= urlencode($filterBranch) ?>&year=<?= urlencode($filterYear) ?>&status=<?= urlencode($filterStatus) ?>" class="btn btn-outline btn-sm">Next &rarr;</a>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal (Practical 11 Requirement) -->
<div id="deleteModal" class="modal-backdrop" aria-hidden="true">
    <div class="modal-content">
        <h3 class="card-title text-danger" style="margin-bottom:0.75rem;">Confirm Delete</h3>
        <p>Are you sure you want to delete student: <strong id="deleteStudentName"></strong>?</p>
        
        <form action="student-delete.php" method="POST" id="deleteStudentForm" style="margin-top:1.5rem;">
            <?= csrf_input_field() ?>
            <input type="hidden" name="id" id="deleteStudentId" value="">

            <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label">Delete Mode</label>
                <div>
                    <label style="margin-right:1rem;"><input type="radio" name="mode" value="soft" checked> Soft Delete (Mark as Inactive)</label>
                    <label><input type="radio" name="mode" value="permanent"> Permanent Delete</label>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                <button type="button" class="btn btn-outline" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-danger">Confirm & Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('deleteStudentId').value = id;
    document.getElementById('deleteStudentName').textContent = name;
    document.getElementById('deleteModal').classList.add('show');
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
