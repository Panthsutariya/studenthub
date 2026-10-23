<?php
/**
 * Practical 12: Event Management CRUD & Browse Catalog
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';

$categoryFilter = trim($_GET['category'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');
$search = trim($_GET['search'] ?? '');

$where = [];
$params = [];
$types = '';

if ($search !== '') {
    $where[] = "(title LIKE ? OR description LIKE ? OR location LIKE ?)";
    $like = "%{$search}%";
    $params[] = $like; $params[] = $like; $params[] = $like;
    $types .= 'sss';
}
if ($categoryFilter !== '') {
    $where[] = "category = ?";
    $params[] = $categoryFilter;
    $types .= 's';
}
if ($statusFilter !== '') {
    $where[] = "status = ?";
    $params[] = $statusFilter;
    $types .= 's';
}

$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
$query = "SELECT e.*, 
          (SELECT COUNT(*) FROM registrations r WHERE r.event_id = e.id AND r.status != 'cancelled') AS registered_count
          FROM events e {$whereSql} ORDER BY e.event_date ASC";

$stmt = $conn->prepare($query);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$events = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$pageTitle = 'Campus Events - Practical 12';
$activePage = 'events';
$breadcrumbs = ['Events' => ''];
require_once __DIR__ . '/includes/header.php';
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
    <div>
        <h1 class="card-title" style="font-size:1.75rem; margin:0;">Campus Events & Competitions</h1>
        <p class="text-muted">Explore upcoming technical hackathons, cultural festivals, workshops, and seminars.</p>
    </div>
    <div style="display:flex; gap:0.5rem;">
        <a href="events-ajax.php" class="btn btn-outline btn-sm">⚡ Asynchronous AJAX CRUD (PR-13)</a>
        <?php if (is_admin()): ?>
            <a href="event-add.php" class="btn btn-primary btn-sm">+ Add New Event</a>
        <?php endif; ?>
    </div>
</div>

<!-- Category & Status Filter Bar (Practical 12 Extension) -->
<div class="card p-3 mb-4" style="margin-bottom:1.5rem;">
    <form method="GET" action="events.php" style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end;">
        <div style="flex:2; min-width:200px;">
            <label class="form-label" for="search">Keyword Search</label>
            <input type="search" id="search" name="search" class="form-control" placeholder="Search event title, venue..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <div style="flex:1; min-width:160px;">
            <label class="form-label" for="category">Category</label>
            <select id="category" name="category" class="form-control">
                <option value="">All Categories</option>
                <?php
                $cats = ['Technical', 'Cultural', 'Sports', 'Workshop', 'Seminar'];
                foreach ($cats as $c) {
                    echo '<option value="' . htmlspecialchars($c) . '"' . ($categoryFilter === $c ? ' selected' : '') . '>' . htmlspecialchars($c) . '</option>';
                }
                ?>
            </select>
        </div>
        <div style="flex:1; min-width:140px;">
            <label class="form-label" for="status">Event Status</label>
            <select id="status" name="status" class="form-control">
                <option value="">All Statuses</option>
                <option value="open" <?= $statusFilter === 'open' ? 'selected' : '' ?>>Open</option>
                <option value="closed" <?= $statusFilter === 'closed' ? 'selected' : '' ?>>Closed</option>
                <option value="cancelled" <?= $statusFilter === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>
        <div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="events.php" class="btn btn-outline">Reset</a>
        </div>
    </form>
</div>

<!-- Events Grid -->
<?php if (empty($events)): ?>
    <div class="card p-4 text-center">
        <p class="text-muted">No events currently match your selected filters.</p>
        <a href="events.php" class="btn btn-outline btn-sm">Clear Filters</a>
    </div>
<?php else: ?>
    <div class="grid-3">
        <?php foreach ($events as $ev): ?>
            <div class="card" style="display:flex; flex-direction:column;">
                <?php if (!empty($ev['poster']) && file_exists(__DIR__ . '/' . $ev['poster'])): ?>
                    <img src="/studenthub/<?= htmlspecialchars($ev['poster']) ?>" alt="<?= htmlspecialchars($ev['title']) ?>" style="width:100%; height:180px; object-fit:cover; border-radius:6px; margin-bottom:1rem;">
                <?php else: ?>
                    <div style="width:100%; height:120px; background:linear-gradient(135deg, #1e3a8a, #0284c7); border-radius:6px; margin-bottom:1rem; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:bold; font-size:1.1rem;">
                        🎓 <?= htmlspecialchars($ev['category']) ?>
                    </div>
                <?php endif; ?>

                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                    <span class="badge badge-primary"><?= htmlspecialchars($ev['category']) ?></span>
                    <span class="badge <?= $ev['status'] === 'open' ? 'badge-success' : ($ev['status'] === 'closed' ? 'badge-warning' : 'badge-danger') ?>">
                        <?= ucfirst(htmlspecialchars($ev['status'])) ?>
                    </span>
                </div>

                <h3 class="card-title" style="font-size:1.15rem; margin-bottom:0.5rem;"><?= htmlspecialchars($ev['title']) ?></h3>
                
                <p class="text-muted" style="font-size:0.85rem; margin-bottom:0.35rem;">
                    <strong>📅 Date:</strong> <?= date('d M Y, h:i A', strtotime($ev['event_date'])) ?>
                </p>
                <p class="text-muted" style="font-size:0.85rem; margin-bottom:0.75rem;">
                    <strong>📍 Venue:</strong> <?= htmlspecialchars($ev['location']) ?>
                </p>

                <p style="font-size:0.9rem; margin-bottom:1rem; flex-grow:1;">
                    <?= nl2br(htmlspecialchars(substr($ev['description'] ?? '', 0, 140))) ?>...
                </p>

                <div style="font-size:0.85rem; margin-bottom:1rem; color:var(--text-muted);">
                    Seats Filled: <strong><?= $ev['registered_count'] ?></strong> / <?= $ev['capacity'] ?? 'Unlimited' ?>
                </div>

                <div style="margin-top:auto; display:flex; gap:0.5rem; flex-direction:column;">
                    <?php if (is_logged_in() && !is_admin() && $ev['status'] === 'open'): ?>
                        <form action="event-register.php" method="POST">
                            <?= csrf_input_field() ?>
                            <input type="hidden" name="event_id" value="<?= $ev['id'] ?>">
                            <button type="submit" class="btn btn-primary btn-sm" style="width:100%;">Register Now &rarr;</button>
                        </form>
                    <?php endif; ?>

                    <?php if (is_admin()): ?>
                        <div style="display:flex; gap:0.35rem;">
                            <a href="event-edit.php?id=<?= $ev['id'] ?>" class="btn btn-outline btn-sm" style="flex:1;">Edit</a>
                            <form action="event-delete.php" method="POST" style="flex:1;" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                <?= csrf_input_field() ?>
                                <input type="hidden" name="id" value="<?= $ev['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm" style="width:100%;">Delete</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
