<?php
/**
 * Practical 2 & 7: Feedback Form
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';

$message = '';
$msgType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $message = 'Session security token expired. Please try again.';
        $msgType = 'danger';
    } else {
        $category = trim($_POST['category'] ?? 'Academics');
        $rating = (int)($_POST['rating'] ?? 5);
        $comments = trim($_POST['comments'] ?? '');
        $username = current_username();

        if (empty($comments) || strlen($comments) < 5) {
            $message = 'Please provide constructive feedback comments (min 5 characters).';
            $msgType = 'danger';
        } else {
            $record = [
                'id' => uniqid('fb_', true),
                'timestamp' => date('Y-m-d H:i:s'),
                'username' => $username,
                'category' => $category,
                'rating' => $rating,
                'comments' => htmlspecialchars($comments, ENT_QUOTES, 'UTF-8')
            ];

            $feedbackFile = __DIR__ . '/data/submissions/feedback-submissions.json';
            $fp = @fopen($feedbackFile, 'c+');
            if ($fp && flock($fp, LOCK_EX)) {
                $content = stream_get_contents($fp);
                $data = $content ? json_decode($content, true) : [];
                $data[] = $record;
                ftruncate($fp, 0);
                rewind($fp);
                fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
                fflush($fp);
                flock($fp, LOCK_UN);
                fclose($fp);

                if (isset($conn) && $conn instanceof mysqli) {
                    log_audit($conn, 'FEEDBACK_SUBMITTED', "User {$username} submitted feedback on {$category}");
                }

                $message = 'Thank you for your valuable feedback! Your response has been recorded.';
                $msgType = 'success';
            } else {
                $message = 'Could not record feedback due to file lock error.';
                $msgType = 'danger';
            }
        }
    }
}

$pageTitle = 'Student Feedback - Practical 2 & 7';
$activePage = 'feedback';
$breadcrumbs = ['Feedback' => ''];
require_once __DIR__ . '/includes/header.php';
?>

<div class="card p-4" style="max-width:650px; margin:0 auto;">
    <h1 class="card-title" style="font-size:1.6rem; margin-bottom:0.35rem;">Campus Feedback & Suggestions</h1>
    <p class="text-muted" style="margin-bottom:1.5rem; font-size:0.92rem;">
        Help us enhance student life and academic infrastructure at CHARUSAT.
    </p>

    <?php if ($message): ?>
        <div class="alert alert-<?= $msgType ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form action="feedback.php" method="POST">
        <?= csrf_input_field() ?>

        <div class="form-group">
            <label class="form-label" for="category">Feedback Category</label>
            <select id="category" name="category" class="form-control" required>
                <option value="Academics">Academics & Curriculum</option>
                <option value="Laboratories">Laboratory Facilities & Computers</option>
                <option value="Campus Facilities">Hostels, Canteen & Sports</option>
                <option value="Portal Features">StudentHub Web Portal Features</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label" for="rating">Satisfaction Rating (1 to 5 Stars)</label>
            <select id="rating" name="rating" class="form-control" required>
                <option value="5">⭐⭐⭐⭐⭐ 5 - Excellent</option>
                <option value="4">⭐⭐⭐⭐ 4 - Very Good</option>
                <option value="3">⭐⭐⭐ 3 - Good</option>
                <option value="2">⭐⭐ 2 - Fair</option>
                <option value="1">⭐ 1 - Needs Improvement</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label" for="comments">Suggestions & Detailed Feedback *</label>
            <textarea id="comments" name="comments" class="form-control" rows="5" placeholder="Share your suggestions, observed issues, or feature requests..." required minlength="5" maxlength="2000"></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="padding:0.65rem 1.75rem;">Submit Feedback &rarr;</button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
