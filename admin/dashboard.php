<?php
/**
 * Practical 14: Admin Dashboard with RBAC, Dynamic Menu, Analytics & Audit Logging
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_admin(); // Practical 14: Strict access restriction to admin users only

// Aggregated Statistics (SQL aggregation)
$studentCount = (int)$conn->query("SELECT COUNT(*) FROM students")->fetch_row()[0];
$eventCount = (int)$conn->query("SELECT COUNT(*) FROM events")->fetch_row()[0];
$userCount = (int)$conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
$registrationCount = (int)$conn->query("SELECT COUNT(*) FROM registrations")->fetch_row()[0];

// Category breakdown for events
$catStats = $conn->query("SELECT category, COUNT(*) as count FROM events GROUP BY category")->fetch_all(MYSQLI_ASSOC);

// Latest 5 Student Registrations
$latestRegs = $conn->query("
    SELECT r.id, r.registered_at, r.status, s.full_name, s.student_number, e.title as event_title
    FROM registrations r
    JOIN students s ON r.student_id = s.id
    JOIN events e ON r.event_id = e.id
    ORDER BY r.registered_at DESC LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

// Latest 5 Audit Log Entries
$latestLogs = $conn->query("SELECT * FROM audit_logs ORDER BY created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'Administrator Dashboard - Practical 14';
$activePage = 'admin';
$breadcrumbs = ['Admin Panel' => ''];
require_once __DIR__ . '/../includes/header.php';
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
    <div>
        <h1 class="card-title" style="font-size:1.75rem; margin:0;">Administrative Control Center</h1>
        <p class="text-muted">Real-time database metrics, role-based controls, audit tracking and analytics.</p>
    </div>
    <div style="display:flex; gap:0.5rem;">
        <a href="audit-logs.php" class="btn btn-outline btn-sm">📜 Audit Logs</a>
        <a href="users.php" class="btn btn-outline btn-sm">👥 Manage Users</a>
        <a href="/studenthub/student-add.php" class="btn btn-primary btn-sm">+ Add Student</a>
    </div>
</div>

<!-- KPI Summary Cards (Practical 14) -->
<div class="grid-4 mb-4" style="margin-bottom:1.5rem;">
    <div class="card" style="border-left: 4px solid var(--primary);">
        <span class="text-muted" style="font-size:0.85rem; text-transform:uppercase; font-weight:bold;">Total Students</span>
        <h2 style="font-size:2rem; margin:0.35rem 0; color:var(--primary);"><?= $studentCount ?></h2>
        <a href="/studenthub/students.php" style="font-size:0.85rem; text-decoration:none;">View all students &rarr;</a>
    </div>

    <div class="card" style="border-left: 4px solid var(--accent);">
        <span class="text-muted" style="font-size:0.85rem; text-transform:uppercase; font-weight:bold;">Campus Events</span>
        <h2 style="font-size:2rem; margin:0.35rem 0; color:var(--accent);"><?= $eventCount ?></h2>
        <a href="/studenthub/events.php" style="font-size:0.85rem; text-decoration:none;">Manage events &rarr;</a>
    </div>

    <div class="card" style="border-left: 4px solid var(--success);">
        <span class="text-muted" style="font-size:0.85rem; text-transform:uppercase; font-weight:bold;">Event Registrations</span>
        <h2 style="font-size:2rem; margin:0.35rem 0; color:var(--success);"><?= $registrationCount ?></h2>
        <span class="text-muted" style="font-size:0.85rem;">Active participations</span>
    </div>

    <div class="card" style="border-left: 4px solid var(--warning);">
        <span class="text-muted" style="font-size:0.85rem; text-transform:uppercase; font-weight:bold;">User Accounts</span>
        <h2 style="font-size:2rem; margin:0.35rem 0; color:var(--warning);"><?= $userCount ?></h2>
        <a href="users.php" style="font-size:0.85rem; text-decoration:none;">RBAC user list &rarr;</a>
    </div>
</div>

<!-- Analytics & Event Breakdown (Practical 14 Advanced Extension) -->
<div class="grid-2 mb-4" style="margin-bottom:1.5rem;">
    <div class="card">
        <h3 class="card-title" style="font-size:1.15rem;">Event Distribution by Category</h3>
        <div style="margin-top:1rem; display:flex; flex-direction:column; gap:0.75rem;">
            <?php foreach ($catStats as $stat): 
                $pct = $eventCount > 0 ? round(($stat['count'] / $eventCount) * 100) : 0;
            ?>
                <div>
                    <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:0.25rem;">
                        <strong><?= htmlspecialchars($stat['category']) ?></strong>
                        <span><?= $stat['count'] ?> events (<?= $pct ?>%)</span>
                    </div>
                    <div style="height:8px; background:var(--border-color); border-radius:4px; overflow:hidden;">
                        <div style="height:100%; width:<?= $pct ?>%; background:var(--primary);"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="card">
        <h3 class="card-title" style="font-size:1.15rem;">Admin Quick Links</h3>
        <div style="display:flex; flex-direction:column; gap:0.6rem; margin-top:1rem;">
            <a href="/studenthub/student-add.php" class="btn btn-outline" style="justify-content:flex-start;">➕ Create New Student Profile</a>
            <a href="/studenthub/event-add.php" class="btn btn-outline" style="justify-content:flex-start;">📅 Create New Campus Event with Poster Upload</a>
            <a href="/studenthub/student-export.php" class="btn btn-outline" style="justify-content:flex-start;">📥 Download Full Student Directory (CSV)</a>
            <a href="/studenthub/view-submissions.php" class="btn btn-outline" style="justify-content:flex-start;">📂 Review Contact Form Submissions (JSON & CSV)</a>
            <a href="/studenthub/events-ajax.php" class="btn btn-outline" style="justify-content:flex-start;">⚡ Open Asynchronous AJAX Event Manager</a>
        </div>
    </div>
</div>

<!-- Two Column: Latest Registrations & Recent Audit Log -->
<div class="grid-2">
    <!-- Latest Registrations -->
    <div class="card p-0" style="overflow:hidden;">
        <div style="padding:1rem 1.25rem; border-bottom:1px solid var(--border-color); display:flex; justify-content:space-between; align-items:center;">
            <h3 class="card-title" style="margin:0; font-size:1.1rem;">Latest Event Registrations</h3>
            <span class="badge badge-primary">Recent 5</span>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Event</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($latestRegs)): ?>
                        <tr><td colspan="3" class="text-center text-muted">No registrations yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($latestRegs as $r): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($r['full_name']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($r['student_number']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($r['event_title']) ?></td>
                                <td><span class="badge badge-success"><?= htmlspecialchars($r['status']) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Audit Logs (Practical 14 Traceability) -->
    <div class="card p-0" style="overflow:hidden;">
        <div style="padding:1rem 1.25rem; border-bottom:1px solid var(--border-color); display:flex; justify-content:space-between; align-items:center;">
            <h3 class="card-title" style="margin:0; font-size:1.1rem;">Security Audit Activity</h3>
            <a href="audit-logs.php" style="font-size:0.85rem; text-decoration:none;">View All &rarr;</a>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($latestLogs)): ?>
                        <tr><td colspan="4" class="text-center text-muted">No audit logs recorded.</td></tr>
                    <?php else: ?>
                        <?php foreach ($latestLogs as $log): ?>
                            <tr>
                                <td><small class="text-muted"><?= date('H:i:s', strtotime($log['created_at'])) ?></small></td>
                                <td><code><?= htmlspecialchars($log['username']) ?></code></td>
                                <td><span class="badge badge-secondary"><?= htmlspecialchars($log['action']) ?></span></td>
                                <td><small><?= htmlspecialchars($log['details']) ?></small></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
