<?php
/**
 * Generator script to create complete Word .doc submission file with all embedded screenshots
 */
declare(strict_types=1);

$imagesDir = __DIR__ . '/manual_images/';

function imgTag(string $filename, string $caption = '', string $maxWidth = '650px'): string {
    global $imagesDir;
    $path = $imagesDir . $filename;
    if (!file_exists($path)) {
        return '<p class="text-muted"><em>[Screenshot: ' . htmlspecialchars($caption) . ']</em></p>';
    }
    
    // Convert to base64 so Word doc is 100% self-contained
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $mime = ($ext === 'jpeg' || $ext === 'jpg') ? 'image/jpeg' : 'image/png';
    $data = base64_encode(file_get_contents($path));
    $base64 = "data:$mime;base64,$data";
    
    $html = '<div style="text-align:center; margin:15px 0;">';
    if ($caption) {
        $html .= '<p style="font-weight:bold; color:#1e3a8a; margin-bottom:5px; font-size:10pt;">' . htmlspecialchars($caption) . '</p>';
    }
    $html .= '<img src="' . $base64 . '" alt="' . htmlspecialchars($caption) . '" style="max-width:' . $maxWidth . '; width:100%; height:auto; border:1px solid #cbd5e1; border-radius:4px; box-shadow:0 2px 4px rgba(0,0,0,0.08);">';
    $html .= '</div>';
    return $html;
}

ob_start();
?>
<!DOCTYPE html>
<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
<head>
<meta charset="utf-8">
<title>StudentHub - ITUE203 Laboratory Manual (Practicals 1 to 15)</title>
<style>
  body {
    font-family: 'Segoe UI', Calibri, Arial, sans-serif;
    font-size: 11pt;
    line-height: 1.5;
    color: #1a1a1a;
    margin: 40px;
  }
  .cover-page {
    text-align: center;
    border: 3px double #1e3a8a;
    padding: 50px 30px;
    margin-bottom: 50px;
    page-break-after: always;
  }
  .univ-header {
    font-size: 18pt;
    font-weight: bold;
    color: #1e3a8a;
    text-transform: uppercase;
    margin-bottom: 5px;
  }
  .dept-header {
    font-size: 13pt;
    color: #334155;
    margin-bottom: 25px;
  }
  .report-title {
    font-size: 24pt;
    font-weight: 800;
    color: #0f172a;
    margin: 25px 0 10px 0;
  }
  .report-subtitle {
    font-size: 13.5pt;
    color: #0284c7;
    margin-bottom: 30px;
  }
  .student-box {
    margin: 30px auto;
    width: 85%;
    border: 1px solid #cbd5e1;
    background-color: #f8fafc;
    padding: 18px;
    text-align: left;
    border-radius: 6px;
  }
  .student-box table {
    width: 100%;
    border-collapse: collapse;
  }
  .student-box td {
    padding: 5px 10px;
    font-size: 11pt;
  }
  h1.practical-title {
    font-size: 15pt;
    color: #1e3a8a;
    border-bottom: 2px solid #1e3a8a;
    padding-bottom: 6px;
    margin-top: 40px;
    page-break-before: always;
  }
  h2.section-heading {
    font-size: 12pt;
    color: #0369a1;
    margin-top: 16px;
    margin-bottom: 6px;
    border-left: 4px solid #0369a1;
    padding-left: 8px;
  }
  p, li {
    font-size: 11pt;
    text-align: justify;
  }
  ul, ol {
    margin-top: 4px;
    margin-bottom: 10px;
    padding-left: 25px;
  }
  .aim-box {
    background-color: #f0fdf4;
    border-left: 5px solid #16a34a;
    padding: 10px 14px;
    margin: 12px 0;
    font-size: 11pt;
  }
  .aim-title {
    font-weight: bold;
    color: #15803d;
    margin-bottom: 3px;
  }
  .qa-box {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 10px 14px;
    margin: 10px 0;
    border-radius: 4px;
  }
  .qa-q {
    font-weight: bold;
    color: #0f172a;
    margin-bottom: 3px;
  }
  .qa-a {
    color: #334155;
    margin-bottom: 0;
  }
  .repo-footer {
    background-color: #f1f5f9;
    padding: 8px 12px;
    border-radius: 4px;
    font-size: 10pt;
    margin-top: 15px;
  }
  code {
    font-family: Consolas, monospace;
    background-color: #f1f5f9;
    padding: 2px 4px;
    border-radius: 3px;
    color: #b91c1c;
    font-size: 9.5pt;
  }
</style>
</head>
<body>

<!-- ================= COVER PAGE ================= -->
<div class="cover-page">
  <div class="univ-header">Charotar University of Science and Technology (CHARUSAT)</div>
  <div class="dept-header">Devang Patel Institute of Advance Technology and Research (DEPSTAR) / FTE<br>Department of Computer Science & Engineering / Information Technology</div>
  
  <hr style="border: 0; border-top: 2px solid #1e3a8a; margin: 20px 0;">
  
  <div class="report-title">LABORATORY PRACTICAL MANUAL</div>
  <div class="report-subtitle">Web Development Frameworks (ITUE203) &bull; Semester 3 (Odd 2026-27)</div>
  
  <p style="font-size: 12pt; font-weight: 600; margin-top: 15px;">
    Term Work Lab Manual Submission: Complete Practicals 1 to 15 with Screenshots
  </p>
  
  <div class="student-box">
    <table>
      <tr>
        <td style="width: 35%;"><strong>Project Portal:</strong></td>
        <td><strong>StudentHub Academic & Campus Portal</strong></td>
      </tr>
      <tr>
        <td><strong>Subject:</strong></td>
        <td>Web Development Frameworks (ITUE203)</td>
      </tr>
      <tr>
        <td><strong>Student Name:</strong></td>
        <td><strong>Jay Bhensdadiya</strong></td>
      </tr>
      <tr>
        <td><strong>Enrollment ID:</strong></td>
        <td><strong>23DCE128 / 23DCE426</strong></td>
      </tr>
      <tr>
        <td><strong>Class / Branch:</strong></td>
        <td>B.Tech Computer Engineering (2nd Year, 3rd Sem)</td>
      </tr>
      <tr>
        <td><strong>Institute:</strong></td>
        <td>DEPSTAR, CHARUSAT Campus, Changa</td>
      </tr>
      <tr>
        <td><strong>GitHub Repository:</strong></td>
        <td><code>https://github.com/Panthsutariya/studenthub</code></td>
      </tr>
    </table>
  </div>

  <p style="font-size: 10pt; color: #64748b; margin-top: 30px;">
    Academic Term 2026-27 &bull; CHARUSAT Campus, Changa - 388421
  </p>
</div>

<!-- ================= PRACTICAL 1 ================= -->
<h1 class="practical-title">Practical 1: Project Initiation, Requirement Analysis, Sitemap, Wireframe, and GitHub Setup</h1>

<div class="aim-box">
  <div class="aim-title">AIM:</div>
  Initiate the semester-long Student Hub portal by identifying the problem scope, user roles, key modules, navigation flow, and minimum 10 pages. Create a sitemap, low-fidelity wireframe, project folder structure, README file, and GitHub repository.
</div>

