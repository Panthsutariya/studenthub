-- =====================================================================
-- CHARUSAT - ITUE203 Web Development Frameworks
-- Project: StudentHub Portal Database Schema & Seeds
-- Practicals 8, 9, 10, 11, 12, 14
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `studenthub`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `studenthub`;

-- ---------------------------------------------------------------------
-- Table: users (Auth, Roles: Admin/Student, Session & Remember Me)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'student') NOT NULL DEFAULT 'student',
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `remember_token` VARCHAR(255) NULL,
  `last_login` DATETIME NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_username` (`username`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: students (Comprehensive student records for CRUD Practical 11)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `students` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_number` VARCHAR(30) NOT NULL,
  `full_name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `mobile` VARCHAR(15) NOT NULL,
  `branch` VARCHAR(100) NOT NULL,
  `year` VARCHAR(20) NOT NULL,
  `gender` ENUM('Male', 'Female', 'Other') NOT NULL,
  `user_id` INT UNSIGNED NULL,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_students_student_number` (`student_number`),
  UNIQUE KEY `uq_students_email` (`email`),
  KEY `idx_students_branch` (`branch`),
  KEY `idx_students_year` (`year`),
  CONSTRAINT `fk_students_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: events (Events with category, poster, status for Practical 12)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `events` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT NULL,
  `category` VARCHAR(50) NOT NULL DEFAULT 'Technical',
  `event_date` DATETIME NOT NULL,
  `location` VARCHAR(200) NOT NULL,
  `capacity` INT UNSIGNED NULL DEFAULT 100,
  `poster` VARCHAR(255) NULL,
  `status` ENUM('open', 'closed', 'cancelled') NOT NULL DEFAULT 'open',
  `created_by` INT UNSIGNED NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_events_event_date` (`event_date`),
  KEY `idx_events_category` (`category`),
  KEY `idx_events_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: registrations (Student Event Registrations)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `registrations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` INT UNSIGNED NOT NULL,
  `event_id` INT UNSIGNED NOT NULL,
  `status` ENUM('registered', 'attended', 'cancelled') NOT NULL DEFAULT 'registered',
  `registered_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_registrations_student_event` (`student_id`, `event_id`),
  KEY `idx_registrations_event_status` (`event_id`, `status`),
  CONSTRAINT `fk_registrations_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_registrations_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: audit_logs (Traceability & Security Tracking for Practical 14)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NULL,
  `username` VARCHAR(50) NOT NULL,
  `action` VARCHAR(100) NOT NULL,
  `details` TEXT NULL,
  `ip_address` VARCHAR(45) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_audit_logs_action` (`action`),
  KEY `idx_audit_logs_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- SEED DATA
-- Default Passwords:
-- Admin: Admin@123
-- Student: Student@123
-- =====================================================================

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `status`) VALUES
(1, 'admin', 'admin@charusat.edu.in', '$2y$10$7D.w2Ov7jeHJ32P0y9xhYOreEOT7aBfmGbe6l15RNYma0zmsPBSta', 'admin', 'active'),
(2, 'aarav', 'aarav.patel@charusat.edu.in', '$2y$10$B7s6hluEGhtui407WLIaL.z3dp1WDwyYU8K.UZgtyvt49uN2vFlsu', 'student', 'active'),
(3, 'diya', 'diya.sharma@charusat.edu.in', '$2y$10$B7s6hluEGhtui407WLIaL.z3dp1WDwyYU8K.UZgtyvt49uN2vFlsu', 'student', 'active'),
(4, 'rohan', 'rohan.mehta@charusat.edu.in', '$2y$10$B7s6hluEGhtui407WLIaL.z3dp1WDwyYU8K.UZgtyvt49uN2vFlsu', 'student', 'active')
ON DUPLICATE KEY UPDATE `username` = VALUES(`username`);

