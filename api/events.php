<?php
/**
 * Practical 13: RESTful PHP JSON API for Events
 * Methods: GET (List/Single), POST (Create), PUT (Update), DELETE (Delete)
 * Features: HTTP Status Codes, Centralized JSON Error Handler, Prepared Statements
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

define('API_REQUEST', true);
header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../includes/auth.php';

function sendJson(int $status, array $data): void {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

// Route handling based on HTTP Method
switch ($method) {
    case 'GET':
        handleGet($conn);
        break;
    case 'POST':
        handlePost($conn);
        break;
    case 'PUT':
        handlePut($conn);
        break;
    case 'DELETE':
        handleDelete($conn);
        break;
    default:
        sendJson(405, ['status' => 'error', 'message' => "Method {$method} not allowed."]);
}

function handleGet(mysqli $conn): void {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $search = trim($_GET['search'] ?? '');
    $category = trim($_GET['category'] ?? '');

    if ($id !== null) {
        $stmt = $conn->prepare("SELECT * FROM events WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $event = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($event) {
            sendJson(200, ['status' => 'success', 'data' => $event]);
        } else {
            sendJson(404, ['status' => 'error', 'message' => 'Event not found.']);
        }
    }

    $where = [];
    $params = [];
    $types = '';

    if ($search !== '') {
        $where[] = "(title LIKE ? OR description LIKE ? OR location LIKE ?)";
        $like = "%{$search}%";
        $params[] = $like; $params[] = $like; $params[] = $like;
        $types .= 'sss';
    }
    if ($category !== '') {
        $where[] = "category = ?";
        $params[] = $category;
        $types .= 's';
    }

    $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
    $query = "SELECT * FROM events {$whereSql} ORDER BY event_date ASC";

    $stmt = $conn->prepare($query);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $events = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    sendJson(200, [
        'status' => 'success',
        'count'  => count($events),
        'data'   => $events
    ]);
}

function handlePost(mysqli_sql_exception|mysqli $conn): void {
    // Check permission (Admin only)
    if (!is_admin()) {
        sendJson(403, ['status' => 'error', 'message' => 'Admin authorization required.']);
    }

    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    $title = trim($input['title'] ?? '');
    $category = trim($input['category'] ?? 'Technical');
    $eventDate = trim($input['event_date'] ?? '');
    $location = trim($input['location'] ?? '');
    $description = trim($input['description'] ?? '');
    $capacity = (int)($input['capacity'] ?? 100);
    $status = in_array($input['status'] ?? '', ['open', 'closed', 'cancelled']) ? $input['status'] : 'open';

    if (empty($title) || empty($eventDate) || empty($location)) {
        sendJson(400, ['status' => 'error', 'message' => 'Title, event_date, and location are required.']);
    }

    try {
        $userId = $_SESSION['user_id'] ?? 1;
        $stmt = $conn->prepare("INSERT INTO events (title, description, category, event_date, location, capacity, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssisi", $title, $description, $category, $eventDate, $location, $capacity, $status, $userId);
        $stmt->execute();
        $newId = $conn->insert_id;
        $stmt->close();

        log_audit($conn, 'API_CREATE_EVENT', "Created event ID {$newId} via JSON API: {$title}");

        sendJson(201, [
            'status'  => 'success',
            'message' => 'Event created successfully.',
            'event_id'=> $newId
        ]);
    } catch (Throwable $e) {
        sendJson(500, ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

function handlePut(mysqli $conn): void {
    if (!is_admin()) {
        sendJson(403, ['status' => 'error', 'message' => 'Admin authorization required.']);
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        sendJson(400, ['status' => 'error', 'message' => 'Valid JSON payload expected.']);
    }

    $id = (int)($input['id'] ?? 0);
    if ($id <= 0) {
        sendJson(400, ['status' => 'error', 'message' => 'Event ID is required for update.']);
    }

    $title = trim($input['title'] ?? '');
    $category = trim($input['category'] ?? 'Technical');
    $eventDate = trim($input['event_date'] ?? '');
    $location = trim($input['location'] ?? '');
    $description = trim($input['description'] ?? '');
    $capacity = (int)($input['capacity'] ?? 100);
    $status = in_array($input['status'] ?? '', ['open', 'closed', 'cancelled']) ? $input['status'] : 'open';

    if (empty($title) || empty($eventDate) || empty($location)) {
        sendJson(400, ['status' => 'error', 'message' => 'Title, event_date, and location are required.']);
    }

    try {
        $stmt = $conn->prepare("UPDATE events SET title = ?, description = ?, category = ?, event_date = ?, location = ?, capacity = ?, status = ? WHERE id = ?");
        $stmt->bind_param("sssssisi", $title, $description, $category, $eventDate, $location, $capacity, $status, $id);
        $stmt->execute();
        $stmt->close();

        log_audit($conn, 'API_UPDATE_EVENT', "Updated event ID {$id} via JSON API: {$title}");

        sendJson(200, [
            'status'  => 'success',
            'message' => "Event {$id} updated successfully."
        ]);
    } catch (Throwable $e) {
        sendJson(500, ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleDelete(mysqli $conn): void {
    if (!is_admin()) {
        sendJson(403, ['status' => 'error', 'message' => 'Admin authorization required.']);
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $id = (int)($input['id'] ?? ($_GET['id'] ?? 0));

    if ($id <= 0) {
        sendJson(400, ['status' => 'error', 'message' => 'Valid Event ID is required for deletion.']);
    }

    try {
        $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();

        if ($affected > 0) {
            log_audit($conn, 'API_DELETE_EVENT', "Deleted event ID {$id} via JSON API.");
            sendJson(200, ['status' => 'success', 'message' => "Event ID {$id} deleted successfully."]);
        } else {
            sendJson(404, ['status' => 'error', 'message' => 'Event not found or already deleted.']);
        }
    } catch (Throwable $e) {
        sendJson(500, ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