<h2 class="section-heading">1. Implementation Details</h2>
<p>
In this practical, the foundational structure of the StudentHub Portal was implemented using HTML5. The following key elements and tags were utilized:
</p>
<ul>
  <li><strong>Basic HTML Skeleton:</strong> Used <code>&lt;!DOCTYPE html&gt;</code>, <code>&lt;html&gt;</code>, <code>&lt;head&gt;</code>, and <code>&lt;body&gt;</code> to structure all 10 web pages.</li>
  <li><strong>Navigation:</strong> Implemented a consistent global navigation bar across all pages using unordered lists and list items with anchor tags for links.</li>
  <li><strong>Forms & Input:</strong> Created user authentication and contact pages (login.html, register.html, contact.html) using the <code>&lt;form&gt;</code> tag along with various input types (text, email, password) and button elements.</li>
  <li><strong>Content Organization:</strong> Used semantic tags like <code>&lt;header&gt;</code>, <code>&lt;main&gt;</code>, and <code>&lt;footer&gt;</code> and <code>&lt;div&gt;</code> containers to organize the dashboard.</li>
  <li><strong>Sitemap Generation:</strong> Created a <code>sitemap.xml</code> file using standard XML tags like <code>&lt;urlset&gt;</code>, <code>&lt;url&gt;</code>, <code>&lt;loc&gt;</code>, and <code>&lt;priority&gt;</code> to define the structure and search hierarchy.</li>
</ul>

<h2 class="section-heading">2. Evidence of Work (Sitemap & Wireframes)</h2>
<?= imgTag('image1.png', 'Sitemap: Hierarchical Page Navigation Architecture') ?>

<?= imgTag('wire1.png', 'Wireframe 1: Home Page', '650px', true, 'Wireframe 1: Student Dashboard - Quick Summary Statistics (CGPA, Attendance, Assignments)') ?>
<?= imgTag('wire2.png', 'Wireframe 2: Login Page', '650px', true, 'Wireframe 2: Login Page - Centered Authentication Card with Email and Password Fields') ?>
<?= imgTag('wire3.png', 'Wireframe 3: Student Profile', '650px', true, 'Wireframe 3: Registration / Sign-up Page - Two-Column Student Input Fields') ?>
<?= imgTag('wire4.png', 'Wireframe 4: Course Registration', '650px', true, 'Wireframe 4: Course Information - Semester Curriculum and Subject Cards') ?>
<?= imgTag('wire5.png', 'Wireframe 5: Time Table', '650px', true, 'Wireframe 5: Assignment Portal - Assessments Grid with Status Tags') ?>
<?= imgTag('wire6.png', 'Wireframe 6: Attendance Tracker', '650px', true, 'Wireframe 6: Attendance Information - Overall Attendance with 80% Rule Indicator') ?>
<?= imgTag('wire7.png', 'Wireframe 7: Exam Schedule', '650px', true, 'Wireframe 7: Results & Transcripts - GPA Summary Boxes and Grades Table') ?>
<?= imgTag('wire8.png', 'Wireframe 8: Hostels Info', '650px', true, 'Wireframe 8: Contact Us - Campus Address and Student Helpdesk Message Form') ?>
<?= imgTag('wire9.png', 'Wireframe 9: Notification Page', '650px', true, 'Wireframe 9: Student Profile - Student Avatar, Enrollment ID, and Academic Particulars') ?>
<?= imgTag('wire10.png', 'Wireframe 10: Logout Page', '650px', true, 'Wireframe 10: About Page - Technologies Used and Portal Vision') ?>

<h2 class="section-heading">3. Challenges Faced</h2>
<p>
During Practical 1, the primary challenge was translating the broad idea of a StudentHub into specific, actionable requirements without overcomplicating the initial project scope. Designing the wireframes in Draw.io presented difficulties in planning how to clearly display complex data, like the dashboard summaries and course tables, on a single screen. It was also challenging to ensure that the global navigation and UI layouts remained perfectly consistent across all 10 distinct page designs during the wireframing phase. When creating the sitemap, I had to adapt to the strict syntax rules of XML, ensuring all tags were accurately nested and closed properly. Finally, deciding on a logical folder architecture (CSS, JS, IMAGES, PAGES) was tricky but necessary to ensure the project remains organized during future coding phases.
</p>

<h2 class="section-heading">4. Learning Outcomes</h2>
<ul>
  <li>Gained hands-on experience structuring a multi-page web application using standard HTML5 tags and semantic elements.</li>
  <li>Learned how to implement global navigation and link multiple HTML files together across different directories.</li>
  <li>Mastered the process of organizing a clean project architecture with dedicated folders for CSS, JS, and HTML pages.</li>
  <li>Developed an understanding of how to build structural layouts for forms, tables, and dashboards using pure HTML.</li>
  <li>Learned how to write and format a <code>sitemap.xml</code> file to define the website's hierarchy for search engines.</li>
</ul>

<div class="repo-footer">
  <strong>Repository Link:</strong> <code>https://github.com/Panthsutariya/studenthub</code>
</div>

<!-- ================= PRACTICAL 2 ================= -->
<h1 class="practical-title">Practical 2: Semantic HTML5 Pages with Accessibility-Ready Structure</h1>

<div class="aim-box">
  <div class="aim-title">AIM:</div>
  Develop static HTML5 skeletons for at least 10 StudentHub pages such as Home, About, Register, Login, Dashboard, Events, Profile, Contact, Admin, FAQ, and Feedback. Use semantic tags and accessibility-friendly structure.
</div>

<h2 class="section-heading">1. Implementation Details</h2>
<p>
In this practical, the foundational structure of the StudentHub portal was developed. Ten separate HTML pages (such as dashboard, courses, login, and result) were coded using core HTML5 elements. Semantic tags, complex <code>&lt;table&gt;</code> structures for data presentation, and <code>&lt;form&gt;</code> elements for user input were implemented to build the raw, unstyled framework of the application.
</p>

<h2 class="section-heading">2. Evidence of Work (Static HTML5 Screenshots)</h2>
<?= imgTag('homeout.png', 'Home Page: Plain HTML structure', '650px', true, 'Student Dashboard: Plain HTML structure with student stats and navigation links') ?>
<?= imgTag('loginout.png', 'Login Page: Unstyled form', '650px', true, 'Login Page: Unstyled form with email, password, and remember me checkbox') ?>
<?= imgTag('studentprofileout.png', 'Student Profile: Plain HTML details', '650px', true, 'Register Page: Student registration form with input fields for personal and academic details') ?>
<?= imgTag('courseregout.png', 'Course Registration: HTML Form', '650px', true, 'Contact Us Page: Message submission form and campus contact information') ?>
<?= imgTag('timetableout.png', 'Time Table: HTML Table layout', '650px', true, 'About Page: Mission, vision, and technical architecture specifications') ?>
<?= imgTag('attendanceout.png', 'Attendance: Plain HTML tracker', '650px', true, 'Enrolled Courses Page: Course curriculum with subject credits and syllabus buttons') ?>
<?= imgTag('examscheduleout.png', 'Exam Schedule: Unstyled schedule table', '650px', true, 'Attendance Page: Overall attendance percentage and subject-wise lecture counts') ?>
<?= imgTag('hostelsout.png', 'Hostels: Information layout', '650px', true, 'Assignments Page: Course assessments with deadline dates and action buttons') ?>
<?= imgTag('notificationout.png', 'Notification: Campus notice list', '650px', true, 'Academic Results & Transcripts: Semester GPA summary and grade table') ?>
<?= imgTag('logoutout.png', 'Logout: Session termination confirmation', '650px', true, 'Student Profile Page: Personal details and academic record table') ?>