-- 22 Seeded Student Records (exceeds Practical 11 requirement of min 20 records)
INSERT INTO `students` (`id`, `student_number`, `full_name`, `email`, `mobile`, `branch`, `year`, `gender`, `user_id`, `status`) VALUES
(1, '23IT001', 'Aarav Patel', 'aarav.patel@charusat.edu.in', '9876543210', 'Information Technology', '3rd Year', 'Male', 2, 'active'),
(2, '23IT002', 'Diya Sharma', 'diya.sharma@charusat.edu.in', '9823456781', 'Information Technology', '3rd Year', 'Female', 3, 'active'),
(3, '23CE003', 'Rohan Mehta', 'rohan.mehta@charusat.edu.in', '9712345678', 'Computer Engineering', '3rd Year', 'Male', 4, 'active'),
(4, '23CE004', 'Ananya Desai', 'ananya.desai@charusat.edu.in', '9898123456', 'Computer Engineering', '2nd Year', 'Female', NULL, 'active'),
(5, '23IT005', 'Kavya Joshi', 'kavya.joshi@charusat.edu.in', '9879543210', 'Information Technology', '2nd Year', 'Female', NULL, 'active'),
(6, '24IT006', 'Dev Shah', 'dev.shah@charusat.edu.in', '9724123890', 'Information Technology', '1st Year', 'Male', NULL, 'active'),
(7, '22CE007', 'Isha Trivedi', 'isha.trivedi@charusat.edu.in', '9825012345', 'Computer Engineering', '4th Year', 'Female', NULL, 'active'),
(8, '23EC008', 'Harsh Varma', 'harsh.varma@charusat.edu.in', '9909012345', 'Electronics & Comm.', '3rd Year', 'Male', NULL, 'active'),
(9, '23ME009', 'Nidhi Dave', 'nidhi.dave@charusat.edu.in', '9737112233', 'Mechanical Engineering', '3rd Year', 'Female', NULL, 'active'),
(10, '24CE010', 'Yash Parikh', 'yash.parikh@charusat.edu.in', '9824889900', 'Computer Engineering', '1st Year', 'Male', NULL, 'active'),
(11, '22IT011', 'Pooja Bhatt', 'pooja.bhatt@charusat.edu.in', '9913224455', 'Information Technology', '4th Year', 'Female', NULL, 'active'),
(12, '23CL012', 'Siddharth Patel', 'siddharth.patel@charusat.edu.in', '9879001122', 'Civil Engineering', '3rd Year', 'Male', NULL, 'active'),
(13, '23IT013', 'Tanvi Rawal', 'tanvi.rawal@charusat.edu.in', '9825556677', 'Information Technology', '3rd Year', 'Female', NULL, 'active'),
(14, '23CE014', 'Manan Kothari', 'manan.kothari@charusat.edu.in', '9714887766', 'Computer Engineering', '2nd Year', 'Male', NULL, 'active'),
(15, '24IT015', 'Rhea Soni', 'rhea.soni@charusat.edu.in', '9898009988', 'Information Technology', '1st Year', 'Female', NULL, 'active'),
(16, '22CE016', 'Karan Chauhan', 'karan.chauhan@charusat.edu.in', '9824003322', 'Computer Engineering', '4th Year', 'Male', NULL, 'active'),
(17, '23EE017', 'Bhavya Jani', 'bhavya.jani@charusat.edu.in', '9727114488', 'Electrical Engineering', '2nd Year', 'Male', NULL, 'active'),
(18, '23IT018', 'Shruti Pandya', 'shruti.pandya@charusat.edu.in', '9909332211', 'Information Technology', '3rd Year', 'Female', NULL, 'active'),
(19, '23CE019', 'Aditya Nair', 'aditya.nair@charusat.edu.in', '9879776655', 'Computer Engineering', '3rd Year', 'Male', NULL, 'active'),
(20, '24CE020', 'Priya Goswami', 'priya.goswami@charusat.edu.in', '9825112299', 'Computer Engineering', '1st Year', 'Female', NULL, 'active'),
(21, '22IT021', 'Chirag Solanki', 'chirag.solanki@charusat.edu.in', '9712998877', 'Information Technology', '4th Year', 'Male', NULL, 'active'),
(22, '23IT022', 'Jhanvi Shah', 'jhanvi.shah@charusat.edu.in', '9898443322', 'Information Technology', '2nd Year', 'Female', NULL, 'active')
ON DUPLICATE KEY UPDATE `full_name` = VALUES(`full_name`);

-- Events Seed Data
INSERT INTO `events` (`id`, `title`, `description`, `category`, `event_date`, `location`, `capacity`, `poster`, `status`, `created_by`) VALUES
(1, 'Hackathon FTE 2026', '24-hour national level hackathon solving real-world challenges in AI, Web and Cloud.', 'Technical', '2026-11-15 09:00:00', 'Seminar Hall 1, FTE Building', 150, 'uploads/events/hackathon.png', 'open', 1),
(2, 'Spoural Cultural Fest', 'Annual inter-college cultural event featuring music, drama, fine arts and dance competitions.', 'Cultural', '2026-12-05 17:00:00', 'University Central Ground', 500, 'uploads/events/spoural.png', 'open', 1),
(3, 'Web & Cloud Architecture Workshop', 'Hands-on session exploring PHP 8, REST APIs, Microservices, and Docker containerization.', 'Workshop', '2026-11-02 10:00:00', 'Lab 302, Computer Dept', 60, 'uploads/events/workshop.png', 'open', 1),
(4, 'Inter-Department Cricket Tournament', 'Annual sports festival cricket league matches between FTE branches.', 'Sports', '2026-11-20 08:30:00', 'Sports Complex Oval', 80, 'uploads/events/cricket.png', 'open', 1),
(5, 'AI & Robotics Colloquium', 'Industry keynote sessions on generative AI, autonomous robotics, and IoT systems.', 'Seminar', '2026-11-28 11:00:00', 'Auditorium FTE', 200, 'uploads/events/ai_seminar.png', 'open', 1),
(6, 'Alumni Mentorship Conclave', 'Network with prominent alumni working in top tech companies across the globe.', 'Seminar', '2026-12-12 14:00:00', 'Conference Room B', 100, 'uploads/events/alumni.png', 'open', 1)
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

-- Registrations Seed Data
INSERT INTO `registrations` (`id`, `student_id`, `event_id`, `status`, `registered_at`) VALUES
(1, 1, 1, 'registered', '2026-10-10 10:00:00'),
(2, 2, 1, 'registered', '2026-10-10 11:30:00'),
(3, 3, 1, 'registered', '2026-10-11 09:15:00'),
(4, 1, 3, 'registered', '2026-10-12 14:20:00'),
(5, 4, 2, 'registered', '2026-10-13 16:45:00'),
(6, 5, 2, 'registered', '2026-10-14 11:10:00')
ON DUPLICATE KEY UPDATE `status` = VALUES(`status`);

-- Audit Logs Seed Data
INSERT INTO `audit_logs` (`id`, `user_id`, `username`, `action`, `details`, `ip_address`, `created_at`) VALUES
(1, 1, 'admin', 'SYSTEM_INIT', 'StudentHub database and seed records initialized successfully.', '127.0.0.1', '2026-10-23 09:00:00'),
(2, 1, 'admin', 'LOGIN', 'Admin user logged in.', '127.0.0.1', '2026-10-23 09:15:00'),
(3, 2, 'aarav', 'REGISTER', 'Student registered for Hackathon FTE 2026.', '127.0.0.1', '2026-10-23 09:30:00')
ON DUPLICATE KEY UPDATE `action` = VALUES(`action`);
