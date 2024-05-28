<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Classmates</title>
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

// Retrieve student information for the provided course from the database
$sql_students = "SELECT * FROM Students WHERE course_id = :course_id AND student_id != :student_id";
$stmt_students = $conn->prepare($sql_students);
$stmt_students->bindParam(':course_id', $course_id);
$stmt_students->bindParam(':student_id', $student_id);
$stmt_students->execute();
$students = $stmt_students->fetchAll();

// Display student information
if ($stmt_students->rowCount() > 0) {
    echo "<h2>Classmates</h2>";
    foreach ($students as $student) {
        echo '<div class="student-card">';
        echo '<p><strong>Name:</strong> ' . $student['student_name'] . '</p>';
        echo '<p><strong>Email:</strong> <a href="mailto:' . $student['email'] . '">' . $student['email'] . '</a></p>';
        // You can display more information here as needed
        echo '</div>';
    }
} else {
    echo "No classmates found for this course.";
}

// Close the database connection
unset($conn);
?>

</div>
<?php include 'clock.php'; ?>
</body>
</html>