<h2 class="section-heading">3. Challenges Faced</h2>
<p>
During Practical 2, the main challenge was building the portal strictly using unstyled HTML elements while ensuring the content remained logical and readable. Creating complex pages like the results and dashboard required careful nesting of <code>&lt;table&gt;</code>, <code>&lt;tr&gt;</code>, and <code>&lt;td&gt;</code> tags to present data clearly. Another hurdle was designing the forms for login and registration, ensuring all <code>&lt;input&gt;</code> fields had the correct attributes for future backend integration. Furthermore, manually linking 10 distinct files using anchor tags meant that testing the navigation flow and preventing dead links was a tedious and error prone process.
</p>

<h2 class="section-heading">4. Learning Outcomes</h2>
<ul>
  <li>Mastered the creation of structured, multi-page HTML5 documents.</li>
  <li>Learned how to build complex data tables and interactive user input forms.</li>
  <li>Gained experience using semantic HTML to ensure clear document structure.</li>
  <li>Understood how to manually link static web pages to form a cohesive portal.</li>
</ul>

<div class="repo-footer">
  <strong>Repository Link:</strong> <code>https://github.com/Panthsutariya/studenthub</code>
</div>

<!-- ================= PRACTICAL 3 ================= -->
<h1 class="practical-title">Practical 3: Responsive UI Design using CSS Grid, Flexbox, and Mobile-First Rules</h1>

<div class="aim-box">
  <div class="aim-title">AIM / Problem Definition:</div>
  Design responsive layouts for Home, About, Registration, Dashboard, and Events pages using CSS Grid and Flexbox. Optional to use CSS framework such as Bootstrap or Tailwind. Ensure mobile-first responsiveness.
</div>

<h2 class="section-heading">1. Implementation Details</h2>
<p>
This practical focused on UI design and project organization. The flat file structure was reorganized into a professional architecture using CSS, JS, and PAGES directories. A central <code>script.css</code> / <code>style.css</code> stylesheet was created and linked to all pages, utilizing advanced CSS techniques like linear gradients, box shadows, Flexbox layouts, and hover effects to transform the raw HTML into a polished, modern interface.
</p>

<h2 class="section-heading">2. Evidence of Work (Responsive CSS Screenshots)</h2>
<?= imgTag('homecssout.png', 'Home Page: Styled responsive layout', '650px', true, 'Landing Page: Polished hero section with feature cards and top navbar') ?>
<?= imgTag('homecssout.png', 'Student Dashboard / Home View', '650px', true, 'Student Dashboard: Styled dashboard with KPI cards, announcements, and CSS Grid layout') ?>
<?= imgTag('logincssout.png', 'Login Page: Styled CSS form card', '650px', true, 'Login Page: Centered authentication card with clean spacing and styled buttons') ?>
<?= imgTag('studentprofilecssout .png', 'Student Profile: Modern card layout', '650px', true, 'Sign-up Page: Responsive two-column form layout with modern input styling') ?>
<?= imgTag('courseregcssout.png', 'Course Registration: Styled input controls', '650px', true, 'Enrolled Courses: Multi-column responsive card grid for curriculum subjects') ?>
<?= imgTag('timetablecssout.png', 'Time Table: Grid styling and alternating rows', '650px', true, 'Assignments Page: Color-coded status badges and action cards') ?>
<?= imgTag('attendancecssout.png', 'Attendance: Styled table with progress badges', '650px', true, 'Attendance Tracker: Visual progress bars and eligibility status tags') ?>
<?= imgTag('examschedulecssout .png', 'Exam Schedule: Responsive table design', '650px', true, 'Results & Transcripts: Modern summary KPI boxes and high-contrast table styling') ?>
<?= imgTag('hostelscssout.png', 'Hostels: Two-column responsive card styling', '650px', true, 'Contact Us: Two-column responsive layout separating form from contact info') ?>
<?= imgTag('notificationcssout .png', 'Notification: Styled card announcements', '650px', true, 'Profile Page: Academic particulars table with action buttons') ?>
<?= imgTag('logoutcssout .png', 'Logout: Styled centered logout view', '650px', true, 'About Page: Responsive mission, vision, and technology stack presentation') ?>

<h2 class="section-heading">3. Challenges Faced</h2>
<p>
In Practical 3, moving the raw HTML into a structured directory architecture (PAGES, CSS, JS) broke all existing file links, which was challenging to fix by updating every relative path. When writing the global stylesheet, applying Flexbox to the feature boxes and form containers was tricky; getting the items to align and wrap correctly required constant tweaking. Additionally, implementing consistent styling for tables and navigation bars across all pages meant I had to rely heavily on CSS classes and global rules, which was a significant shift in thinking. Finally, debugging conflicting CSS rules where styles accidentally overrode each other took considerable time.
</p>

<h2 class="section-heading">4. Learning Outcomes</h2>
<ul>
  <li>Learned to structure a web project professionally using dedicated directories.</li>
  <li>Gained experience applying aesthetic properties like gradients, shadows, and rounded borders.</li>
  <li>Learned how to resolve relative path issues when linking external stylesheets.</li>
  <li>Understood the importance of a single global stylesheet for maintaining UI consistency.</li>
</ul>

<div class="repo-footer">
  <strong>Repository Link:</strong> <code>https://github.com/Panthsutariya/studenthub</code>
</div>

<!-- ================= PRACTICAL 4 ================= -->
<h1 class="practical-title">Practical 4: JavaScript DOM Manipulation, Event Handling, and UI Interactivity</h1>

<div class="aim-box">
  <div class="aim-title">AIM:</div>
  Add dynamic UI components such as collapsible FAQ, modal popup, image/content slider, notification banner, hamburger menu, and light/dark theme switcher using JavaScript.
</div>

<h2 class="section-heading">1. Implementation Details & Explanation</h2>
<ul>
  <li><strong>Light / Dark Theme Switcher:</strong> Clicking <code>#theme-toggle</code> in <code>main.js</code> runs <code>document.documentElement.setAttribute('data-theme', theme)</code>. To remember the user's selected theme across page refreshes, <code>localStorage.setItem('studenthub_theme', ...)</code> is used.</li>
  <li><strong>Hamburger Menu (Responsive Navigation):</strong> Clicking the header's hamburger button toggles the <code>open</code> class on the navigation menu (<code>navMenu.classList.toggle('open')</code>) with outside-click detection.</li>
  <li><strong>Notification Banner:</strong> Dismissible alerts support manual closing and automated smooth fade-out dismissal.</li>
  <li><strong>Modal Popup Dialog:</strong> Clicking trigger buttons adds <code>show</code> class to modal backdrops with Escape key closing and focus control.</li>
  <li><strong>Collapsible FAQ Accordion:</strong> Clicking an accordion header dynamically expands the height (<code>maxHeight = scrollHeight + 'px'</code>) and toggles active indicators.</li>
</ul>

