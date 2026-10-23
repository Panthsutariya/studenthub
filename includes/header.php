<?php
/**
 * Master Header Component
 * Practical 2 (Semantic HTML5 & Accessibility) & Practical 3 (Responsive Design)
 */
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/auth.php';

$pageTitle = $pageTitle ?? 'StudentHub - Academic & Campus Portal';
$activePage = $activePage ?? '';
$currentTheme = $_GET['theme'] ?? 'light';
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= htmlspecialchars($currentTheme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="StudentHub - CHARUSAT Student Academic & Event Portal">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="/studenthub/css/style.css">
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <header class="site-header" role="banner">
        <div class="container header-inner">
            <a href="/studenthub/index.php" class="logo-brand">
                <span>🎓 StudentHub</span>
                <span class="logo-badge">CHARUSAT</span>
            </a>

            <button class="hamburger-btn" id="hamburger-btn" aria-label="Toggle navigation menu" aria-expanded="false">
                ☰
            </button>

            <nav class="nav-menu" id="nav-menu" role="navigation" aria-label="Main Navigation">
                <a href="/studenthub/index.php" class="<?= $activePage === 'home' ? 'active' : '' ?>">Home</a>
                <a href="/studenthub/about.php" class="<?= $activePage === 'about' ? 'active' : '' ?>">About</a>
                <a href="/studenthub/events.php" class="<?= $activePage === 'events' ? 'active' : '' ?>">Events</a>
                <a href="/studenthub/faqs.php" class="<?= $activePage === 'faqs' ? 'active' : '' ?>">FAQs</a>
                <a href="/studenthub/contact.php" class="<?= $activePage === 'contact' ? 'active' : '' ?>">Contact</a>

                <?php if (is_logged_in()): ?>
                    <?php if (is_admin()): ?>
                        <a href="/studenthub/admin/dashboard.php" class="<?= $activePage === 'admin' ? 'active' : '' ?>">Admin Panel</a>
                        <a href="/studenthub/students.php" class="<?= $activePage === 'students' ? 'active' : '' ?>">Students CRUD</a>
                        <a href="/studenthub/admin/audit-logs.php" class="<?= $activePage === 'audit' ? 'active' : '' ?>">Audit Logs</a>
                    <?php else: ?>
                        <a href="/studenthub/profile.php" class="<?= $activePage === 'profile' ? 'active' : '' ?>">Profile</a>
                        <a href="/studenthub/feedback.php" class="<?= $activePage === 'feedback' ? 'active' : '' ?>">Feedback</a>
                    <?php endif; ?>
                <?php endif; ?>
            </nav>

            <div class="nav-actions">
                <button type="button" class="theme-toggle-btn" id="theme-toggle" aria-label="Toggle dark mode">
                    🌙 Dark
                </button>

                <?php if (is_logged_in()): ?>
                    <span class="badge <?= is_admin() ? 'badge-danger' : 'badge-primary' ?>" style="font-size:0.8rem;">
                        <?= htmlspecialchars(current_username()) ?> (<?= strtoupper(current_user_role()) ?>)
                    </span>
                    <a href="/studenthub/logout.php" class="btn btn-danger btn-sm">Logout</a>
                <?php else: ?>
                    <a href="/studenthub/login.php" class="btn btn-outline btn-sm" style="color:#fff; border-color:rgba(255,255,255,0.4);">Login</a>
                    <a href="/studenthub/register.php" class="btn btn-primary btn-sm">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <?php if (isset($breadcrumbs) && is_array($breadcrumbs)): ?>
    <div class="breadcrumb-container" aria-label="Breadcrumb">
        <div class="container">
            <ol class="breadcrumb-list">
                <li><a href="/studenthub/index.php">Home</a></li>
                <?php foreach ($breadcrumbs as $name => $link): ?>
                    <?php if ($link): ?>
                        <li><a href="<?= htmlspecialchars($link) ?>"><?= htmlspecialchars($name) ?></a></li>
                    <?php else: ?>
                        <li><span aria-current="page"><?= htmlspecialchars($name) ?></span></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>
    <?php endif; ?>

    <main id="main-content" class="main-content" role="main">
        <div class="container">
            <?php if (!empty($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible" role="status">
                    <span><?= htmlspecialchars($_GET['success']) ?></span>
                    <button type="button" class="btn-sm alert-close" style="background:none; border:none; cursor:pointer; font-weight:bold;">&times;</button>
                </div>
            <?php endif; ?>
            <?php if (!empty($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <span><?= htmlspecialchars($_GET['error']) ?></span>
                    <button type="button" class="btn-sm alert-close" style="background:none; border:none; cursor:pointer; font-weight:bold;">&times;</button>
                </div>
            <?php endif; ?>
