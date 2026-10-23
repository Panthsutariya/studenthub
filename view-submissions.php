<?php
/**
 * Practical 7 Extension: Display Stored CSV / JSON Submissions
 */
declare(strict_types=1);

$pageTitle = 'Stored Submissions (JSON & CSV) - Practical 7';
$activePage = 'contact';
$breadcrumbs = ['Contact' => 'contact.php', 'Stored Submissions' => ''];
require_once __DIR__ . '/includes/header.php';

$jsonPath = __DIR__ . '/data/submissions/contact-submissions.json';
$csvPath  = __DIR__ . '/data/submissions/contact-submissions.csv';

$jsonRecords = [];
if (file_exists($jsonPath)) {
    $content = file_get_contents($jsonPath);
    $decoded = json_decode($content, true);
    if (is_array($decoded)) {
        $jsonRecords = $decoded;
    }
}

$csvRecords = [];
if (file_exists($csvPath)) {
    if (($handle = fopen($csvPath, "r")) !== false) {
        $header = fgetcsv($handle);
        while (($row = fgetcsv($handle)) !== false) {
            $csvRecords[] = $row;
        }
        fclose($handle);
    }
}
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
    <div>
        <h1 class="card-title" style="font-size:1.75rem;">Practical 7: File-Based Submissions Viewer</h1>
        <p class="text-muted">Review server-side validated form responses written safely to CSV & JSON file formats.</p>
    </div>
    <div>
        <a href="contact.php" class="btn btn-outline">&larr; Back to Contact Form</a>
    </div>
</div>

<div class="card mb-4" style="margin-bottom: 2rem;">
    <h3 class="card-title">1. Records Stored in JSON Format (<code>contact-submissions.json</code>)</h3>
    <p class="text-muted" style="font-size:0.9rem;">Total entries: <?= count($jsonRecords) ?></p>
    <?php if (empty($jsonRecords)): ?>
        <div class="alert alert-info">No JSON records found yet. Submit the contact form to generate one.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Category</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_reverse($jsonRecords) as $rec): ?>
                        <tr>
                            <td><small><?= htmlspecialchars($rec['timestamp'] ?? '') ?></small></td>
                            <td><strong><?= htmlspecialchars($rec['name'] ?? '') ?></strong></td>
                            <td><?= htmlspecialchars($rec['email'] ?? '') ?></td>
                            <td><span class="badge badge-primary"><?= htmlspecialchars($rec['category'] ?? '') ?></span></td>
                            <td><?= htmlspecialchars($rec['message'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <h3 class="card-title">2. Records Stored in CSV Format (<code>contact-submissions.csv</code>)</h3>
    <p class="text-muted" style="font-size:0.9rem;">Total entries: <?= count($csvRecords) ?></p>
    <?php if (empty($csvRecords)): ?>
        <div class="alert alert-info">No CSV records found yet.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Timestamp</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Category</th>
                        <th>Message</th>
                        <th>Client IP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_reverse($csvRecords) as $r): ?>
                        <tr>
                            <td><small><?= htmlspecialchars($r[0] ?? '') ?></small></td>
                            <td><small><?= htmlspecialchars($r[1] ?? '') ?></small></td>
                            <td><strong><?= htmlspecialchars($r[2] ?? '') ?></strong></td>
                            <td><?= htmlspecialchars($r[3] ?? '') ?></td>
                            <td><span class="badge badge-secondary"><?= htmlspecialchars($r[4] ?? '') ?></span></td>
                            <td><?= htmlspecialchars($r[5] ?? '') ?></td>
                            <td><code><?= htmlspecialchars($r[6] ?? '') ?></code></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
