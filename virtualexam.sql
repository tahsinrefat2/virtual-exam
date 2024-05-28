-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 05, 2024 at 04:20 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `virtualexam`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admins`
--

CREATE TABLE `Admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Admins`
--

INSERT INTO `Admins` (`admin_id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `announcement_title` varchar(255) NOT NULL,
  `announcement_content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcement_id`, `course_id`, `announcement_title`, `announcement_content`, `created_at`, `updated_at`) VALUES
(1, NULL, 'A1', 'This is first announcement.\r\nCSE299', '2024-03-28 17:14:52', '2024-03-28 17:14:52'),
(3, 1, 'A1', 'This is first announcement on cse299', '2024-03-28 17:34:42', '2024-03-28 17:34:42'),
(4, 1, 'a1', 'this is announcement 1', '2024-03-29 13:58:28', '2024-03-29 13:58:28'),
(5, 1, 'announcement 2', 'This is announcement 2 for CSE299.', '2024-03-30 06:00:30', '2024-03-30 06:00:30'),
(6, 1, 'demo post', 'this is a demo announcement', '2024-03-31 17:57:14', '2024-03-31 17:57:14');

-- --------------------------------------------------------

--
-- Table structure for table `Courses`
--

CREATE TABLE `Courses` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Courses`
--

INSERT INTO `Courses` (`course_id`, `course_name`) VALUES
(1, 'CSE299'),
(3, 'cse425'),
(5, 'cse225'),
(6, 'CSE445');

-- --------------------------------------------------------

--
-- Table structure for table `Exams`
--

CREATE TABLE `Exams` (
  `exam_id` int(11) NOT NULL,
  `exam_name` varchar(100) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `exam_start_time` datetime DEFAULT NULL,
  `exam_end_time` datetime DEFAULT NULL,
  `exam_duration` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Exams`
--