<h2 class="section-heading">2. Evidence of Work (Interactivity Screenshots)</h2>
<?= imgTag('image33.png', 'Home Light Theme: Clean modern interface in default light mode') ?>
<?= imgTag('image34.jpeg', 'Home Dark Theme: High-contrast dark mode with dark blue palette') ?>
<?= imgTag('image35.png', 'DevTools Application Tab: localStorage verifying theme persistence and data cache') ?>
<?= imgTag('image36.png', 'Mobile View: Responsive hamburger menu drawer on mobile screen') ?>
<?= imgTag('image37.png', 'Interactive Content Slider: Student portal highlights slider with next/prev buttons') ?>
<?= imgTag('image38.png', 'Collapsible FAQ Accordion: Questions expand and collapse on click') ?>
<?= imgTag('image39.png', 'Modal Popup Dialog: Course syllabus modal with animated backdrop') ?>
<?= imgTag('image40.jpeg', 'Contact Us Dark Mode: Accessible dark theme styling on input elements') ?>

<h2 class="section-heading">3. Key Questions / Analysis / Interpretation</h2>
<div class="qa-box">
  <div class="qa-q">Q1. How are DOM elements selected and modified?</div>
  <div class="qa-a">
    DOM elements are selected using methods like <code>getElementById()</code>, <code>querySelector()</code>, and <code>querySelectorAll()</code>. They are modified using properties such as <code>textContent</code>, <code>innerHTML</code>, <code>style</code>, and <code>classList</code>.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q2. Are event listeners attached correctly?</div>
  <div class="qa-a">
    Yes, event listeners are attached correctly using <code>addEventListener()</code>. They respond to user actions such as clicks, input changes, and keyboard events.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q3. Is localStorage used for remembering theme choice?</div>
  <div class="qa-a">
    Yes, <code>localStorage</code> is used to save the selected theme. When the page is reopened, the saved theme is retrieved and applied automatically.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q4. Does interactivity improve usability without breaking accessibility?</div>
  <div class="qa-a">
    Yes, the interactive features improve usability while maintaining accessibility by using clear buttons, proper labels, keyboard-friendly controls, and readable contrast.
  </div>
</div>

<div class="repo-footer">
  <strong>Conclusion:</strong> The interactive UI featuresâ€”including responsive hamburger navigation, persistent dark mode, and modal popupsâ€”were successfully integrated using JavaScript.<br>
  <strong>Repository Link:</strong> <code>https://github.com/Panthsutariya/studenthub</code>
</div>

<!-- ================= PRACTICAL 5 ================= -->
<h1 class="practical-title">Practical 5: Registration Form with Frontend Validation and User-Friendly Error Handling</h1>

<div class="aim-box">
  <div class="aim-title">AIM:</div>
  Create a student registration form with HTML5 input types and JavaScript validation for name, email, mobile number, password, confirm password, course, year, gender, and terms acceptance. Use Regular Expression for validations.
</div>

<h2 class="section-heading">1. Implementation Details</h2>
<p>
A student registration form was built with HTML5 input types and client-side validation logic in <code>js/validation.js</code>:
</p>
<ul>
  <li>Full Name validation using regex: <code>/^[a-zA-Z\s]{2,50}$/</code></li>
  <li>University Email validation: <code>/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/</code></li>
  <li>Mobile number validation: <code>/^[6-9]\d{9}$/</code> (10 digits starting with 6 to 9)</li>
  <li>Password strength evaluation measuring length, character variety, digits, and special symbols with color-coded bar meter.</li>
  <li>Advanced Extension: HTML5 Canvas CAPTCHA generation rendering randomized distorted characters and line noise.</li>
</ul>

<h2 class="section-heading">2. Evidence of Work (Validation Screenshots)</h2>
<?= imgTag('image41.png', 'Form Validation Errors: Inline error messages displayed beneath empty required fields') ?>
<?= imgTag('image42.png', 'Registration Form: Multi-field layout with Canvas CAPTCHA and branch selection') ?>
<?= imgTag('image43.png', 'Regex Validation Test: Real-time warnings on invalid email and phone number input') ?>
<?= imgTag('image44.png', 'Password Strength Meter: Visual meter bar indicating Weak / Medium / Strong score') ?>
<?= imgTag('image45.jpeg', 'HTML5 Browser Constraint Validation: Tooltip warning on empty required field') ?>

<h2 class="section-heading">3. Key Questions / Analysis / Interpretation</h2>
<div class="qa-box">
  <div class="qa-q">Q1. Are correct input types and attributes used?</div>
  <div class="qa-a">
    Yes, correct input types such as text, email, password, and number are used with suitable attributes like required, placeholder, and maxlength.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q2. Are validation errors displayed near the relevant fields?</div>
  <div class="qa-a">
    Yes, validation errors are displayed near the corresponding input fields so users can easily understand and correct their mistakes.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q3. Is password strength checked?</div>
  <div class="qa-a">
    Yes, password strength is checked based on conditions such as minimum length, uppercase/lowercase letters, numbers, and special characters.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q4. Is the form accessible using keyboard and screen-reader-friendly labels?</div>
  <div class="qa-a">
    Yes, the form is keyboard accessible and uses proper labels associated with input fields, making it easier for screen readers to understand.
  </div>
</div>

<div class="repo-footer">
  <strong>Conclusion:</strong> We successfully built a secure student registration form with HTML5 and JavaScript Regex validation, client-side error handling, and canvas CAPTCHA.<br>
  <strong>Repository Link:</strong> <code>https://github.com/Panthsutariya/studenthub</code>
</div>

<!-- ================= PRACTICAL 6 ================= -->
<h1 class="practical-title">Practical 6: Rendering External JSON Data using Fetch API, Search and Filter</h1>

<div class="aim-box">
  <div class="aim-title">AIM:</div>
  Display event lists, student profiles, notices, or FAQs from external JSON files using Fetch API. Implement dynamic rendering, search, filter, sorting, and pagination.
</div>

<h2 class="section-heading">1. Implementation Details</h2>
<p>
Dynamic data consumption was built in <code>js/dynamic-lists.js</code> consuming 16+ records each from <code>data/faqs.json</code>, <code>data/events.json</code>, and <code>data/students.json</code>:
</p>
<ul>
  <li>Fetched JSON asynchronously using <code>fetch(url)</code> with <code>async/await</code> and error catching.</li>
  <li>Dynamic search across titles, descriptions, and venues using <code>filter()</code> and <code>includes()</code>.</li>
  <li>Category dropdown filter dynamically populated using <code>new Set()</code> and <code>map()</code>.</li>
  <li>Alphabetical and date sorting using <code>sort()</code>.</li>
  <li>Client-side pagination using <code>slice()</code> with page status counters.</li>
  <li>Offline caching in <code>localStorage</code> as fallback when the network is unavailable.</li>
</ul>

<h2 class="section-heading">2. Evidence of Work (Fetch API Screenshots)</h2>
<?= imgTag('image46.jpeg', 'Live Search Test: Real-time keyword filter searching event titles from JSON') ?>
<?= imgTag('image47.jpeg', 'Category Filter & Sort: Events filtered by Sports category and sorted A to Z') ?>
<?= imgTag('image48.png', 'Pagination Test: Multi-page navigation displaying 6 cards per page with page numbers') ?>
<?= imgTag('image49.png', 'JSON Dataset Structure: Structured events.json dataset with at least 15 records in VS Code') ?>

