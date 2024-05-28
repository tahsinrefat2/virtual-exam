<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolled Courses</title>
    <!--<link rel="stylesheet" href="styles.css">  Include your custom CSS file -->
</head>
<body>

<?php include 'floating_profile_button.php'; ?>

<div id="sidebar">
    <?php include 'student_slidebar.php'; ?>
   
</div>
<div id="content">
    <h2>Enrolled Courses</h2>
    
    <div class="course-cards-container">
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

        // Retrieve enrolled courses for the student from the database
        $sql = "SELECT * FROM Courses WHERE course_id IN (SELECT course_id FROM Students WHERE student_id = :student_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        $courses = $stmt->fetchAll();

        // Display enrolled courses as cards
        foreach ($courses as $course) {
            echo '<div class="course-card">';
            echo '<div class="course-card-content">';
            echo '<h3 class="course-card-title">' . $course['course_name'] . '</h3>';
            echo '<div class="course-card-links">';
            echo '<a href="student_view_coursemates.php?course_id=' . $course['course_id'] . '" class="course-card-link">View Coursemates</a>';
            echo '<a href="student_view_announcements.php?course_id=' . $course['course_id'] . '" class="course-card-link">View Announcements</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        // Close the database connection
        unset($conn);
        ?>
    </div>
</div>
<?php include 'clock.php';?>
</body>
</html>
