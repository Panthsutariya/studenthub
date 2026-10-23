<?php
/**
 * Practical 12: Delete Event with poster cleanup
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_admin();
require_once __DIR__ . '/includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: events.php?error=Invalid request method.');
    exit;
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    header('Location: events.php?error=Security token expired.');
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    header('Location: events.php?error=Invalid event ID.');
    exit;
}

try {
    $stmt = $conn->prepare("SELECT title, poster FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $event = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($event) {
        if (!empty($event['poster']) && file_exists(__DIR__ . '/' . $event['poster'])) {
            @unlink(__DIR__ . '/' . $event['poster']);
        }
        $del = $conn->prepare("DELETE FROM events WHERE id = ?");
        $del->bind_param("i", $id);
        $del->execute();
        $del->close();

        log_audit($conn, 'DELETE_EVENT', "Admin deleted event ID {$id}: {$event['title']}");
        header('Location: events.php?success=' . urlencode("Event '{$event['title']}' deleted successfully."));
    } else {
        header('Location: events.php?error=Event not found.');
    }
} catch (mysqli_sql_exception $e) {
    header('Location: events.php?error=' . urlencode('Delete failed: ' . $e->getMessage()));
}
exit;