<h2 class="section-heading">3. Key Questions / Analysis / Interpretation</h2>
<div class="qa-box">
  <div class="qa-q">Q1. How is JSON fetched, parsed, and rendered?</div>
  <div class="qa-a">
    JSON is fetched using <code>fetch()</code>, parsed using <code>.json()</code>, and displayed on the webpage using JavaScript DOM manipulation.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q2. Which array methods are used for search, filter, sort, and pagination?</div>
  <div class="qa-a">
    Search &rarr; <code>includes()</code> | Filter &rarr; <code>filter()</code> | Sort &rarr; <code>sort()</code> | Pagination &rarr; <code>slice()</code>.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q3. How are loading and error states handled?</div>
  <div class="qa-a">
    A loading message is shown while fetching data. If an error occurs, <code>.catch()</code> displays an informative error banner with localStorage fallback.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q4. How is modularity maintained in JavaScript files?</div>
  <div class="qa-a">
    Code is divided into separate modular files based on functionality (e.g., <code>main.js</code>, <code>validation.js</code>, <code>dynamic-lists.js</code>).
  </div>
</div>

<div class="repo-footer">
  <strong>Conclusion:</strong> The student portal was successfully enhanced with dynamic JSON rendering using Fetch API, supporting live search, category filtering, sorting, and pagination.<br>
  <strong>Repository Link:</strong> <code>https://github.com/Panthsutariya/studenthub</code>
</div>

<!-- ================= PRACTICAL 7 ================= -->
<h1 class="practical-title">Practical 7: PHP Form Processing with Server-Side Validation and CSV/JSON File Storage</h1>

<div class="aim-box">
  <div class="aim-title">AIM:</div>
  Process submitted registration/contact form data using PHP. Validate and sanitize inputs on the server side and store records in CSV or JSON file format. Display success/error messages.
</div>

<h2 class="section-heading">1. Implementation Details</h2>
<p>
Implemented server-side form processing via <code>contact.php</code>, <code>contact-process.php</code>, and <code>view-submissions.php</code>:
</p>
<ul>
  <li>Form submissions accepted strictly via HTTP POST.</li>
  <li>Inputs sanitized using <code>htmlspecialchars()</code> and validated with <code>filter_var()</code>.</li>
  <li>Data is concurrently stored in both JSON (<code>contact-submissions.json</code>) and CSV (<code>contact-submissions.csv</code>) formats.</li>
  <li>Protected against concurrent write race conditions using <code>flock($fp, LOCK_EX)</code>.</li>
  <li>Intermediate Extension: Built <code>view-submissions.php</code> rendering all stored file records in formatted tables.</li>
</ul>

<h2 class="section-heading">2. Evidence of Work (File Processing Screenshots)</h2>
<?= imgTag('image50.png', 'Server-Side Success: Green flash message confirming safe record logging in CSV and JSON') ?>
<?= imgTag('image51.png', 'Server-Side Validation Failure: Red error banner highlighting missing required inputs') ?>
<?= imgTag('image52.png', 'CSV Storage: Form submissions stored in contacts.csv with timestamp and client IP') ?>
<?= imgTag('image53.png', 'JSON Storage: Registrations and submissions saved with structured JSON objects in VS Code') ?>
<?= imgTag('image54.png', 'Stored Records Webpage: Admin viewer page displaying stored file records in responsive tables') ?>

<h2 class="section-heading">3. Key Questions / Analysis / Interpretation</h2>
<div class="qa-box">
  <div class="qa-q">Q1. Is the form submitted using POST?</div>
  <div class="qa-a">
    Yes. The form uses the POST method to send data securely to the PHP backend without exposing data in URL parameters.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q2. Are inputs validated and sanitized server side?</div>
  <div class="qa-a">
    Yes. The PHP code validates input fields and sanitizes user data on the server side before processing or storing it.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q3. Is file writing handled safely?</div>
  <div class="qa-a">
    Yes. The data is written using <code>flock()</code> file locking to prevent data corruption during simultaneous user writes.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q4. Are success and error responses displayed clearly?</div>
  <div class="qa-a">
    Yes. The program displays clear success messages when the form is submitted successfully and error messages when validation fails.
  </div>
</div>

<div class="repo-footer">
  <strong>Conclusion:</strong> Server-side form processing with robust sanitization and dual CSV/JSON file storage was successfully implemented.<br>
  <strong>Repository Link:</strong> <code>https://github.com/Panthsutariya/studenthub</code>
</div>

<!-- ================= PRACTICAL 8 ================= -->
<h1 class="practical-title">Practical 8: MySQL Schema Design, ER Model, PDO Connectivity, and Prepared Statements</h1>

<div class="aim-box">
  <div class="aim-title">AIM:</div>
  Design the database for StudentHub with students, events and registrations. Create PHP database connection using MYSQL statements.
</div>

<h2 class="section-heading">1. Implementation Details</h2>
<p>
Engineered the relational database in <code>sql/studenthub.sql</code> and dual connection files <code>db.php</code> and <code>db_pdo.php</code>:
</p>
<ul>
  <li>Normalized database schema containing 5 tables: <code>users</code>, <code>students</code>, <code>events</code>, <code>registrations</code>, and <code>audit_logs</code>.</li>
  <li>Implemented primary keys, foreign key constraints with <code>ON UPDATE CASCADE ON DELETE CASCADE</code>, and performance indexes.</li>
  <li>Dual connectivity support: MySQLi connection in <code>db.php</code> and PDO connection with prepared statements in <code>db_pdo.php</code>.</li>
  <li>Built <code>test_connection.php</code> providing automated verification of MySQLi, PDO, and stored procedures.</li>
</ul>

<h2 class="section-heading">2. Evidence of Work (Database Screenshots)</h2>
<?= imgTag('image55.png', 'Database Verification Suite: test_connection.php verifying MySQLi, PDO, and Stored Procedures') ?>
<?= imgTag('image56.png', 'phpMyAdmin Table Inventory: Schema showing audit_logs, events, registrations, students, and users') ?>
<?= imgTag('image57.png', 'Database ER Diagram: Entity Relationship model displaying primary and foreign key links') ?>
<?= imgTag('image58.jpeg', 'Events Table in phpMyAdmin: Seeded campus event records with dates, venues, and status') ?>
<?= imgTag('image59.jpeg', 'Registrations Table in phpMyAdmin: Junction table connecting student_id and event_id') ?>
<?= imgTag('image60.jpeg', 'Students Table in phpMyAdmin: 22+ seeded student records exceeding manual requirements') ?>

<h2 class="section-heading">3. Key Questions / Analysis / Interpretation</h2>
<div class="qa-box">
  <div class="qa-q">Q1. Is the schema normalized properly?</div>
  <div class="qa-a">
    Yes, the schema is normalized to Third Normal Form (3NF), separating authentication, students, events, and registration transactions without redundancy.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q2. Are primary keys and foreign keys correctly defined?</div>
  <div class="qa-a">
    Yes, primary keys uniquely identify records, and foreign keys maintain referential integrity with cascading constraints.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q3. Is the database connection handled securely?</div>
  <div class="qa-a">
    Yes, database operations use UTF-8 character encoding (<code>utf8mb4</code>) and prepared statements preventing SQL injection attacks.
  </div>
