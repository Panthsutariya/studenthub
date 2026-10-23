<?php
/**
 * Practical 12: Event Management CRUD with Poster Upload & File Validation
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_admin();
require_once __DIR__ . '/includes/csrf.php';

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
            $errors[] = 'Event location is required.';
        }

        // File Upload Validation (Practical 12 Core Requirement)
        $posterPath = null;
        if (isset($_FILES['poster']) && $_FILES['poster']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['poster'];

            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errors[] = 'File upload failed with error code: ' . $file['error'];
            } else {
                // 1. File Size Validation: max 2MB (2097152 bytes)
                $maxBytes = 2 * 1024 * 1024;
                if ($file['size'] > $maxBytes) {
                    $errors[] = 'Poster file size exceeds the 2MB limit.';
                }

                // 2. MIME Type Validation using finfo
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($file['tmp_name']);
                $allowedMimeTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

                if (!array_key_exists($mimeType, $allowedMimeTypes)) {
                    $errors[] = 'Invalid image file type. Allowed formats: JPG, PNG, WEBP.';
                } else {
                    // 3. Image Renaming & Secure Storage (Practical 12 Extension)
                    $ext = $allowedMimeTypes[$mimeType];
                    $uniqueName = 'event_' . bin2hex(random_bytes(10)) . '.' . $ext;
                    $targetDir = __DIR__ . '/uploads/events';
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0755, true);
                    }
                    $targetPath = $targetDir . '/' . $uniqueName;

                    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                        $posterPath = 'uploads/events/' . $uniqueName;
                    } else {
                        $errors[] = 'Failed to save uploaded poster file.';
                    }
                }
            }
        }

        if (empty($errors)) {
            try {
                $userId = (int)$_SESSION['user_id'];
                $stmt = $conn->prepare("INSERT INTO events (title, description, category, event_date, location, capacity, poster, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssissi", $title, $description, $category, $eventDate, $location, $capacity, $posterPath, $status, $userId);
                $stmt->execute();
                $eventId = $conn->insert_id;
                $stmt->close();

                log_audit($conn, 'CREATE_EVENT', "Admin created event ID {$eventId}: {$title}");

                header('Location: events.php?success=' . urlencode("Event '{$title}' created successfully."));
                exit;
            } catch (mysqli_sql_exception $e) {
                $errors[] = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

$pageTitle = 'Add New Event - Practical 12';
$activePage = 'events';
$breadcrumbs = ['Events' => 'events.php', 'Add Event' => ''];
require_once __DIR__ . '/includes/header.php';
?>

<div class="card p-4" style="max-width: 750px; margin: 0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <h1 class="card-title" style="font-size:1.5rem; margin:0;">Create New Campus Event</h1>
        <a href="events.php" class="btn btn-outline btn-sm">&larr; Back to Events</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul style="margin:0; padding-left:1.25rem;">
                <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="event-add.php" method="POST" enctype="multipart/form-data">
        <?= csrf_input_field() ?>

        <div class="form-group">
            <label class="form-label" for="title">Event Title *</label>
            <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" placeholder="e.g. CHARUSAT Hackathon 2026" required>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label class="form-label" for="category">Category *</label>
                <select id="category" name="category" class="form-control" required>
                    <option value="Technical">Technical</option>
                    <option value="Cultural">Cultural</option>
                    <option value="Sports">Sports</option>
                    <option value="Workshop">Workshop</option>
                    <option value="Seminar">Seminar</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="event_date">Date & Time *</label>
                <input type="datetime-local" id="event_date" name="event_date" class="form-control" value="<?= htmlspecialchars($_POST['event_date'] ?? '') ?>" required>
            </div>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label class="form-label" for="location">Venue / Location *</label>
                <input type="text" id="location" name="location" class="form-control" value="<?= htmlspecialchars($_POST['location'] ?? '') ?>" placeholder="e.g. Seminar Hall 1, FTE" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="capacity">Seat Capacity</label>
                <input type="number" id="capacity" name="capacity" class="form-control" value="<?= htmlspecialchars($_POST['capacity'] ?? '100') ?>" min="1" max="5000">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Event Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" placeholder="Enter event scope, schedule, prizes and prerequisites..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <!-- Poster Upload & Validation (Practical 12) -->
        <div class="form-group card p-3" style="background:var(--bg-main); border: 1px dashed var(--border-color);">
            <label class="form-label" for="poster">Event Poster Image (Max 2MB - JPG, PNG, WEBP)</label>
            <input type="file" id="poster" name="poster" class="form-control" accept="image/jpeg,image/png,image/webp">
            <small class="text-muted" style="margin-top:0.35rem; display:block;">
                Server performs MIME validation, size check, and random secure filename generation.
            </small>
        </div>

        <div class="form-group">
            <label class="form-label" for="status">Event Status</label>
            <select id="status" name="status" class="form-control" style="max-width:200px;">
                <option value="open">Open for Registration</option>
                <option value="closed">Closed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="padding: 0.7rem 1.8rem;">Publish Event &rarr;</button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