INSERT INTO `Exams` (`exam_id`, `exam_name`, `course_id`, `exam_start_time`, `exam_end_time`, `exam_duration`, `status`) VALUES
(1, 'quiz 1', 1, NULL, NULL, 3, 'inactive'),
(2, 'mid', 1, NULL, NULL, 5, 'active'),
(6, 'Final', 1, NULL, NULL, 1, 'active'),
(8, 'Quiz 1', 3, NULL, NULL, 15, 'inactive'),
(9, 'Quiz 1', 6, NULL, NULL, 1, 'inactive'),
(10, 'new exam', 1, '2024-04-27 23:13:00', '2024-04-27 23:20:00', 3, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `feedback_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `feedback` text NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `anonymous` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`feedback_id`, `student_id`, `feedback`, `name`, `anonymous`, `created_at`) VALUES
(1, 2, 'e1', 'emon', 0, '2024-04-14 16:36:08'),
(2, 2, 'e2', '', 1, '2024-04-14 16:36:17'),
(3, 2, 'sir er class bujhi na', '', 1, '2024-04-14 16:46:11'),
(4, 2, 'sir khub bhalo', 'emon', 0, '2024-04-14 16:46:34'),
(5, 1, 'project completed successfully!', 'refat', 0, '2024-04-27 13:21:01');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `question_text` text DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `option_1` text DEFAULT NULL,
  `option_2` text DEFAULT NULL,
  `option_3` text DEFAULT NULL,
  `option_4` text DEFAULT NULL,
  `correct_option` int(11) DEFAULT NULL
) ;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `exam_id`, `question_text`, `marks`, `option_1`, `option_2`, `option_3`, `option_4`, `correct_option`) VALUES
(1, 6, 'correct option is op3', 1, 'op1', 'op2', 'op3', 'op4', 3),
(2, 6, 'correct option is op2', 1, 'op1', 'op2', 'op3', 'op4', 2),
(3, 6, 'correct option is op1', 1, 'op1', 'op2', 'op3', 'op4', 1),
(4, 9, 'op1', 1, 'op1', 'op2', 'op3', 'op4', 1),
(5, 9, 'op2', 1, 'op1', 'op2', 'op3', 'op4', 2),
(6, 10, 'a1', 1, 'a1', 'a2', 'a3', 'a4', 1),
(7, 10, 'a2', 1, 'a1', 'a2', 'a3', 'a4', 2),
(8, 10, 'a3', 1, 'a1', 'a2', 'a3', 'a4', 3),
(9, 10, 'a4', 1, 'a1', 'a2', 'a3', 'a4', 4),
(10, 6, 'correct option is op4', 1, 'op1', 'op2', 'op3', 'op4', 4);

-- --------------------------------------------------------

--
-- Table structure for table `requested_students`
--

CREATE TABLE `requested_students` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `course_id` int(11) NOT NULL,
  `dob` date NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `gender` enum('Male','Female','Others') NOT NULL,
  `address` varchar(255) NOT NULL,
  `security_question` varchar(255) NOT NULL,
  `security_question_answer` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Results`
--

CREATE TABLE `Results` (
  `result_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Results`
--

INSERT INTO `Results` (`result_id`, `student_id`, `exam_id`, `score`) VALUES
(2, 2, 6, 2),
(3, 3, 6, 1),
(11, 10, 6, 0),
(16, 11, 6, 1),
(20, 1, 6, 3),
(21, 1, 10, 4);

-- --------------------------------------------------------

--
-- Table structure for table `Students`
--

CREATE TABLE `Students` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `dob` date NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `gender` enum('male','female','others') DEFAULT 'others',
  `address` varchar(255) DEFAULT NULL,
  `security_question` varchar(255) DEFAULT NULL,
  `security_question_answer` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Students`
--

INSERT INTO `Students` (`student_id`, `student_name`, `course_id`, `username`, `password`, `email`, `dob`, `phone_number`, `gender`, `address`, `security_question`, `security_question_answer`) VALUES
(1, 'refat', 1, 'refat', 'refat1', 'tahsinulrefat@northsouth.edu', '2000-05-14', '01832433260', 'male', 'Bashundhara R/A, Dhaka - 1229', 'What is your family name?', 'Karim'),
(2, 'emon', 1, 'emon', 'emon', NULL, '0000-00-00', NULL, 'male', NULL, NULL, NULL),
(3, 'rafid', 1, 'rafid', 'rafid', NULL, '0000-00-00', NULL, 'male', NULL, NULL, NULL),
(10, 'tomal', 1, 'tomal', 'tomal123', 'tomal@gmail.com', '1998-06-09', '123123', 'male', 'sadsd', 'sdfas', 'dsfas'),
(11, 'Al Amin', 1, 'alamin', 'alamin', 'alamin@gmail.com', '2000-06-04', '1231232', 'male', 'bashundhara', 'place of birth?', 'Naoga');

-- --------------------------------------------------------

--
-- Table structure for table `student_answers`
--

CREATE TABLE `student_answers` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `selected_option_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_answers`
--

INSERT INTO `student_answers` (`id`, `student_id`, `exam_id`, `question_id`, `selected_option_id`) VALUES
(13, 11, 6, 1, 1),
(14, 11, 6, 2, 2),
(15, 11, 6, 3, 3),
(23, 1, 6, 1, 3),
(24, 1, 6, 2, 2),
(25, 1, 6, 3, 1),
(26, 1, 10, 6, 1),
(27, 1, 10, 7, 2),
(28, 1, 10, 8, 3),
(29, 1, 10, 9, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admins`
--
ALTER TABLE `Admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `Courses`
--
ALTER TABLE `Courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `Exams`
--
ALTER TABLE `Exams`
  ADD PRIMARY KEY (`exam_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `exam_id` (`exam_id`);

--
-- Indexes for table `requested_students`
--
ALTER TABLE `requested_students`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `fk_course_id` (`course_id`);

--
-- Indexes for table `Results`
--
ALTER TABLE `Results`
  ADD PRIMARY KEY (`result_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `exam_id` (`exam_id`);

--
-- Indexes for table `Students`
--
ALTER TABLE `Students`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `exam_id` (`exam_id`),
  ADD KEY `question_id` (`question_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Admins`
--
ALTER TABLE `Admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `Courses`
--
ALTER TABLE `Courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `Exams`
--
ALTER TABLE `Exams`
  MODIFY `exam_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requested_students`
--
ALTER TABLE `requested_students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `Results`
--
ALTER TABLE `Results`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `Students`
--
ALTER TABLE `Students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `student_answers`
--
ALTER TABLE `student_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `Exams`
--
ALTER TABLE `Exams`
  ADD CONSTRAINT `exams_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `Courses` (`course_id`);

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `Students` (`student_id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`exam_id`);

--
-- Constraints for table `requested_students`
--
ALTER TABLE `requested_students`
  ADD CONSTRAINT `fk_course_id` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `Results`
--
ALTER TABLE `Results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `Students` (`student_id`),
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `Exams` (`exam_id`);

--
-- Constraints for table `Students`
--
ALTER TABLE `Students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `Courses` (`course_id`);

--
-- Constraints for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD CONSTRAINT `student_answers_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `Students` (`student_id`),
  ADD CONSTRAINT `student_answers_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `Exams` (`exam_id`),
  ADD CONSTRAINT `student_answers_ibfk_3` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