</div>

<div class="repo-footer">
  <strong>Conclusion:</strong> StudentHub database was successfully designed using MySQL and connected with PHP PDO and MySQLi prepared statements.<br>
  <strong>Repository Link:</strong> <code>https://github.com/Panthsutariya/studenthub</code>
</div>

<!-- ================= PRACTICAL 9 ================= -->
<h1 class="practical-title">Practical 9: Secure User Registration with Database Insert, Duplicate Email Check, and Password Hashing</h1>

<div class="aim-box">
  <div class="aim-title">AIM:</div>
  Implement secure user registration by inserting user details into MySQL using MySQLi. Validate input on both frontend and backend, check for duplicate email/username, hash passwords using password_hash(), and display appropriate success or error messages.
</div>

<h2 class="section-heading">1. Implementation Details</h2>
<p>
Implemented secure user account creation in <code>register.php</code>:
</p>
<ul>
  <li>Passwords encrypted using PHP's native <code>password_hash($password, PASSWORD_BCRYPT)</code> generating a 60-character hash.</li>
  <li>Checks for duplicate email addresses, usernames, and student enrollment numbers using prepared queries before insertion.</li>
  <li>Uses database transactions (<code>begin_transaction()</code>, <code>commit()</code>, <code>rollback()</code>) ensuring user credentials and student records are inserted together atomically.</li>
  <li>Records an audit log entry in the <code>audit_logs</code> table upon each successful registration.</li>
</ul>

<h2 class="section-heading">2. Evidence of Work (Registration Screenshots)</h2>
<?= imgTag('image61.png', 'Duplicate Email Detection: Server-side validation warning that email address is already registered') ?>
<?= imgTag('image62.png', 'Successful Registration: Account created successfully with assigned Student ID') ?>
<?= imgTag('image63.png', 'Password Hashing in MySQL: Verified 60-character Bcrypt hashes in users table') ?>
<?= imgTag('image64.png', 'Audit Log Entry: Event and user registration activities logged in audit_logs table') ?>

<h2 class="section-heading">3. Key Questions / Analysis / Interpretation</h2>
<div class="qa-box">
  <div class="qa-q">Q1. Is password_hash() used before storing passwords?</div>
  <div class="qa-a">
    Yes, <code>password_hash()</code> is used before storing passwords in the database to prevent plain-text exposure.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q2. Is duplicate email/username checked?</div>
  <div class="qa-a">
    Yes, duplicate email and username are checked before inserting the user details.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q3. Are frontend and backend validations consistent?</div>
  <div class="qa-a">
    Yes, frontend and backend validations are kept consistent to ensure valid and secure user input.
  </div>
</div>

<div class="repo-footer">
  <strong>Conclusion:</strong> The practical successfully implemented secure user registration using PHP, MySQLi, Bcrypt hashing, and duplicate checks.<br>
  <strong>Repository Link:</strong> <code>https://github.com/Panthsutariya/studenthub</code>
</div>

<!-- ================= PRACTICAL 10 ================= -->
<h1 class="practical-title">Practical 10: Secure Login/Logout with Sessions, Role-Based Access, Timeout, and Remember-Me Token</h1>

<div class="aim-box">
  <div class="aim-title">AIM:</div>
  Develop secure login and logout functionality using PHP sessions. Verify passwords using password_verify(), regenerate the session ID after login, restrict dashboard access, implement role-based redirection and session timeout, and optionally add a secure remember-me token.
</div>

<h2 class="section-heading">1. Implementation Details</h2>
<p>
Authentication and session management were implemented across <code>login.php</code>, <code>logout.php</code>, and <code>includes/auth.php</code>:
</p>
<ul>
  <li>Authentication verifies passwords using <code>password_verify()</code> against stored Bcrypt hashes.</li>
  <li>Session regeneration using <code>session_regenerate_id(true)</code> to prevent session fixation.</li>
  <li>15-minute inactivity session timeout automatically logging out inactive users.</li>
  <li>Role-Based Access Control (RBAC): Administrators are redirected to <code>admin/dashboard.php</code>; students are routed to student portals. Unauthorized access triggers HTTP 403.</li>
  <li>Persistent Remember-Me functionality using secure random tokens stored as SHA-256 hashes in database and set in HttpOnly cookies.</li>
</ul>

<h2 class="section-heading">2. Evidence of Work (Authentication Screenshots)</h2>
<?= imgTag('image65.png', 'Login Interface: login.php with university email, password, and remember me checkbox') ?>
<?= imgTag('image66.png', 'Administrator Control Center: Role-based admin dashboard with live statistics') ?>
<?= imgTag('image67.png', 'Student Dashboard: Student view with personal CGPA, attendance, and announcements') ?>
<?= imgTag('image68.png', 'Failed Login Attempt: Authentication notice alerting user of invalid credentials') ?>
<?= imgTag('image69.png', 'Remember-Me Cookie in DevTools: studenthub_remember cookie stored with HttpOnly flag') ?>

<h2 class="section-heading">3. Key Questions / Analysis / Interpretation</h2>
<div class="qa-box">
  <div class="qa-q">Q1. Are sessions started and destroyed securely?</div>
  <div class="qa-a">
    Yes, sessions are started securely with HttpOnly cookies and destroyed properly during logout.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q2. Is password_verify() used correctly?</div>
  <div class="qa-a">
    Yes, <code>password_verify()</code> is used to verify the entered password against the hashed password stored in the database.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q3. Is unauthorized dashboard access blocked?</div>
  <div class="qa-a">
    Yes, unauthorized users are prevented from accessing the dashboard using session-based authentication guards (<code>require_admin()</code>).
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q4. Is session timeout implemented?</div>
  <div class="qa-a">
    Yes, session timeout is implemented to automatically log out inactive users after 15 minutes of inactivity.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q5. Are admin and student users redirected to appropriate dashboards?</div>
  <div class="qa-a">
    Yes, admin and student users are redirected to their respective dashboards based on their roles.
  </div>
</div>

<div class="repo-footer">
  <strong>Conclusion:</strong> Secure login and logout functionality with role-based routing, session regeneration, timeout, and remember-me tokens was successfully implemented.<br>
  <strong>Repository Link:</strong> <code>https://github.com/Panthsutariya/studenthub</code>
</div>

<!-- ================= PRACTICAL 11 ================= -->
<h1 class="practical-title">Practical 11: Student Management CRUD with Search, Filter, and MySQLi Statements</h1>

<div class="aim-box">
  <div class="aim-title">AIM:</div>
  Create a student management module to add, view, edit, delete, search, and filter student records. Perform all database operations using MySQLi statements and display clear success or failure messages.
</div>

<h2 class="section-heading">1. Implementation Details</h2>
<p>
Engineered complete student management in <code>students.php</code>, <code>student-add.php</code>, <code>student-edit.php</code>, <code>student-delete.php</code>, and <code>student-export.php</code>:
</p>
<ul>
  <li>Full CRUD: Add student, View paginated table, Edit details, and Delete with modal confirmation.</li>
  <li>Multi-criteria search filtering by name, enrollment ID, and email, alongside branch and status filters.</li>
  <li>Intermediate Extension: Built <code>student-export.php</code> exporting the full student directory to a downloadable CSV spreadsheet.</li>
  <li>Advanced Extension: Added soft-delete support toggling student status to 'inactive' instead of permanent deletion.</li>
  <li>Dataset contains 22 seeded student records.</li>
