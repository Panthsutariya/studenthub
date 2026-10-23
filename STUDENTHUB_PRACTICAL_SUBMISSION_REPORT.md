# CHAROTAR UNIVERSITY OF SCIENCE AND TECHNOLOGY (CHARUSAT)
### FACULTY OF TECHNOLOGY AND ENGINEERING (FTE) / DEPSTAR
**DEPARTMENT OF COMPUTER ENGINEERING / INFORMATION TECHNOLOGY**  
**SUBJECT**: Web Development Frameworks (ITUE203) &bull; Semester 3 (Odd 2026-27)

---

# LABORATORY PRACTICAL MANUAL SUBMISSION REPORT
## Complete Practicals 1 to 15

| Field | Details |
|:---|:---|
| **Project Portal** | **StudentHub Academic & Campus Portal** |
| **Course Code & Name** | ITUE203 - Web Development Frameworks |
| **Student Name** | Jay Bhensdadiya |
| **Student Enrollment ID** | 23DCE128 / 23DCE426 |
| **Academic Department** | Computer Engineering / Information Technology |
| **Repository Link** | `https://github.com/Dev-Jay99/WDF` |
| **Local Deployment Path** | `c:\xampp\htdocs\studenthub\` |

---

## Practical 1: Project Initiation, Requirement Analysis, Sitemap, Wireframe, and GitHub Setup

### AIM:
Initiate the semester-long StudentHub portal by identifying the problem scope, user roles, key modules, navigation flow, and minimum 10 pages. Create a sitemap, low-fidelity wireframe, project folder structure, README file, and GitHub repository.

### 1. Implementation Details:
The foundational structure and architecture of the StudentHub Portal were initiated:
- **Problem Scope & User Roles**: Designed to centralize campus workflows across three distinct user roles: Administrator (full data management, moderation, audit logging), Enrolled Student (profile, event registration, dynamic queries), and Guest (public catalog, FAQs, contact inquiry).
- **Core 10 Modules**: Home, About, Login, Registration, Student Dashboard, Campus Events, Student Profile, Course Catalog, Contact Support, and FAQs.
- **Wireframes**: Created 10 low-fidelity wireframes depicting responsive layouts, feature card grids, authentication forms, and summary tables.
- **Sitemap**: Created `sitemap.xml` defining website page hierarchy, URL routing, and search engine priorities.
- **Folder Architecture & Git**: Established a modular project architecture (`css/`, `js/`, `data/`, `sql/`, `uploads/`, `admin/`, `includes/`) initialized with a clean Git repository and structured `README.md`.

### 2. Key Questions / Analysis / Interpretation:
- **Q1. What is a URL and what are the parts of a URL?**  
  A Uniform Resource Locator (URL) specifies the unique address of a web resource. Its parts are: Protocol (`https://`), Host/Domain (`charusat.edu.in`), Port (`:443`), Path (`/studenthub/events.php`), Query String (`?category=Technical&search=hackathon`), and Fragment/Anchor (`#main-content`).
- **Q2. How is an HTML file processed in a web browser?**  
  Processing follows a 5-step rendering pipeline: (1) HTML bytes are parsed into DOM nodes; (2) CSS stylesheets are parsed into the CSSOM tree; (3) DOM and CSSOM combine into a Render Tree; (4) Layout/Reflow calculates element dimensions and coordinates; (5) Painting rasterizes pixels to the display.
- **Q3. How will page navigation flow be managed among all HTML pages?**  
  Navigation flow is unified via a central header navigation component that dynamically presents links based on user authentication and role, augmented by breadcrumbs on deeper pages.
- **Q4. How will GitHub commits be maintained after each practical?**  
  Commits are made after completing each practical milestone with conventional, atomic messages (e.g., `feat(pr-01): project setup and wireframes`).

### 3. Challenges Faced:
Translating the extensive requirements of a campus portal into clean, non-conflicting module specifications; ensuring consistent folder paths and relative links across all pages during wireframing and initial setup.

### 4. Learning Outcomes:
- Learned how to analyze web application requirements and construct functional sitemaps.
- Gained experience creating low-fidelity wireframes.
- Mastered Git repository setup and professional project folder structuring.

