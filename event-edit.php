<?php
/**
 * Practical 12: Edit Event with Poster Replacement
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_admin();
require_once __DIR__ . '/includes/csrf.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: events.php?error=Invalid event ID.');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM events WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$event) {
    header('Location: events.php?error=Event not found.');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Session security token expired. Please retry.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $category = trim($_POST['category'] ?? 'Technical');
        $eventDate = trim($_POST['event_date'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $capacity = (int)($_POST['capacity'] ?? 100);
        $status = in_array($_POST['status'] ?? '', ['open', 'closed', 'cancelled']) ? $_POST['status'] : 'open';

        if (empty($title) || strlen($title) < 3) {
            $errors[] = 'Event title must be at least 3 characters.';
        }
        if (empty($eventDate)) {
            $errors[] = 'Event date and time is required.';
        }
        if (empty($location)) {
            $errors[] = 'Location is required.';
        }

        $posterPath = $event['poster']; // Default to current poster

        // Check if new poster uploaded
        if (isset($_FILES['poster']) && $_FILES['poster']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['poster'];
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errors[] = 'Upload error: ' . $file['error'];
            } else {
                $maxBytes = 2 * 1024 * 1024;
                if ($file['size'] > $maxBytes) {
                    $errors[] = 'Poster file exceeds 2MB limit.';
                }

                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($file['tmp_name']);
                $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

                if (!array_key_exists($mimeType, $allowed)) {
                    $errors[] = 'Invalid file type. Allowed: JPG, PNG, WEBP.';
                } else {
                    $ext = $allowed[$mimeType];
                    $uniqueName = 'event_' . bin2hex(random_bytes(10)) . '.' . $ext;
                    $targetDir = __DIR__ . '/uploads/events';
                    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                    $targetPath = $targetDir . '/' . $uniqueName;

                    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                        // Unlink old poster if existed
                        if (!empty($event['poster']) && file_exists(__DIR__ . '/' . $event['poster'])) {
                            @unlink(__DIR__ . '/' . $event['poster']);
                        }
                        $posterPath = 'uploads/events/' . $uniqueName;
                    } else {
                        $errors[] = 'Failed to move uploaded poster file.';
                    }
                }
            }
        }

        if (empty($errors)) {
            try {
                $upStmt = $conn->prepare("UPDATE events SET title = ?, description = ?, category = ?, event_date = ?, location = ?, capacity = ?, poster = ?, status = ? WHERE id = ?");
                $upStmt->bind_param("sssssissi", $title, $description, $category, $eventDate, $location, $capacity, $posterPath, $status, $id);
                $upStmt->execute();
                $upStmt->close();

                log_audit($conn, 'UPDATE_EVENT', "Admin updated event ID {$id}: {$title}");

                header('Location: events.php?success=' . urlencode("Event '{$title}' updated successfully."));
                exit;
            } catch (mysqli_sql_exception $e) {
                $errors[] = 'Update failed: ' . $e->getMessage();
            }
        }
    }
}

$pageTitle = 'Edit Event - Practical 12';
$activePage = 'events';
$breadcrumbs = ['Events' => 'events.php', 'Edit Event' => ''];
require_once __DIR__ . '/includes/header.php';
?>

<div class="card p-4" style="max-width: 750px; margin: 0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <h1 class="card-title" style="font-size:1.5rem; margin:0;">Edit Event: <?= htmlspecialchars($event['title']) ?></h1>
        <a href="events.php" class="btn btn-outline btn-sm">&larr; Back to Events</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul style="margin:0; padding-left:1.25rem;">
                <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="event-edit.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_input_field() ?>

        <div class="form-group">
            <label class="form-label" for="title">Event Title *</label>
            <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($_POST['title'] ?? $event['title']) ?>" required>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label class="form-label" for="category">Category *</label>
                <select id="category" name="category" class="form-control" required>
                    <?php
                    $categories = ['Technical', 'Cultural', 'Sports', 'Workshop', 'Seminar'];
                    $currCat = $_POST['category'] ?? $event['category'];
                    foreach ($categories as $c) {
                        echo '<option value="' . htmlspecialchars($c) . '"' . ($currCat === $c ? ' selected' : '') . '>' . htmlspecialchars($c) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="event_date">Date & Time *</label>
                <?php $valDate = str_replace(' ', 'T', substr($event['event_date'], 0, 16)); ?>
                <input type="datetime-local" id="event_date" name="event_date" class="form-control" value="<?= htmlspecialchars($_POST['event_date'] ?? $valDate) ?>" required>
            </div>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label class="form-label" for="location">Venue / Location *</label>
                <input type="text" id="location" name="location" class="form-control" value="<?= htmlspecialchars($_POST['location'] ?? $event['location']) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="capacity">Seat Capacity</label>
                <input type="number" id="capacity" name="capacity" class="form-control" value="<?= htmlspecialchars($_POST['capacity'] ?? $event['capacity']) ?>" min="1" max="5000">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Event Description</label>
            <textarea id="description" name="description" class="form-control" rows="4"><?= htmlspecialchars($_POST['description'] ?? $event['description']) ?></textarea>
        </div>

        <div class="form-group card p-3" style="background:var(--bg-main); border: 1px dashed var(--border-color);">
            <label class="form-label" for="poster">Replace Poster Image (Max 2MB - JPG, PNG, WEBP)</label>
            <?php if (!empty($event['poster']) && file_exists(__DIR__ . '/' . $event['poster'])): ?>
                <p style="font-size:0.85rem; margin-bottom:0.5rem;">Current poster: <code><?= htmlspecialchars($event['poster']) ?></code></p>
            <?php endif; ?>
            <input type="file" id="poster" name="poster" class="form-control" accept="image/jpeg,image/png,image/webp">
        </div>

        <div class="form-group">
            <label class="form-label" for="status">Event Status</label>
            <select id="status" name="status" class="form-control" style="max-width:200px;">
                <?php $currStatus = $_POST['status'] ?? $event['status']; ?>
                <option value="open" <?= $currStatus === 'open' ? 'selected' : '' ?>>Open</option>
                <option value="closed" <?= $currStatus === 'closed' ? 'selected' : '' ?>>Closed</option>
                <option value="cancelled" <?= $currStatus === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="padding: 0.7rem 1.8rem;">Save Changes &rarr;</button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
