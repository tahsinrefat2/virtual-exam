<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exams</title>
    <!--<link rel="stylesheet" href="styles.css">  Include your custom CSS file -->
</head>
<body>
<?php include 'floating_profile_button.php'; ?>
<div id="sidebar">
    <?php include 'student_slidebar.php'; ?>
</div>

<div id="content">
    <h2>Exams</h2>
    <div class="exam-cards-container">
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

        // Retrieve exams for the courses the student is enrolled in
        $sql = "SELECT * FROM Exams WHERE course_id IN (SELECT course_id FROM Students WHERE student_id = :student_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        $exams = $stmt->fetchAll();

        // Display exams as cards
        foreach ($exams as $exam) {
            // Check if the exam status is active
            if ($exam['status'] == 'active') {
                echo '<div class="exam-card">';
                echo '<div class="exam-card-content">';
                echo '<h3 class="exam-card-title">' . $exam['exam_name'] . '</h3>';
                echo '<p>Course: ' . getCourseName($exam['course_id'], $conn) . '</p>'; // Assuming a function to get course name
                echo '<p>Time Limit: ' . $exam['exam_duration'] . ' minutes</p>';
                echo '<div class="exam-card-links">';
                echo '<a href="student_attend_exam.php?exam_id=' . $exam['exam_id'] . '" class="exam-card-link">Attend Exam</a>';
                echo '<a href="student_view_result.php?exam_id=' . $exam['exam_id'] . '" class="exam-card-link">View Result</a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        }

        // Close the database connection
        unset($conn);

        // Function to get course name by course ID
        function getCourseName($course_id, $conn) {
            $sql = "SELECT course_name FROM Courses WHERE course_id = :course_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':course_id', $course_id);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['course_name'];
        }
        ?>
    </div>
</div>
<?php include 'clock.php'; ?>
</body>
</html>
