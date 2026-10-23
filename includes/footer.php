<?php
/**
 * Master Footer Component
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
declare(strict_types=1);
?>
        </div><!-- /.container -->
    </main><!-- /#main-content -->

    <footer class="site-footer" role="contentinfo">
        <div class="container footer-inner">
            <div>
                <strong>StudentHub Portal</strong> &bull; FTE, CHARUSAT University<br>
                <small class="text-muted" style="color:rgba(255,255,255,0.7);">Course: ITUE203 Web Development Frameworks | Practicals 1 to 15 Integrated</small>
            </div>
            <div style="display:flex; gap: 1rem; align-items:center;">
                <a href="/studenthub/faqs.php" style="color:#e2e8f0; text-decoration:none;">FAQs</a>
                <a href="/studenthub/contact.php" style="color:#e2e8f0; text-decoration:none;">Support</a>
                <a href="/studenthub/test_connection.php" style="color:#93c5fd; text-decoration:none;">DB Status</a>
                <span style="color:rgba(255,255,255,0.5);">&copy; <?= date('Y') ?> CHARUSAT</span>
            </div>
        </div>
    </footer>

    <script src="/studenthub/js/main.js"></script>
    <?php if (isset($extraScripts) && is_array($extraScripts)): ?>
        <?php foreach ($extraScripts as $script): ?>
            <script src="<?= htmlspecialchars($script) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