</ul>

<h2 class="section-heading">2. Evidence of Work (Student CRUD Screenshots)</h2>
<?= imgTag('image70.png', 'Student Management Module: Table listing students with branch, status, and action buttons') ?>
<?= imgTag('image71.png', 'Add New Student Modal: Input form for enrollment number, full name, email, and branch') ?>
<?= imgTag('image72.png', 'Edit Student Record Modal: Form pre-filled with existing data for update operations') ?>
<?= imgTag('image73.png', 'Search and Filter Test: Real-time search by branch (B.Tech CE) displaying filtered results') ?>
<?= imgTag('image74.png', 'Soft Delete Confirmation: Green notice confirming student marked as deleted (Soft Delete)') ?>
<?= imgTag('image75.jpeg', 'CSV Export: Downloaded students_export.csv file displaying structured records') ?>

<h2 class="section-heading">3. Key Questions / Analysis / Interpretation</h2>
<div class="qa-box">
  <div class="qa-q">Q1. Are SELECT, INSERT, UPDATE, and DELETE operations working?</div>
  <div class="qa-a">
    Yes, SELECT, INSERT, UPDATE, and DELETE operations are working correctly using MySQLi prepared statements.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q2. Is search by name/email/course implemented?</div>
  <div class="qa-a">
    Yes, search and filtering by name, email, and course are implemented.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q3. Are delete operations confirmed before execution?</div>
  <div class="qa-a">
    Yes, an interactive modal confirmation is displayed before deleting or soft-deleting any student record.
  </div>
</div>

<div class="repo-footer">
  <strong>Conclusion:</strong> The practical successfully implemented a complete Student Management CRUD module with search, filter, pagination, soft-delete, and CSV export.<br>
  <strong>Repository Link:</strong> <code>https://github.com/Panthsutariya/studenthub</code>
</div>

<!-- ================= PRACTICAL 12 ================= -->
<h1 class="practical-title">Practical 12: Event Management CRUD with Poster Upload and File Validation</h1>

<div class="aim-box">
  <div class="aim-title">AIM:</div>
  Develop event management functionality for admin users. Admin can add, view, update, delete, and upload event posters with proper file type and file size validation.
</div>

<h2 class="section-heading">1. Implementation Details</h2>
<p>
Event lifecycle management was built in <code>events.php</code>, <code>event-add.php</code>, <code>event-edit.php</code>, <code>event-delete.php</code>, and <code>event-register.php</code>:
</p>
<ul>
  <li>Admin can Add, View, Update, and Delete campus events.</li>
  <li>Poster upload validation enforces MIME type checking via <code>finfo()</code> (JPG, PNG, WEBP) and a maximum size limit of 2 MB.</li>
  <li>Uploaded files are renamed with cryptographic random hashes and saved into <code>uploads/events/</code> protected by an <code>.htaccess</code> execution barrier.</li>
  <li>Students can register for open events with a single click, automatically updating seat capacity.</li>
</ul>

<h2 class="section-heading">2. Evidence of Work (Event CRUD Screenshots)</h2>
<?= imgTag('image76.png', 'Event Management Cards Grid: Displaying events with poster images, dates, venues, and status') ?>
<?= imgTag('image77.png', 'Publish New Event Modal: Form with category, date picker, venue, and poster file upload') ?>
<?= imgTag('image78.png', 'Edit Event Modal: Pre-populated modal allowing administrators to modify details or replace poster') ?>

<h2 class="section-heading">3. Key Questions / Analysis / Interpretation</h2>
<div class="qa-box">
  <div class="qa-q">Q1. Are add, update, and delete functionalities implemented correctly?</div>
  <div class="qa-a">
    Yes, add, update, and delete functionalities are implemented correctly with database linking.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q2. Is the UI linked with the database correctly?</div>
  <div class="qa-a">
    Yes, the UI is properly connected to the database for all event management operations.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q3. Are poster uploads validated for type and size?</div>
  <div class="qa-a">
    Yes, event poster uploads are validated for allowed file types (JPG, PNG, WEBP) and file size (&le;2MB).
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q4. Are success and failure messages displayed?</div>
  <div class="qa-a">
    Yes, appropriate success and failure messages are displayed for each operation.
  </div>
</div>

<div class="repo-footer">
  <strong>Conclusion:</strong> Event Management CRUD with validated file uploads, secure image renaming, and student registration was successfully implemented.<br>
  <strong>Repository Link:</strong> <code>https://github.com/Panthsutariya/studenthub</code>
</div>

<!-- ================= PRACTICAL 13 ================= -->
<h1 class="practical-title">Practical 13: PHP JSON API and AJAX/Fetch-Based CRUD without Page Reload</h1>

<div class="aim-box">
  <div class="aim-title">AIM:</div>
  Create simple PHP JSON API endpoints for events or students and consume them using Fetch API. Perform list, add, update, and delete operations asynchronously without requiring a full page reload.
</div>

<h2 class="section-heading">1. Implementation Details</h2>
<p>
Decoupled API endpoints and single-page application interactivity were implemented in <code>api/events.php</code> and <code>events-ajax.php</code>:
</p>
<ul>
  <li>RESTful JSON API supporting GET, POST, PUT, and DELETE methods with HTTP response codes (200, 201, 400, 404, 500).</li>
  <li>Consumed asynchronously using JavaScript <code>fetch()</code> without reloading the page.</li>
  <li>Implemented debounced real-time search (300ms) reducing unnecessary server queries.</li>
  <li>Interactive in-place modals for Add/Edit operations and row-fading deletions with toast notifications.</li>
</ul>

<h2 class="section-heading">2. Evidence of Work (AJAX & API Screenshots)</h2>
<?= imgTag('image79.png', 'RESTful JSON API Response: api/events.php returning HTTP 200 and valid JSON dataset') ?>
<?= imgTag('image80.png', 'AJAX CRUD Single Page App: Live event cards loaded via Fetch API without page reload') ?>
<?= imgTag('image81.png', 'Asynchronous Create Modal: Adding event via AJAX POST with HTTP 201 Created response') ?>
<?= imgTag('image82.png', 'Instant UI Feedback: Green toast alert confirming event created successfully via API') ?>
<?= imgTag('image83.png', 'Browser Network Tab: Inspecting asynchronous fetch requests, debouncing, and response payloads') ?>

<h2 class="section-heading">3. Key Questions / Analysis / Interpretation</h2>
<div class="qa-box">
  <div class="qa-q">Q1. Do PHP endpoints return valid JSON responses?</div>
  <div class="qa-a">
    Yes, PHP endpoints return valid JSON responses for both successful and failed requests.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q2. Are GET and POST requests handled properly?</div>
  <div class="qa-a">
    Yes, GET, POST, PUT, and DELETE requests are handled properly for retrieving and modifying data.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q3. Are loading, success, and error states shown on the frontend?</div>
  <div class="qa-a">
    Yes, loading, success, and error messages are displayed appropriately on the frontend without refreshing.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q4. Is input validation performed before database operations?</div>
  <div class="qa-a">
    Yes, input validation is performed before executing database operations.
  </div>
