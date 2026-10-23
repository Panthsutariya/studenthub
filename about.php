<?php
/**
 * Practical 2 & Practical 15: About Page
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

$pageTitle = 'About - StudentHub Portal';
$activePage = 'about';
$breadcrumbs = ['About' => ''];
require_once __DIR__ . '/includes/header.php';
?>

<div class="card p-4 mb-4">
    <h1 class="card-title" style="font-size:2rem; margin-bottom:0.75rem;">About StudentHub Portal</h1>
    <p class="text-muted" style="font-size:1.05rem; line-height:1.7;">
        StudentHub is an integrated, semester-long web application engineered for the <strong>Charotar University of Science and Technology (CHARUSAT)</strong>, Faculty of Technology and Engineering (FTE). Developed under course <strong>ITUE203 (Web Development Frameworks)</strong>, this application implements full-stack web engineering concepts from client-side accessibility and responsive layouts to server-side authentication, RESTful APIs, and relational database persistence.
    </p>
</div>

<div class="grid-2 mb-4">
    <div class="card">
        <h2 class="card-title" style="font-size:1.3rem;">🏗️ Architectural Stack</h2>
        <ul style="padding-left:1.25rem; line-height:1.8; margin-top:0.75rem;">
            <li><strong>Frontend Presentation:</strong> Semantic HTML5, CSS3 Grid & Flexbox, CSS Variables for Dark/Light theme.</li>
            <li><strong>Client Logic:</strong> Vanilla Modern JavaScript (ES6+), DOM Manipulation, Fetch API, Canvas CAPTCHA, LocalStorage caching.</li>
            <li><strong>Backend Processing:</strong> PHP 8.x (Strict Types, Session Management, RBAC Middleware).</li>
            <li><strong>Database Persistence:</strong> MySQL 8.x / MariaDB, Normalized schema, InnoDB transactions, Foreign Keys, MySQLi & PDO prepared statements.</li>
            <li><strong>Security Practices:</strong> Bcrypt password hashing, CSRF tokens, strict session regeneration, 15-minute inactivity timeout, MIME-type upload verification, and audit trails.</li>
        </ul>
    </div>

    <div class="card">
        <h2 class="card-title" style="font-size:1.3rem;">🎯 Course Learning Outcomes (COs)</h2>
        <ul style="padding-left:1.25rem; line-height:1.8; margin-top:0.75rem;">
            <li><strong>CO1:</strong> Formulate web application requirements, wireframes, and secure form validation architectures.</li>
            <li><strong>CO2:</strong> Construct responsive, accessible, multi-device layouts utilizing CSS Grid and Flexbox.</li>
            <li><strong>CO3:</strong> Engineer interactive client-side experiences utilizing DOM events, Fetch API, and asynchronous flows.</li>
            <li><strong>CO4:</strong> Implement RESTful JSON APIs and single-page asynchronous operations without full page reloads.</li>
            <li><strong>CO5:</strong> Develop secure database-driven backend modules with role-based access control, file uploads, and session management.</li>
        </ul>
    </div>
</div>

<div class="card p-4">
    <h2 class="card-title" style="font-size:1.3rem; margin-bottom:1rem;">📋 Complete Practical Implementation Index (1 to 15)</h2>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Practical Title</th>
                    <th>Module / Route</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>1</td><td>Project Initiation, Wireframes & GitHub Setup</td><td><code>README.md</code>, Wireframe assets, Git repository</td><td><span class="badge badge-success">Completed</span></td></tr>
                <tr><td>2</td><td>Semantic HTML5 with Accessibility-Ready Structure</td><td>Semantic layout, skip links, aria attributes, breadcrumbs</td><td><span class="badge badge-success">Completed</span></td></tr>
                <tr><td>3</td><td>Responsive UI Design (CSS Grid, Flexbox, Mobile-First)</td><td><code>css/style.css</code> (Mobile-first responsive grids)</td><td><span class="badge badge-success">Completed</span></td></tr>
                <tr><td>4</td><td>JavaScript DOM Manipulation & Interactivity</td><td><code>js/main.js</code> (Dark mode, modal, mobile drawer, accordion)</td><td><span class="badge badge-success">Completed</span></td></tr>
                <tr><td>5</td><td>Registration Form with Regex Validation & Errors</td><td><code>register.php</code>, <code>js/validation.js</code>, Canvas CAPTCHA</td><td><span class="badge badge-success">Completed</span></td></tr>
                <tr><td>6</td><td>External JSON Rendering using Fetch API</td><td><code>faqs.php</code>, <code>js/dynamic-lists.js</code>, 15+ JSON records</td><td><span class="badge badge-success">Completed</span></td></tr>
                <tr><td>7</td><td>PHP Form Processing & CSV/JSON File Storage</td><td><code>contact.php</code>, <code>contact-process.php</code>, <code>view-submissions.php</code></td><td><span class="badge badge-success">Completed</span></td></tr>
                <tr><td>8</td><td>MySQL Schema Design, PDO & MySQLi Connectivity</td><td><code>sql/studenthub.sql</code>, <code>db.php</code>, <code>db_pdo.php</code>, <code>test_connection.php</code></td><td><span class="badge badge-success">Completed</span></td></tr>
                <tr><td>9</td><td>Secure Registration with Bcrypt Hashing & DB Insert</td><td><code>register.php</code> (Bcrypt, duplicate checks, audit logging)</td><td><span class="badge badge-success">Completed</span></td></tr>
                <tr><td>10</td><td>Secure Login/Logout, Sessions, RBAC & Timeout</td><td><code>login.php</code>, <code>logout.php</code>, <code>includes/auth.php</code> (15m timeout)</td><td><span class="badge badge-success">Completed</span></td></tr>
                <tr><td>11</td><td>Student Management CRUD with Search & Filter</td><td><code>students.php</code>, <code>student-add.php</code>, <code>student-edit.php</code>, CSV export</td><td><span class="badge badge-success">Completed</span></td></tr>
                <tr><td>12</td><td>Event Management CRUD with Poster Upload & File Validation</td><td><code>events.php</code>, <code>event-add.php</code>, <code>uploads/events/</code></td><td><span class="badge badge-success">Completed</span></td></tr>
                <tr><td>13</td><td>PHP JSON API & AJAX CRUD without Page Reload</td><td><code>api/events.php</code>, <code>events-ajax.php</code> (Fetch API, debounce)</td><td><span class="badge badge-success">Completed</span></td></tr>
                <tr><td>14</td><td>Admin Dashboard with RBAC, Dynamic Menu & Audit Log</td><td><code>admin/dashboard.php</code>, <code>admin/audit-logs.php</code>, <code>admin/users.php</code></td><td><span class="badge badge-success">Completed</span></td></tr>
                <tr><td>15</td><td>Final Integration, Testing & Local Deployment</td><td>Unified application, master documentation, local deployment test</td><td><span class="badge badge-success">Completed</span></td></tr>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
