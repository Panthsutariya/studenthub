<?php
/**
 * Practical 7: Contact Form with Server-side Processing
 */
declare(strict_types=1);

$pageTitle = 'Contact & Support - Practical 7';
$activePage = 'contact';
$breadcrumbs = ['Contact' => ''];
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/header.php';
?>

<div class="grid-2">
    <div>
        <h1 class="card-title" style="font-size: 1.75rem;">Get in Touch</h1>
        <p class="text-muted" style="margin-bottom: 1.5rem;">
            Have questions regarding campus admissions, course registrations, or technical queries? Send us a message below.
        </p>

        <div class="card p-4">
            <form action="contact-process.php" method="POST" id="contactForm">
                <?= csrf_input_field() ?>

                <div class="form-group">
                    <label class="form-label" for="name">Full Name *</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="e.g. Diya Sharma" required minlength="2" maxlength="100">
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Official Email Address *</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="e.g. diya.sharma@charusat.edu.in" required maxlength="191">
                </div>

                <div class="form-group">
                    <label class="form-label" for="category">Inquiry Category</label>
                    <select id="category" name="category" class="form-control">
                        <option value="General">General Inquiry</option>
                        <option value="Academics">Academics & Registration</option>
                        <option value="Examinations">Examinations & Results</option>
                        <option value="Hostels">Hostels & Facilities</option>
                        <option value="Technical">Portal Technical Issue</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="message">Your Message *</label>
                    <textarea id="message" name="message" class="form-control" rows="5" placeholder="Type your detailed question or feedback here..." required minlength="10" maxlength="5000"></textarea>
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <button type="submit" class="btn btn-primary">Send Message &rarr;</button>
                    <a href="view-submissions.php" class="btn btn-outline btn-sm">View Saved CSV/JSON &raquo;</a>
                </div>
            </form>
        </div>
    </div>

    <div>
        <div class="card p-4 mb-4" style="margin-bottom: 1.5rem;">
            <h3 class="card-title">Campus Information</h3>
            <p><strong>Charotar University of Science and Technology (CHARUSAT)</strong></p>
            <p class="text-muted">Faculty of Technology and Engineering (FTE)</p>
            <p class="text-muted">CHARUSAT Campus, Changa - 388421, Anand, Gujarat, India</p>
            <hr style="border:0; border-top: 1px solid var(--border-color); margin: 1rem 0;">
            <p><strong>📞 Contact Desk:</strong> +91-2697-265011 / 265021</p>
            <p><strong>✉️ Support Email:</strong> info@charusat.ac.in</p>
            <p><strong>🕒 Office Hours:</strong> Monday - Saturday, 9:00 AM to 5:00 PM</p>
        </div>

        <div class="card p-4">
            <h3 class="card-title">Practical 7 Highlights</h3>
            <ul style="padding-left: 1.25rem; font-size: 0.9rem; line-height: 1.8;">
                <li>Server-side validation and sanitization using PHP.</li>
                <li>Dual data persistence in <strong>JSON</strong> and <strong>CSV</strong> file formats.</li>
                <li>Exclusive write concurrency protection with <code>flock()</code>.</li>
                <li>Protection against Cross-Site Request Forgery (CSRF).</li>
            </ul>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