**Conclusion**: Practical 1 successfully established the planning, sitemap, wireframes, and GitHub repository for the StudentHub portal.  
**Repository Link**: `https://github.com/Dev-Jay99/WDF`

---

## Practical 2: Semantic HTML5 Pages with Accessibility-Ready Structure

### AIM:
Develop static HTML5 skeletons for at least 10 StudentHub pages such as Home, About, Register, Login, Dashboard, Events, Profile, Contact, Admin, FAQ, and Feedback. Use semantic tags and accessibility-friendly structure.

### 1. Implementation Details:
- **Semantic Structure**: Built 10 clean page skeletons using `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<aside>`, and `<footer>`.
- **Accessibility Enhancements**: Included a `.skip-link` allowing keyboard users to jump directly to `#main-content`, explicit `<label for="...">` associations for every form input, descriptive `alt` attributes for images, and WAI-ARIA landmark roles (`role="banner"`, `role="navigation"`, `role="main"`, `role="contentinfo"`).
- **Document Outline**: Standardized heading hierarchies (`<h1>` through `<h4>`) and breadcrumb navigation to guarantee accessibility inspection compliance.

### 2. Key Questions / Analysis / Interpretation:
- **Q1. Are semantic tags such as header, nav, main, section, article, aside, and footer used properly?**  
  Yes. Every page utilizes a distinct `<header>`, global `<nav>`, unique `<main>`, modular `<section>` divisions, self-contained `<article>` cards, contextual `<aside>` widgets, and `<footer>`.
- **Q2. Are form labels, headings, image alt text, and navigation links accessible?**  
  Yes. Every form input is bound to an explicit `<label>`, heading levels follow a strict hierarchical sequence without skipping ranks, all image posters have meaningful descriptions, and interactive elements possess keyboard focus indicators.
- **Q3. Is the page structure consistent across all pages?**  
  Yes. The skeleton layout remains uniform across all 10 pages, ensuring visual predictability and assistive technology consistency.

### 3. Challenges Faced & Learning Outcomes:
Replacing older non-semantic markup with pure semantic HTML5 tags while ensuring keyboard tab navigation flows logically without visual styling. Gained deep mastery of accessible document structures and ARIA landmarks.

**Conclusion**: All 10 StudentHub core pages were successfully built with clean, accessible, semantic HTML5 structure.  
**Repository Link**: `https://github.com/Dev-Jay99/WDF`

---

## Practical 3: Responsive UI Design using CSS Grid, Flexbox, and Mobile-First Rules

### AIM:
Design responsive layouts for Home, About, Registration, Dashboard, and Events pages using CSS Grid and Flexbox. Optional to use CSS framework such as Bootstrap or Tailwind. Ensure mobile-first responsiveness.

### 1. Implementation Details:
- **Mobile-First CSS**: Built `css/style.css` starting from mobile viewport base styles, scaling up with media queries at `680px` (tablets) and `900px` (desktops).
- **CSS Grid Layouts**: Implemented `.grid-2`, `.grid-3`, and `.grid-4` for event cards, dashboard KPI widgets, and feature highlights.
- **Flexbox Alignment**: Flexbox powers the global navbar, horizontal action bars, breadcrumb trails, card headers, and form button alignments.
- **Design System**: Integrated reusable CSS custom properties (`--primary`, `--bg-main`, `--text-main`, `--border-color`) ensuring high color contrast ratios meeting WCAG AA accessibility standards.

### 2. Key Questions / Analysis / Interpretation:
- **Q1. How does the layout adapt for mobile, tablet, and desktop screens?**  
  On mobile (<680px), multi-column grids collapse to a single vertical column, and the navbar transforms into a hidden off-canvas drawer. On tablets (>=680px) and desktops (>=900px), CSS Grid expands items into balanced 2-, 3-, and 4-column cards.
- **Q2. Which layout technique is used and why?**  
  A hybrid approach is employed: CSS Grid is selected for 2-dimensional page areas (dashboards, event catalogs, data card grids) while Flexbox is selected for 1-dimensional components (navbars, card headers, button groups, modal footers).
