<?php
// Include database connection
require_once 'conn.php';

// Fetch total number of students
$sqlTotalStudents = "SELECT COUNT(*) AS total_students FROM Students";
$stmtTotalStudents = $conn->query($sqlTotalStudents);
$totalStudents = $stmtTotalStudents->fetch(PDO::FETCH_ASSOC)['total_students'];

// Fetch total number of courses
$sqlTotalCourses = "SELECT COUNT(*) AS total_courses FROM Courses";
$stmtTotalCourses = $conn->query($sqlTotalCourses);
$totalCourses = $stmtTotalCourses->fetch(PDO::FETCH_ASSOC)['total_courses'];

// Fetch total number of active exams
$sqlTotalActiveExams = "SELECT COUNT(*) AS total_active_exams FROM Exams WHERE status = 'active'";
$stmtTotalActiveExams = $conn->query($sqlTotalActiveExams);
$totalActiveExams = $stmtTotalActiveExams->fetch(PDO::FETCH_ASSOC)['total_active_exams'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div id="sidebar">
        <?php include 'slidebar.php'; ?> <!-- Include the sidebar -->
    </div>
    <div id="content" class="wrapper">
        <h1>Welcome to Admin Dashboard</h1>
        <div class="row">
            <div class="col-md-4">
                <a href="view_students.php" class="card-link">
                    <div class="card total-students">
                        <div class="card-body">
                            <h5 class="card-title">Total Students</h5>
                            <p class="card-text"><?php echo $totalStudents; ?></p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="view_courses.php" class="card-link">
                    <div class="card total-courses">
                        <div class="card-body">
                            <h5 class="card-title">Total Courses</h5>
                            <p class="card-text"><?php echo $totalCourses; ?></p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="view_exams.php" class="card-link">
                    <div class="card active-exams">
                        <div class="card-body">
                            <h5 class="card-title">Active Exams</h5>
                            <p class="card-text"><?php echo $totalActiveExams; ?></p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Add more cards for other statistics as needed -->
        </div>
    </div>
    
</body>
