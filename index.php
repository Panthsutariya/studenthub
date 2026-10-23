<?php
/**
 * Practical 15 & Master Landing: StudentHub Portal Home
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

$pageTitle = 'Home - StudentHub Portal | CHARUSAT';
$activePage = 'home';
require_once __DIR__ . '/includes/header.php';

// Quick dynamic stats from DB
$statsStudents = 0;
$statsEvents = 0;
try {
    if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
        $statsStudents = (int)$conn->query("SELECT COUNT(*) FROM students")->fetch_row()[0];
        $statsEvents = (int)$conn->query("SELECT COUNT(*) FROM events WHERE status='open'")->fetch_row()[0];
    }
} catch (Throwable $e) {}
?>

<!-- Hero Banner -->
<div class="card p-5 mb-4" style="background: linear-gradient(135deg, #1e3a8a 0%, #0284c7 100%); color:#ffffff; border:none; margin-bottom:2rem; border-radius:12px; box-shadow:var(--shadow-lg);">
    <div style="max-width:750px;">
        <span class="badge" style="background:rgba(255,255,255,0.2); color:#ffffff; margin-bottom:0.75rem;">CHARUSAT FTE &bull; ITUE203</span>
        <h1 style="font-size:2.5rem; font-weight:800; line-height:1.2; margin-bottom:1rem; color:#ffffff;">
            Welcome to StudentHub Campus Portal
        </h1>
        <p style="font-size:1.1rem; opacity:0.95; line-height:1.6; margin-bottom:1.5rem; color:#f0f9ff;">
            A centralized digital platform for Charotar University students to manage academic profiles, participate in campus events, explore resources, and access administrative services.
        </p>
        <div style="display:flex; gap:1rem; flex-wrap:wrap;">
            <?php if (is_logged_in()): ?>
                <?php if (is_admin()): ?>
                    <a href="admin/dashboard.php" class="btn btn-primary" style="background:#ffffff; color:#1e3a8a; font-weight:bold;">Go to Admin Dashboard &rarr;</a>
                <?php else: ?>
                    <a href="profile.php" class="btn btn-primary" style="background:#ffffff; color:#1e3a8a; font-weight:bold;">View My Profile &rarr;</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary" style="background:#ffffff; color:#1e3a8a; font-weight:bold;">Sign In to Portal</a>
                <a href="register.php" class="btn btn-outline" style="border-color:rgba(255,255,255,0.8); color:#ffffff;">New Student Registration</a>
            <?php endif; ?>
            <a href="events.php" class="btn btn-outline" style="border-color:rgba(255,255,255,0.8); color:#ffffff;">Browse Events</a>
        </div>
    </div>
</div>

<!-- Key Features Grid (Practicals 2, 3, 4) -->
<div class="grid-3 mb-4" style="margin-bottom:2rem;">
    <div class="card">
        <div style="font-size:2rem; margin-bottom:0.5rem;">📅</div>
        <h3 class="card-title" style="font-size:1.2rem;">Campus Events & Hackathons</h3>
        <p class="text-muted" style="font-size:0.9rem; margin-bottom:1rem;">
            Discover technical workshops, cultural celebrations, sports leagues, and register with one click.
        </p>
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <a href="events.php" style="font-size:0.9rem; font-weight:bold; text-decoration:none;">View Events &rarr;</a>
            <span class="badge badge-success"><?= $statsEvents ?> Open Now</span>
        </div>
    </div>

    <div class="card">
        <div style="font-size:2rem; margin-bottom:0.5rem;">👨‍🎓</div>
        <h3 class="card-title" style="font-size:1.2rem;">Student Directory & Records</h3>
        <p class="text-muted" style="font-size:0.9rem; margin-bottom:1rem;">
            Full database-backed directory with real-time search, multi-criteria filters, and CSV export.
        </p>
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <a href="students.php" style="font-size:0.9rem; font-weight:bold; text-decoration:none;">Search Directory &rarr;</a>
            <span class="badge badge-primary"><?= $statsStudents ?> Students</span>
        </div>
    </div>

    <div class="card">
        <div style="font-size:2rem; margin-bottom:0.5rem;">⚡</div>
        <h3 class="card-title" style="font-size:1.2rem;">Asynchronous REST API</h3>
        <p class="text-muted" style="font-size:0.9rem; margin-bottom:1rem;">
            Demonstrating modern AJAX Fetch API with zero page reloads, debounced search, and JSON endpoints.
        </p>
        <a href="events-ajax.php" style="font-size:0.9rem; font-weight:bold; text-decoration:none;">Launch AJAX CRUD &rarr;</a>
    </div>
</div>

<!-- Dynamic FAQ & Information Section (Practical 6 Fetch API Demonstration) -->
<div class="card p-4 mb-4" style="margin-bottom:2rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:0.5rem;">
        <div>
            <h2 class="card-title" style="margin:0; font-size:1.4rem;">Frequently Asked Questions (Fetch API)</h2>
            <p class="text-muted" style="margin:0; font-size:0.85rem;">Consuming external <code>data/faqs.json</code> using JavaScript Fetch with search, filter, and pagination.</p>
        </div>
        <a href="faqs.php" class="btn btn-outline btn-sm">Full FAQ Explorer &raquo;</a>
    </div>

    <div id="homeFaqContainer" data-json="/studenthub/data/faqs.json" data-list-type="faq" data-page-size="3"></div>
</div>

<!-- Laboratory Notice Board Modal Trigger (Practical 4) -->
<div class="card p-4 text-center" style="margin-bottom:2rem; background:var(--bg-card); border-style:dashed;">
    <h3 class="card-title" style="font-size:1.2rem;">Official Campus Bulletin & Notice Board</h3>
    <p class="text-muted" style="margin-bottom:1rem;">Check recent notifications regarding semester examinations, academic schedules, and lab submissions.</p>
    <div>
        <button type="button" class="btn btn-primary btn-sm" data-modal-target="noticeModal">📢 Open Bulletin Modal (DOM Popup)</button>
        <a href="contact.php" class="btn btn-outline btn-sm">Ask Question / Support</a>
    </div>
</div>

<!-- Notice Modal Popup (Practical 4 Requirement) -->
<div id="noticeModal" class="modal-backdrop" aria-hidden="true">
    <div class="modal-content">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <h3 class="card-title text-primary" style="margin:0;">📢 University Bulletin Notice</h3>
            <button type="button" class="btn-outline btn-sm" data-modal-close>&times;</button>
        </div>
        <div style="font-size:0.95rem; line-height:1.6;">
            <p><strong>Subject:</strong> Submission Schedule for ITUE203 Web Development Frameworks</p>
            <p class="text-muted">All FTE 3rd semester students are hereby instructed to submit Practicals 1 to 15 along with GitHub repository link and SQL database seeds.</p>
            <ul style="padding-left:1.25rem; margin:1rem 0; font-size:0.9rem;">
                <li>Ensure all forms use CSRF token protection.</li>
                <li>Verify password hashing using <code>password_hash()</code>.</li>
                <li>Test AJAX endpoints using Fetch API.</li>
            </ul>
        </div>
        <div style="text-align:right; margin-top:1.5rem;">
            <button type="button" class="btn btn-primary btn-sm" data-modal-close>Acknowledge & Close</button>
        </div>
    </div>
</div>

<?php 
$extraScripts = ['/studenthub/js/dynamic-lists.js'];
require_once __DIR__ . '/includes/footer.php'; 
?>
