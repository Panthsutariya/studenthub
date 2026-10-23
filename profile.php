<?php
/**
 * Practical 2, 10, 11: Student Profile Page
 * Displays authenticated user profile and enrolled event registrations
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_login(); // Redirects to login.php if guest

$userId = (int)$_SESSION['user_id'];

// Retrieve user and student details
$stmt = $conn->prepare("
    SELECT u.username, u.email as user_email, u.role, u.status as user_status, u.last_login, u.created_at as joined_date,
           s.id as student_id, s.student_number, s.full_name, s.mobile, s.branch, s.year, s.gender, s.status as student_status
    FROM users u
    LEFT JOIN students s ON s.user_id = u.id
    WHERE u.id = ? LIMIT 1
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$profile = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Retrieve student's registered events
$myEvents = [];
if (!empty($profile['student_id'])) {
    $stId = (int)$profile['student_id'];
    $stmtEv = $conn->prepare("
        SELECT r.id as reg_id, r.registered_at, r.status as reg_status, e.id as event_id, e.title, e.category, e.event_date, e.location
        FROM registrations r
        JOIN events e ON r.event_id = e.id
        WHERE r.student_id = ?
        ORDER BY e.event_date ASC
    ");
    $stmtEv->bind_param("i", $stId);
    $stmtEv->execute();
    $myEvents = $stmtEv->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmtEv->close();
}

$pageTitle = 'My Profile - StudentHub';
$activePage = 'profile';
$breadcrumbs = ['Profile' => ''];
require_once __DIR__ . '/includes/header.php';
?>

<div class="grid-3 mb-4">
    <!-- User Card -->
    <div class="card" style="text-align:center;">
        <div style="width:90px; height:90px; border-radius:50%; background:linear-gradient(135deg, #1e40af, #0284c7); color:#fff; display:flex; align-items:center; justify-content:center; font-size:2.5rem; margin:0 auto 1rem;">
            <?= strtoupper(substr($profile['username'] ?? 'U', 0, 1)) ?>
        </div>
        <h2 class="card-title" style="margin-bottom:0.25rem;">
            <?= htmlspecialchars($profile['full_name'] ?? $profile['username']) ?>
        </h2>
        <p class="text-muted" style="font-size:0.9rem; margin-bottom:0.75rem;">
            <code><?= htmlspecialchars($profile['student_number'] ?? 'Admin/Staff') ?></code>
        </p>
        <div>
            <span class="badge <?= $profile['role'] === 'admin' ? 'badge-danger' : 'badge-primary' ?>">
                <?= strtoupper(htmlspecialchars($profile['role'])) ?>
            </span>
            <span class="badge badge-success">
                <?= ucfirst(htmlspecialchars($profile['user_status'])) ?>
            </span>
        </div>
        <hr style="border:0; border-top:1px solid var(--border-color); margin:1.25rem 0;">
        <p class="text-muted" style="font-size:0.8rem; text-align:left;">
            <strong>Member Since:</strong> <?= date('d M Y', strtotime($profile['joined_date'])) ?><br>
            <strong>Last Login:</strong> <?= $profile['last_login'] ? date('d M Y, h:i A', strtotime($profile['last_login'])) : 'Current Session' ?>
        </p>
    </div>

    <!-- Academic Information -->
    <div class="card" style="grid-column: span 2;">
        <h3 class="card-title" style="font-size:1.3rem; margin-bottom:1rem;">Academic & Contact Particulars</h3>
        <div class="grid-2">
            <div>
                <p class="text-muted" style="font-size:0.85rem; margin-bottom:0.2rem;">Full Name</p>
                <p><strong><?= htmlspecialchars($profile['full_name'] ?? 'Not Linked') ?></strong></p>
            </div>
            <div>
                <p class="text-muted" style="font-size:0.85rem; margin-bottom:0.2rem;">University ID</p>
                <p><strong><?= htmlspecialchars($profile['student_number'] ?? 'N/A') ?></strong></p>
            </div>
            <div>
                <p class="text-muted" style="font-size:0.85rem; margin-bottom:0.2rem;">Official Email</p>
                <p><strong><?= htmlspecialchars($profile['user_email']) ?></strong></p>
            </div>
            <div>
                <p class="text-muted" style="font-size:0.85rem; margin-bottom:0.2rem;">Contact Number</p>
                <p><strong><?= htmlspecialchars($profile['mobile'] ?? 'N/A') ?></strong></p>
            </div>
            <div>
                <p class="text-muted" style="font-size:0.85rem; margin-bottom:0.2rem;">Academic Department</p>
                <p><strong><?= htmlspecialchars($profile['branch'] ?? 'Faculty of Technology and Engineering') ?></strong></p>
            </div>
            <div>
                <p class="text-muted" style="font-size:0.85rem; margin-bottom:0.2rem;">Academic Term</p>
                <p><strong><?= htmlspecialchars($profile['year'] ?? '3rd Year') ?></strong></p>
            </div>
        </div>

        <div style="margin-top:1.5rem;">
            <a href="contact.php" class="btn btn-outline btn-sm">Request Detail Change</a>
            <a href="logout.php" class="btn btn-danger btn-sm">Log Out</a>
        </div>
    </div>
</div>

<!-- Enrolled Events Section -->
<div class="card p-0" style="overflow:hidden;">
    <div style="padding:1rem 1.25rem; border-bottom:1px solid var(--border-color); display:flex; justify-content:space-between; align-items:center;">
        <h3 class="card-title" style="margin:0; font-size:1.15rem;">My Registered Campus Events</h3>
        <a href="events.php" class="btn btn-outline btn-sm">Browse More Events &rarr;</a>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Category</th>
                    <th>Date & Time</th>
                    <th>Venue</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($myEvents)): ?>
                    <tr>
                        <td colspan="5" style="text-align:center; padding:2rem;" class="text-muted">
                            You have not registered for any campus events yet.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($myEvents as $ev): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($ev['title']) ?></strong></td>
                            <td><span class="badge badge-primary"><?= htmlspecialchars($ev['category']) ?></span></td>
                            <td><small><?= date('d M Y, h:i A', strtotime($ev['event_date'])) ?></small></td>
                            <td><?= htmlspecialchars($ev['location']) ?></td>
                            <td><span class="badge badge-success"><?= ucfirst(htmlspecialchars($ev['reg_status'])) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