- **Q3. Are typography, spacing, colors, and contrast readable?**  
  Yes. System font stacks with relative line heights (1.6) and high-contrast color pairings (#1e40af on white, #f1f5f9 on dark slate) ensure readability across high-DPI screens.

### 3. Challenges Faced & Learning Outcomes:
Preventing card height stretching across unequal content lengths; debugging flexbox wrapping bugs on mobile input fields; achieving clean typography contrast across both light and dark backgrounds.

**Conclusion**: Fully responsive UI layouts for Home, About, Register, Dashboard, and Events were successfully implemented with pure CSS Grid and Flexbox.  
**Repository Link**: `https://github.com/Dev-Jay99/WDF`

---

## Practical 4: JavaScript DOM Manipulation, Event Handling, and UI Interactivity

### AIM:
Add dynamic UI components such as collapsible FAQ, modal popup, image/content slider, notification banner, hamburger menu, and light/dark theme switcher using JavaScript.

### 1. Implementation Details:
- **Light/Dark Theme Switcher**: Toggles `data-theme="dark"` on `document.documentElement` and persists the user preference in `localStorage` (`studenthub_theme`).
- **Responsive Mobile Hamburger Menu**: Toggles an `.open` class on `#nav-menu` with backdrop click-outside detection and Escape key closing.
- **Notification Banners**: Dismissible alerts with manual close buttons and automated smooth fade-out dismissal after 6 seconds.
- **Accessible Modal Dialogs**: Custom popups supporting keyboard navigation, Escape key closing, and focus restoration.
- **Collapsible FAQ Accordion**: Calculates `scrollHeight` dynamically upon click events, expanding selected items while smoothly collapsing sibling items.

### 2. Key Questions / Analysis / Interpretation:
- **Q1. How are DOM elements selected and modified?**  
  Elements are selected using `document.getElementById()`, `querySelector()`, and `querySelectorAll()`. Modifications are performed safely via `classList.add/remove/toggle`, `setAttribute()`, and `textContent` to prevent XSS vulnerabilities.
- **Q2. Are event listeners attached correctly?**  
  Yes. Event listeners are bound cleanly via `addEventListener('click')`, `addEventListener('input')`, and `addEventListener('keydown')` after the `DOMContentLoaded` event fires.
- **Q3. Is localStorage used for remembering theme choice?**  
  Yes. `localStorage.setItem('studenthub_theme', theme)` persists user selection across page reloads.

### 3. Challenges Faced & Learning Outcomes:
Managing transition timing for accordion expand/collapse using dynamic scrollHeight; preventing page background scroll when modals are open; persisting theme state prior to initial paint.

**Conclusion**: Comprehensive client-side interactivity—including dark theme switching, mobile drawer navigation, modal popups, and accordion FAQ—was successfully implemented.  
**Repository Link**: `https://github.com/Dev-Jay99/WDF`

---

## Practical 5: Registration Form with Frontend Validation and User-Friendly Error Handling

### AIM:
Create a student registration form with HTML5 input types and JavaScript validation for name, email, mobile number, password, confirm password, course, year, gender, and terms acceptance. Use Regular Expression for validations.

### 1. Implementation Details:
- **Regular Expression Formats**:
  - Full Name: `/^[a-zA-Z\s]{2,50}$/`
  - University Email: `/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/`
  - Mobile Number: `/^[6-9]\d{9}$/` (10-digit Indian standard starting with 6-9)
  - Student Number: `/^[0-9]{2}[A-Za-z]{2}[0-9]{3}$/` (e.g., 23IT001)
- **Password Strength Meter**: Real-time scoring across length, upper/lower case letters, digits, and special characters with a 4-tier colored visual bar (Weak, Fair, Good, Strong).
- **Real-Time UX Errors**: Inline error messages rendered dynamically below inputs on `blur` and `keyup` events.
- **Advanced Extension (Canvas CAPTCHA)**: Generates random alphanumeric tokens rendered on HTML5 `<canvas>` with line noise and character rotation.

### 2. Key Questions / Analysis / Interpretation:
- **Q1. Are correct input types and attributes used?**  
  Yes. Dedicated input types (`email`, `tel`, `password`, `text`) are paired with `required`, `minlength`, `maxlength`, and `pattern`.
- **Q2. Are validation errors displayed near the relevant fields?**  
  Yes. Inline `.form-error` elements appear directly underneath the failing input field, providing clear, actionable feedback.
- **Q3. Is password strength checked?**  
  Yes. Evaluated dynamically on every keystroke and reflected via the visual strength meter.

### 3. Challenges Faced & Learning Outcomes:
Designing regular expressions that enforce security standards without rejecting valid student names; generating readable yet noise-protected canvas CAPTCHA codes.

**Conclusion**: Robust frontend validation with regular expressions, dynamic password meter, and canvas CAPTCHA was successfully completed.  
**Repository Link**: `https://github.com/Dev-Jay99/WDF`

---

## Practical 6: Rendering External JSON Data using Fetch API, Search and Filter

### AIM:
Display event lists, student profiles, notices, or FAQs from external JSON files using Fetch API. Implement dynamic rendering, search, filter, sorting, and pagination.

### 1. Implementation Details:
- **JSON Datasets**: Created 3 extensive datasets with 16+ records each: `data/faqs.json`, `data/events.json`, and `data/students.json`.
- **Asynchronous Fetch API**: Uses modern `async/await` syntax to retrieve JSON resources, handling loading states and HTTP status codes cleanly.
- **Search, Filter & Sort**: Implemented real-time multi-field search, dynamic category filter dropdowns, and sorting (A-Z, Z-A).
- **Client-Side Pagination**: Slices filtered records into configurable page sizes (3, 6, or 10 per page) with Next/Previous buttons and live page counters.
- **Offline LocalStorage Caching**: Retrieved JSON responses are cached in `localStorage`; if the network is disconnected, the cached dataset is rendered automatically with an offline badge.

### 2. Key Questions / Analysis / Interpretation:
- **Q1. How is JSON fetched, parsed, and rendered?**  
  `fetch(url)` retrieves the remote stream, `response.json()` parses the payload into a JavaScript array, and template literals dynamically construct and mount HTML cards into the DOM.
- **Q2. Which array methods are used for search, filter, sort, and pagination?**  
  `filter()`, `includes()`, `map()`, `sort()`, and `slice()`.
- **Q3. How are loading and error states handled?**  
  Loading banners are displayed during network fetch; network errors trigger accessible error alerts with fallback to cached localStorage data.

### 3. Challenges Faced & Learning Outcomes:
Synchronizing simultaneous search queries, category filters, and sorting controls without race conditions; handling pagination state when filters yield zero results.

**Conclusion**: External JSON consumption using Fetch API with live search, category filter, sorting, pagination, and offline caching was successfully achieved.  
**Repository Link**: `https://github.com/Dev-Jay99/WDF`

---

## Practical 7: PHP Form Processing with Server-Side Validation and CSV/JSON File Storage

### AIM:
Process submitted registration/contact form data using PHP. Validate and sanitize inputs on the server side and store records in CSV or JSON file format. Display success/error messages.

### 1. Implementation Details:
- **POST Submission & Sanitization**: Form data is ingested strictly via POST and sanitized using `htmlspecialchars()`, `filter_var()`, and string trimming in `contact-process.php`.
- **CSRF Token Protection**: Every form submission includes a cryptographically secure CSRF token validated server-side.
- **Safe Concurrent File Persistence (`flock`)**: Submissions are appended safely to both `contact-submissions.json` and `contact-submissions.csv` using exclusive file locking (`LOCK_EX`).
- **Intermediate Extension (Submissions Viewer)**: Built `view-submissions.php` displaying saved records from both CSV and JSON formats in responsive tables.

### 2. Key Questions / Analysis / Interpretation:
- **Q1. Is the form submitted using POST?**  
  Yes. Form data is submitted exclusively using the POST method to protect sensitive information from being exposed in URL query parameters.
- **Q2. Are inputs validated and sanitized server side?**  
  Yes. PHP performs rigorous server-side sanitization and validation, rejecting empty or malformed fields even if client-side validation is bypassed.
- **Q3. Is file writing handled safely?**  
  Yes. File pointers use `flock($fp, LOCK_EX)` to ensure that concurrent submissions do not corrupt file data.

### 3. Challenges Faced & Learning Outcomes:
Safely reading, appending, and re-writing JSON arrays concurrently without data corruption; properly formatting multi-line textarea messages into standard RFC 4180 CSV rows.

**Conclusion**: Secure server-side form processing storing data safely in both JSON and CSV formats with CSRF protection was successfully implemented.  
**Repository Link**: `https://github.com/Dev-Jay99/WDF`

---

## Practical 8: MySQL Schema Design, ER Model, PDO Connectivity, and Prepared Statements

### AIM:
Design the database for StudentHub with students, events and registrations. Create PHP database connection using MYSQL statements.

### 1. Implementation Details:
- **Normalized Schema (3NF)**: Engineered `sql/studenthub.sql` with 5 relational tables: `users`, `students`, `events`, `registrations`, and `audit_logs`.
- **Primary & Foreign Keys**: Primary keys with auto-increment, composite unique keys, foreign keys with cascading updates and deletes.
- **Dual Database Connectivity**:
  - `db.php`: MySQLi connection with strict error reporting.
  - `db_pdo.php`: PHP Data Objects (PDO) connection with prepared statement emulation disabled for security.
- **Diagnostic Verification**: Built `test_connection.php` testing both MySQLi and PDO engines.

### 2. Key Questions / Analysis / Interpretation:
- **Q1. Is the schema normalized properly?**  
  Yes. Normalized to Third Normal Form (3NF), separating authentication, student profiles, events, and registrations into independent tables.
- **Q2. Are primary keys and foreign keys correctly defined?**  
  Yes. Primary keys uniquely identify rows; foreign keys maintain referential integrity with cascading constraints.
- **Q3. Is the database connection handled securely?**  
  Yes. Connections use UTF-8 (`utf8mb4`), PDO exception error modes, and prepared statements preventing SQL injection.

### 3. Challenges Faced & Learning Outcomes:
Establishing composite unique keys preventing double event registrations; designing foreign key cascades without circular dependencies.

**Conclusion**: Normalized MySQL schema design with 5 relational tables, seed data, and secure MySQLi/PDO connections was successfully established.  
**Repository Link**: `https://github.com/Dev-Jay99/WDF`

---

## Practical 9: Secure User Registration with Database Insert, Duplicate Email Check, and Password Hashing

### AIM:
Implement secure user registration by inserting user details into MySQL using MySQLi. Validate input on frontend and backend, check duplicate email/username, hash passwords using password_hash(), and display appropriate messages.

### 1. Implementation Details:
- **Bcrypt Password Hashing**: Passwords are encrypted using `password_hash($password, PASSWORD_BCRYPT)` with cost factor 10.
- **Duplicate Collision Checks**: Checks for existing usernames, emails, and student enrollment numbers before insertion.
- **Atomic Database Transactions**: MySQLi transactions (`begin_transaction()`, `commit()`, `rollback()`) guarantee that user credentials and student records are inserted together atomically.
- **Audit Logging**: Successful registrations log a `USER_REGISTERED` action in the `audit_logs` table.

### 2. Key Questions / Analysis / Interpretation:
- **Q1. Is password_hash() used before storing passwords?**  
  Yes. Passwords are encrypted using PHP's native `password_hash()` with Bcrypt, generating 60-character salted hashes before database storage.
- **Q2. Is duplicate email/username checked?**  
  Yes. Prepared SELECT queries check for existing username or email records. If duplicates exist, informative warning notices are presented to the user.
- **Q3. Are frontend and backend validations consistent?**  
  Yes. Matching Regular Expressions and validation rules are executed on both client-side JavaScript and server-side PHP to guarantee data hygiene.

### 3. Challenges Faced & Learning Outcomes:
Synchronizing foreign-key-linked inserts across two tables within a single atomic database transaction; ensuring clean user feedback on collision errors.

**Conclusion**: Secure user registration featuring Bcrypt password hashing, duplicate verification, and database transactions was successfully implemented.  
**Repository Link**: `https://github.com/Dev-Jay99/WDF`

---

## Practical 10: Secure Login/Logout with Sessions, Role-Based Access, Timeout, and Remember-Me Token

### AIM:
Develop secure login and logout functionality using PHP sessions. Verify password using password_verify(), regenerate session ID after login, restrict dashboard access, implement role-based redirection, session timeout, and optional secure remember-me token.

### 1. Implementation Details:
- **Authentication**: Uses `password_verify()` to compare submitted passwords against stored Bcrypt hashes.
- **Session Security**: Invokes `session_regenerate_id(true)` upon successful login to prevent session fixation attacks.
- **15-Minute Inactivity Timeout**: Automatically logs out users if `time() - $_SESSION['last_activity'] > 900` seconds.
- **Role-Based Access Control (RBAC)**: Redirects `admin` users to `admin/dashboard.php` and `student` users to `index.php` / `profile.php`; unauthorized dashboard visits are blocked with HTTP 403 Forbidden.
- **Remember-Me Cookie Tokens**: Stores random SHA-256 hashed tokens in the database and sets secure, HttpOnly cookies for persistent 30-day login.

### 2. Key Questions / Analysis / Interpretation:
- **Q1. Are sessions started and destroyed securely?**  
  Yes. Sessions are initialized with `cookie_httponly` and destroyed during logout by unsetting `$_SESSION`, invalidating cookie headers, and calling `session_destroy()`.
- **Q2. Is password_verify() used correctly?**  
  Yes. `password_verify($password, $user['password'])` safely verifies credentials in constant time against timing attacks.
- **Q3. Is unauthorized dashboard access blocked?**  
  Yes. The guard function `require_admin()` inspects session role headers and blocks unauthenticated or non-admin users with HTTP 403 Forbidden.
- **Q4. Is session timeout implemented?**  
  Yes. The middleware evaluates `$_SESSION['last_activity']`; users inactive for >15 minutes are automatically signed out.

### 3. Challenges Faced & Learning Outcomes:
Configuring secure session cookie attributes (HttpOnly, SameSite) across local XAMPP environments; managing state transitions during auto-logout timeout events.

**Conclusion**: Secure authentication with role-based routing, session regeneration, 15-minute inactivity timeout, and remember-me tokens was successfully completed.  
**Repository Link**: `https://github.com/Dev-Jay99/WDF`

---

## Practical 11: Student Management CRUD with Search, Filter, and MySQLi Statements

### AIM:
Create a student management module to add, view, edit, delete, search and filter student records. All database operations must use MYSQLi statements and clear success/failure messages.

### 1. Implementation Details:
- **CRUD Operations**: Add (`student-add.php`), View (`students.php`), Edit (`student-edit.php`), and Delete (`student-delete.php`) using MySQLi prepared statements.
- **Search & Multi-Filter**: Real-time search across student name, enrollment ID, and email, alongside branch, year, and status dropdown filters.
- **Delete Confirmation Modal**: Accessible modal prompt confirming deletion and supporting both permanent delete and soft-delete (status = 'inactive').
- **CSV Export**: `student-export.php` streams all database student records into a downloadable CSV spreadsheet.
- **Seeded Dataset**: Database populated with 22 student records (exceeding minimum requirement of 20).

### 2. Key Questions / Analysis / Interpretation:
- **Q1. Are SELECT, INSERT, UPDATE, and DELETE operations working?**  
  Yes. All four CRUD operations execute using parameterized MySQLi prepared statements with explicit success and failure feedback banners.
- **Q2. Is search by name/email/course implemented?**  
  Yes. The search input binds wildcard parameters (`%keyword%`) against full name, student number, and email columns simultaneously.
- **Q3. Are delete operations confirmed before execution?**  
  Yes. Delete actions trigger an interactive confirmation modal displaying the target student's name and allowing selection between soft-delete and permanent delete.

### 3. Challenges Faced & Learning Outcomes:
Dynamically constructing parameterized SQL statements with variable WHERE conditions; building a reliable CSV export streaming directly to output buffers.

**Conclusion**: A complete Student Management CRUD module featuring search, multi-filter, pagination, modal deletion, and CSV export was successfully completed.  
**Repository Link**: `https://github.com/Dev-Jay99/WDF`

---

## Practical 12: Event Management CRUD with Poster Upload and File Validation

### AIM:
Develop event management functionality for admin users. Admin can add, view, update, delete, and upload event posters with file type and file size validation.

### 1. Implementation Details:
- **Poster File Upload Validation**:
  - MIME Type Inspection: Evaluated via `finfo(FILEINFO_MIME_TYPE)` strictly allowing `image/jpeg`, `image/png`, and `image/webp`.
  - Size Limit: Maximum 2 MB (2,097,152 bytes).
  - Secure Renaming: Generates cryptographically random filenames (e.g., `event_a7b8c9d0e1.jpg`) preventing path traversal and name collisions.
  - Upload Folder Security: `.htaccess` inside `uploads/` prevents script execution.
- **Event Lifecycle & Registration**: Admin CRUD management for events with category filtering, capacity tracking, and student one-click registration (`event-register.php`).

### 2. Key Questions / Analysis / Interpretation:
- **Q1. Are add, update, and delete functionalities implemented correctly?**  
  Yes. Administrators can create events with posters, edit metadata, and delete events (which automatically purges associated poster files from disk).
- **Q2. Are poster uploads validated for type and size?**  
  Yes. PHP validates files using genuine file inspection (not just extensions) and strictly rejects files exceeding 2 MB.
- **Q3. Are success and failure messages displayed?**  
  Yes. Flash messages provide feedback on validation errors, successful publications, updates, and cancellations.

### 3. Challenges Faced & Learning Outcomes:
Preventing malicious PHP file execution in upload directories; handling file cleanup on update/delete without leaving orphaned image files.

**Conclusion**: Event Management CRUD with strict file upload validation, secure image storage, and registration capabilities was successfully implemented.  
**Repository Link**: `https://github.com/Dev-Jay99/WDF`

---

## Practical 13: PHP JSON API and AJAX/Fetch-Based CRUD without Page Reload

### AIM:
Create simple PHP JSON API endpoints for events or students and consume them using Fetch API. Perform list, add, update, and delete operations asynchronously without full page reload.

### 1. Implementation Details:
- **RESTful API Endpoint (`api/events.php`)**:
  - Handles `GET`, `POST`, `PUT`, `DELETE` methods.
  - Returns proper HTTP status codes (200 OK, 201 Created, 400 Bad Request, 403 Forbidden, 404 Not Found, 500 Server Error).
  - Sets `Content-Type: application/json; charset=UTF-8` on all outputs.
- **SPA AJAX Interface (`events-ajax.php`)**:
  - Single-page application using `fetch()` to manage events without full page reloads.
  - 300ms debounced live search.
  - Dynamic in-place modal for Add/Edit operations and row-fading deletions with toast feedback.

### 2. Key Questions / Analysis / Interpretation:
- **Q1. Do PHP endpoints return valid JSON responses?**  
  Yes. All responses are encoded with `json_encode()` and sent with appropriate HTTP status codes (200, 201, 400, 403, 404, 500).
- **Q2. Are GET and POST requests handled properly?**  
  Yes. The API parses `$_SERVER['REQUEST_METHOD']`, reads raw JSON streams from `php://input`, and validates payloads before database execution.
- **Q3. Are loading, success, and error states shown on the frontend?**  
  Yes. Loading skeletons are rendered during network operations, success toasts confirm operations, and error banners report API failure reasons.

### 3. Challenges Faced & Learning Outcomes:
Reading raw JSON input from `php://input` for PUT and DELETE requests; synchronizing in-memory DOM table rows with asynchronous server responses.

**Conclusion**: Fully asynchronous CRUD operations with RESTful JSON API endpoints, Fetch API, and debounced search were successfully implemented with zero page reloads.  
**Repository Link**: `https://github.com/Dev-Jay99/WDF`

---

## Practical 14: Admin Dashboard with Role-Based Access Control, Dynamic Menu, and Audit Log

### AIM:
Develop an admin dashboard that displays user statistics, student count, event count and latest registrations. Restrict access to admin users only and show dynamic menu options based on role.

### 1. Implementation Details:
- **KPI Summary Cards**: Real-time aggregated database statistics (Total Students, Campus Events, Event Registrations, User Accounts).
- **Visual Analytics**: Proportional category distribution progress bars and registration breakdown.
- **Dynamic Role-Based Navigation**: Conditionally renders Admin Control Center, Student CRUD, and Audit Logs only for administrative sessions.
- **Security Audit Trail (`audit_logs`)**: Automatically logs user logins, logouts, record additions, modifications, deletions, and user status toggles with timestamps and client IP addresses.
- **Intermediate Extension (User Status Moderation)**: `admin/users.php` allows administrators to toggle user accounts between Active and Inactive.

### 2. Key Questions / Analysis / Interpretation:
- **Q1. Are dashboard values loaded dynamically from the database?**  
  Yes. KPI summary boxes and registration streams are queried live from MySQL tables on every dashboard load.
- **Q2. Is access restricted to admin users?**  
  Yes. `require_admin()` guards the dashboard. Unauthorized students or guests are intercepted and served an HTTP 403 Forbidden alert.
- **Q3. Are role-based menus working?**  
  Yes. The navigation bar conditionally renders administrative controls only when `$_SESSION['role'] === 'admin'`.
- **Q4. Are important admin actions recorded in audit_logs?**  
  Yes. The helper `log_audit()` writes administrative events into the `audit_logs` table, visible via the dedicated Audit Log viewer.

### 3. Challenges Faced & Learning Outcomes:
Writing high-performance SQL aggregation queries; designing an immutable security audit logging architecture for system traceability.

**Conclusion**: Role-based Admin Dashboard with real-time KPI metrics, dynamic menu switching, user status moderation, and audit trail logging was successfully implemented.  
**Repository Link**: `https://github.com/Dev-Jay99/WDF`

---

## Practical 15: Final Integration, Testing, Documentation, GitHub Submission, and Local Deployment

### AIM:
Integrate all modules into a single deployable StudentHub portal. Verify navigation, responsiveness, authentication, database operations, security checks, AJAX functionality, and documentation. Deploy locally using XAMPP/Apache virtual host and submit GitHub repository.

### 1. Implementation Details:
- **Full-Stack Portal Integration**: Master `index.php` connects all 14 preceding practicals into an intuitive, unified campus web portal.
- **Testing & Verification**: 100% pass rate on PHP syntax linting across all 34 scripts. Verified cross-browser responsiveness across mobile, tablet, and desktop viewports.
- **Security Checklists**: Validated CSRF tokens on all state-altering POST requests, 100% prepared statements preventing SQL injection, Bcrypt password hashing, session timeout enforcement, and upload directory protection.
- **Local Deployment**: Configured and tested under XAMPP (`c:\xampp\htdocs\studenthub\`) with database seeds in `sql/studenthub.sql`.
- **Git Version Control & Documentation**: Clean Git repository with structured commit history and complete `README.md`.

### 2. Key Questions / Analysis / Interpretation:
- **Q1. Are all pages properly linked and navigable?**  
  Yes. The global navigation bar, breadcrumbs, and inter-page links are verified with zero broken paths.
- **Q2. Are sessions, roles, validations, and database operations working end-to-end?**  
  Yes. End-to-end user workflows—from registration and login to student CRUD, event registration, poster upload, and admin audit logging—operate without errors.
- **Q3. Are security and accessibility checklists completed?**  
  Yes. Semantic landmarks, ARIA attributes, skip links, contrast ratios, CSRF tokens, Bcrypt hashing, and prepared statements satisfy all academic requirements.
- **Q4. Is project documentation sufficient for installation and testing?**  
  Yes. The `README.md` provides step-by-step XAMPP setup instructions, database import commands, architecture diagrams, and default login credentials.
- **Q5. Is the project repository clean and properly committed?**  
  Yes. The Git repository is structured with a clean commit history tracking all practical milestones.

### 3. Challenges Faced & Learning Outcomes:
Harmonizing frontend components, AJAX endpoints, and stateful PHP sessions into a unified, secure system. Gained end-to-end mastery of modern full-stack web engineering.

**Conclusion**: The StudentHub portal was successfully integrated, tested, and deployed locally under XAMPP. All 15 practical requirements of the CHARUSAT ITUE203 syllabus have been completely fulfilled.  
**Repository Link**: `https://github.com/Dev-Jay99/WDF`
