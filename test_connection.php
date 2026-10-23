<?php
/**
 * Practical 8: Test Database Connectivity (MySQLi & PDO)
 */
declare(strict_types=1);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connectivity Test - StudentHub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="card p-4 shadow-sm" style="max-width: 650px; margin: 0 auto;">
            <h2 class="mb-3 text-primary">Practical 8: Database Connection Test</h2>
            <hr>
            
            <h4 class="mt-3">1. MySQLi Connection Test:</h4>
            <?php
            try {
                require __DIR__ . '/db.php';
                if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
                    $res = $conn->query("SELECT DATABASE() AS current_db, VERSION() AS mysql_ver");
                    $row = $res->fetch_assoc();
                    echo '<div class="alert alert-success"><strong>Success!</strong> Connected via MySQLi.<br>';
                    echo 'Active Database: <code>' . htmlspecialchars($row['current_db'] ?? 'N/A') . '</code><br>';
                    echo 'MySQL Server Version: <code>' . htmlspecialchars($row['mysql_ver'] ?? 'N/A') . '</code></div>';
                } else {
                    echo '<div class="alert alert-danger">MySQLi connection failed. Check XAMPP MySQL.</div>';
                }
            } catch (Throwable $e) {
                echo '<div class="alert alert-danger"><strong>MySQLi Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            ?>

            <h4 class="mt-4">2. PDO Connection Test (Prepared Statements Ready):</h4>
            <?php
            try {
                require __DIR__ . '/db_pdo.php';
                if (isset($pdo) && $pdo instanceof PDO) {
                    $stmt = $pdo->query("SELECT COUNT(*) AS total_students FROM students");
                    $count = $stmt->fetchColumn();
                    echo '<div class="alert alert-success"><strong>Success!</strong> Connected via PHP PDO.<br>';
                    echo 'Total Students in DB: <strong>' . (int)$count . '</strong></div>';
                } else {
                    echo '<div class="alert alert-danger">PDO connection failed. Check XAMPP MySQL.</div>';
                }
            } catch (Throwable $e) {
                echo '<div class="alert alert-danger"><strong>PDO Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            ?>

            <div class="mt-4">
                <a href="index.php" class="btn btn-secondary">&larr; Return to Portal</a>
                <a href="students.php" class="btn btn-primary">Go to Student CRUD (PR-11)</a>
            </div>
        </div>
    </div>
</body>
</html>
