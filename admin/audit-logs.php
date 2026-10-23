<?php
/**
 * Practical 14: Audit Logs Management & Traceability
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_admin();

$actionFilter = trim($_GET['action'] ?? '');
$userFilter = trim($_GET['user'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 12;
$offset = ($page - 1) * $limit;

$where = [];
$params = [];
$types = '';

if ($actionFilter !== '') {
    $where[] = "action LIKE ?";
    $params[] = "%{$actionFilter}%";
    $types .= 's';
}
if ($userFilter !== '') {
    $where[] = "username LIKE ?";
    $params[] = "%{$userFilter}%";
    $types .= 's';
}

$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Count
$countStmt = $conn->prepare("SELECT COUNT(*) FROM audit_logs {$whereSql}");
if ($params) $countStmt->bind_param($types, ...$params);
$countStmt->execute();
$totalLogs = (int)$countStmt->get_result()->fetch_row()[0];
$countStmt->close();

$totalPages = max(1, (int)ceil($totalLogs / $limit));

// Fetch
$dataQuery = "SELECT * FROM audit_logs {$whereSql} ORDER BY id DESC LIMIT ? OFFSET ?";
$paramsData = $params;
$paramsData[] = $limit;
$paramsData[] = $offset;
$typesData = $types . 'ii';

$dataStmt = $conn->prepare($dataQuery);
$dataStmt->bind_param($typesData, ...$paramsData);
$dataStmt->execute();
$logs = $dataStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$dataStmt->close();

$pageTitle = 'Security & Audit Logs - Practical 14';
$activePage = 'audit';
$breadcrumbs = ['Admin Panel' => 'dashboard.php', 'Audit Logs' => ''];
require_once __DIR__ . '/../includes/header.php';
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
    <div>
        <h1 class="card-title" style="font-size:1.75rem; margin:0;">System Audit Logs & Traceability</h1>
        <p class="text-muted">Practical 14: Trace all administrative actions, logins, CRUD operations and security events.</p>
    </div>
    <div>
        <a href="dashboard.php" class="btn btn-outline btn-sm">&larr; Back to Dashboard</a>
    </div>
</div>

<div class="card p-3 mb-4" style="margin-bottom:1.5rem;">
    <form method="GET" action="audit-logs.php" style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end;">
        <div style="flex:1; min-width:180px;">
            <label class="form-label" for="action">Action Filter</label>
            <input type="text" id="action" name="action" class="form-control" placeholder="e.g. LOGIN, CREATE, DELETE..." value="<?= htmlspecialchars($actionFilter) ?>">
        </div>
        <div style="flex:1; min-width:180px;">
            <label class="form-label" for="user">Username Filter</label>
            <input type="text" id="user" name="user" class="form-control" placeholder="e.g. admin, aarav..." value="<?= htmlspecialchars($userFilter) ?>">
        </div>
        <div>
            <button type="submit" class="btn btn-primary">Filter Logs</button>
            <a href="audit-logs.php" class="btn btn-outline">Reset</a>
        </div>
    </form>
</div>

<div class="card p-0" style="overflow:hidden;">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Log ID</th>
                    <th>Timestamp</th>
                    <th>Operator</th>
                    <th>Action</th>
                    <th>Details</th>
                    <th>Client IP</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding:2rem;" class="text-muted">No audit logs matching query.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $l): ?>
                        <tr>
                            <td><code>#<?= $l['id'] ?></code></td>
                            <td><small><?= $l['created_at'] ?></small></td>
                            <td><strong><?= htmlspecialchars($l['username']) ?></strong></td>
                            <td>
                                <span class="badge <?= str_contains($l['action'], 'DELETE') ? 'badge-danger' : (str_contains($l['action'], 'CREATE') ? 'badge-success' : 'badge-primary') ?>">
                                    <?= htmlspecialchars($l['action']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($l['details']) ?></td>
                            <td><code><?= htmlspecialchars($l['ip_address']) ?></code></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div style="display:flex; justify-content:space-between; align-items:center; margin-top:1.5rem;">
    <span class="text-muted" style="font-size:0.9rem;">
        Showing <?= min($offset + 1, $totalLogs) ?> to <?= min($offset + $limit, $totalLogs) ?> of <?= $totalLogs ?> log records
    </span>
    <div style="display:flex; gap:0.35rem;">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>&action=<?= urlencode($actionFilter) ?>&user=<?= urlencode($userFilter) ?>" class="btn btn-outline btn-sm">&larr; Prev</a>
        <?php endif; ?>
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>&action=<?= urlencode($actionFilter) ?>&user=<?= urlencode($userFilter) ?>" class="btn btn-outline btn-sm">Next &rarr;</a>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
