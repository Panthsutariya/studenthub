<?php
/**
 * Practical 6: FAQs Dynamic List View using Fetch API
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);

$pageTitle = 'Frequently Asked Questions - Practical 6';
$activePage = 'faqs';
$breadcrumbs = ['FAQs' => ''];
require_once __DIR__ . '/includes/header.php';
?>

<div style="margin-bottom:1.5rem;">
    <h1 class="card-title" style="font-size:1.75rem; margin-bottom:0.35rem;">Frequently Asked Questions</h1>
    <p class="text-muted">Explore academic regulations, examination policies, hostel guidelines, and IT services.</p>
</div>

<!-- Dynamic Container bound to data/faqs.json via Fetch API -->
<div id="faqExplorerContainer" data-json="/studenthub/data/faqs.json" data-list-type="faq" data-page-size="6"></div>

<?php 
$extraScripts = ['/studenthub/js/dynamic-lists.js'];
require_once __DIR__ . '/includes/footer.php'; 
?>