</div>

<div class="repo-footer">
  <strong>Conclusion:</strong> Fully asynchronous CRUD operations with RESTful JSON API endpoints, Fetch API, and debounced search were successfully implemented with zero page reloads.<br>
  <strong>Repository Link:</strong> <code>https://github.com/Panthsutariya/studenthub</code>
</div>

<!-- ================= PRACTICAL 14 ================= -->
<h1 class="practical-title">Practical 14: Admin Dashboard with Role-Based Access Control, Dynamic Menu, and Audit Log</h1>

<div class="aim-box">
  <div class="aim-title">AIM:</div>
  Develop an admin dashboard that displays user statistics, student count, event count, and latest registrations. Restrict dashboard access to admin users only and display dynamic menu options based on the user's role.
</div>

<h2 class="section-heading">1. Implementation Details</h2>
<p>
Engineered the administrative control center in <code>admin/dashboard.php</code>, <code>admin/audit-logs.php</code>, and <code>admin/users.php</code>:
</p>
<ul>
  <li>Real-time database aggregation displaying Active Students, Campus Events, Registrations, and System Users.</li>
  <li>Visual analytics chart illustrating monthly event registrations trend.</li>
  <li>Dynamic navigation menu displaying admin tools exclusively to administrative sessions.</li>
  <li>Security Audit Trail logging critical operations (logins, logouts, modifications, status changes) in <code>audit_logs</code>.</li>
  <li>Intermediate Extension: Built user moderation module in <code>admin/users.php</code> allowing active/inactive status toggles.</li>
  <li>Enforces HTTP 403 Forbidden access denial against unauthorized students or guests.</li>
</ul>

<h2 class="section-heading">2. Evidence of Work (Admin Dashboard Screenshots)</h2>
<?= imgTag('image84.png', 'Admin Dashboard KPI Metrics: Live database counts for students, events, registrations, and users') ?>
<?= imgTag('image85.png', 'Role-Based Dynamic Menu: Navigation bar dynamically rendering administrative controls for admin') ?>
<?= imgTag('image86.png', 'Monthly Registration Analytics: Visual trend chart illustrating event enrollments by month') ?>
<?= imgTag('image87.png', 'User Moderation Status Toggle: Interface allowing administrators to activate or deactivate user accounts') ?>
<?= imgTag('image88.png', 'Audit Log Test Cases: Comprehensive security audit trail recording logins, status toggles, and operations') ?>
<?= imgTag('image89.png', 'RBAC 403 Access Denied: Security guard blocking non-admin users from accessing admin modules') ?>

<h2 class="section-heading">3. Key Questions / Analysis / Interpretation</h2>
<div class="qa-box">
  <div class="qa-q">Q1. Are dashboard values loaded dynamically from the database?</div>
  <div class="qa-a">
    Yes, dashboard values and KPI metrics are loaded dynamically from the database.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q2. Is access restricted to admin users?</div>
  <div class="qa-a">
    Yes, access to the dashboard is strictly restricted to authenticated admin users.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q3. Are role-based menus working?</div>
  <div class="qa-a">
    Yes, role-based menus are displayed according to the user's role.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q4. Are important admin actions recorded in audit_logs?</div>
  <div class="qa-a">
    Yes, important admin actions are recorded in the <code>audit_logs</code> table for traceability.
  </div>
</div>

<div class="repo-footer">
  <strong>Conclusion:</strong> Role-based Admin Dashboard with real-time KPI metrics, dynamic menu switching, user status moderation, and audit logging was successfully implemented.<br>
  <strong>Repository Link:</strong> <code>https://github.com/Panthsutariya/studenthub</code>
</div>

<!-- ================= PRACTICAL 15 ================= -->
<h1 class="practical-title">Practical 15: Final Integration, Testing, Documentation, GitHub Submission, and Local Deployment</h1>

<div class="aim-box">
  <div class="aim-title">AIM:</div>
  Integrate all modules into a single deployable StudentHub portal. Verify navigation, responsiveness, authentication, database operations, security checks, AJAX functionality, and documentation. Deploy locally using XAMPP/Apache virtual host and submit GitHub repository.
</div>

<h2 class="section-heading">1. Implementation Details</h2>
<p>
The final integration harmonized all 14 previous practicals into a single deployable web application:
</p>
<ul>
  <li>Unified portal navigation under root <code>index.php</code> with responsive design, persistent dark/light mode, and role-based views.</li>
  <li>Automated End-to-End Test Suite verifying 15 out of 15 assertions passed with zero errors.</li>
  <li>System Deployment & Master Diagnostics Center verifying server runtime, 10 PHP extensions, database inventory, and file concurrency permissions.</li>
  <li>Configured Apache Virtual Host architecture (<code>studenthub.local</code>) and complete <code>README.md</code> documentation.</li>
  <li>Clean Git repository with atomic commit history across all practical milestones.</li>
</ul>

<h2 class="section-heading">2. Evidence of Work (End-to-End Testing & Diagnostics Screenshots)</h2>
<?= imgTag('image90.png', 'Master End-to-End Test Suite Runner: Automated test verifying all 15 practical assertions PASSED (15/15)') ?>
<?= imgTag('image91.png', 'System Deployment & Master Diagnostics Center: Complete runtime telemetry, PHP extension audit, database inventory, and file concurrency verified') ?>

<h2 class="section-heading">3. Key Questions / Analysis / Interpretation</h2>
<div class="qa-box">
  <div class="qa-q">Q1. Are all pages properly linked and navigable?</div>
  <div class="qa-a">
    Yes, all pages are properly linked, accessible, and navigable without broken routes.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q2. Are sessions, roles, validations, and database operations working end-to-end?</div>
  <div class="qa-a">
    Yes, authentication, RBAC, form validations, and database CRUD operations operate seamlessly end-to-end.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q3. Are security and accessibility checklists completed?</div>
  <div class="qa-a">
    Yes, CSRF tokens, Bcrypt hashing, prepared statements, session timeouts, and semantic accessibility standards are all satisfied.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q4. Is project documentation sufficient for installation and testing?</div>
  <div class="qa-a">
    Yes, the project documentation in <code>README.md</code> is comprehensive and covers installation, database setup, and testing credentials.
  </div>
</div>
<div class="qa-box">
  <div class="qa-q">Q5. Is the project repository clean and properly committed?</div>
  <div class="qa-a">
    Yes, the Git repository is structured with a clean, descriptive commit history.
  </div>
</div>

<div class="repo-footer">
  <strong>Conclusion:</strong> The StudentHub portal was successfully integrated, tested, and deployed locally using XAMPP. Navigation, authentication, database operations, security checks, and AJAX functionality were verified.<br>
  <strong>Repository Link:</strong> <code>https://github.com/Panthsutariya/studenthub</code>
</div>

</body>
</html>
<?php
$htmlContent = ob_get_clean();
file_put_contents(__DIR__ . '/STUDENTHUB_PRACTICAL_SUBMISSION_REPORT.doc', $htmlContent);
echo "Successfully generated STUDENTHUB_PRACTICAL_SUBMISSION_REPORT.doc (" . strlen($htmlContent) . " bytes)\n";
