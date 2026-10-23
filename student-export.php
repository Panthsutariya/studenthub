<?php
/**
 * Practical 11 Intermediate Extension: Export Students to CSV
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_login();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=students_export_' . date('Y-m-d') . '.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Student Number', 'Full Name', 'Email', 'Mobile', 'Branch', 'Year', 'Gender', 'Status', 'Registered At']);

$res = $conn->query("SELECT id, student_number, full_name, email, mobile, branch, year, gender, status, created_at FROM students ORDER BY id ASC");
while ($row = $res->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['student_number'],
        $row['full_name'],
        $row['email'],
        $row['mobile'],
        $row['branch'],
        $row['year'],
        $row['gender'],
        $row['status'],
        $row['created_at']
    ]);
}
fclose($output);
exit;
