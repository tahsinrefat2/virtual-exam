<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Announcements</title>
    <!--<link rel="stylesheet" href="styles.css">-->
</head>
<body>
<?php include 'floating_profile_button.php'; ?>
<div id="sidebar">
    <?php include 'student_slidebar.php'; ?>
</div>
<div id="content" class="wrapper">
<?php
// Include the database connection file
require_once 'conn.php';

// Start session
session_start();

// Check if student is not logged in
if (!isset($_SESSION["student_id"])) {
    // Redirect to student login page
    header("location: student_login.php");
    exit;
}

// Retrieve student ID from session
$student_id = $_SESSION["student_id"];

// Check if course_id is provided in the URL
if (!isset($_GET['course_id'])) {
    echo "Course ID not provided.";
    exit;
}

// Retrieve course ID from the URL parameter
$course_id = $_GET['course_id'];

// Check if the student is enrolled in the provided course
$sql_check_enrollment = "SELECT * FROM Students WHERE student_id = :student_id AND course_id = :course_id";
$stmt_check_enrollment = $conn->prepare($sql_check_enrollment);
$stmt_check_enrollment->bindParam(':student_id', $student_id);
$stmt_check_enrollment->bindParam(':course_id', $course_id);
$stmt_check_enrollment->execute();

// If not enrolled, display an error message
if ($stmt_check_enrollment->rowCount() == 0) {
    echo "You are not enrolled in this course.";
    exit;
}

// Retrieve announcements for the provided course from the database
$sql_announcements = "SELECT announcement_title, announcement_content, created_at FROM Announcements WHERE course_id = :course_id ORDER BY created_at DESC";
$stmt_announcements = $conn->prepare($sql_announcements);
$stmt_announcements->bindParam(':course_id', $course_id);
$stmt_announcements->execute();
$announcements = $stmt_announcements->fetchAll();

// Display announcements
if ($stmt_announcements->rowCount() > 0) {
    echo "<h2>Announcements</h2>";
    foreach ($announcements as $announcement) {
        echo '<div class="announcement">';
        echo '<h3>' . $announcement['announcement_title'] . '</h3>';
        echo '<p>' . $announcement['announcement_content'] . '</p>';
        echo '<p><em>Posted on: ' . $announcement['created_at'] . '</em></p>';
        echo '</div>';
    }
} else {
    echo "No announcements available for this course.";
}

// Close the database connection
unset($conn);
?>
</div>
<?php include 'clock.php'; ?>
</body>
</html>
