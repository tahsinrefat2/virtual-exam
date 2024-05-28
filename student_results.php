<?php
// Include the database connection file
require_once 'conn.php';

// Initialize session (if not already started)
session_start();

// Check if student is logged in, redirect to login page if not logged in
if (!isset($_SESSION["student_id"])) {
    header("location: student_login.php");
    exit; // Exit after redirection
}

// Fetch exam results for the logged-in student with calculated ranks, exam names, and course names
$sql = "SELECT r.*, e.exam_name, c.course_name,
        (SELECT COUNT(*) FROM results AS r2 WHERE r2.exam_id = r.exam_id AND r2.score >= r.score) AS rank 
        FROM results AS r 
        JOIN exams AS e ON r.exam_id = e.exam_id 
        JOIN courses AS c ON e.course_id = c.course_id
        WHERE r.student_id = :student_id 
        ORDER BY r.exam_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':student_id', $_SESSION['student_id']);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Close connection
unset($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Exam Results</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php include 'floating_profile_button.php'; ?>
<div id="sidebar">
        <?php include 'student_slidebar.php'; ?> <!-- Include the sidebar -->
    </div>

    <div id="content" class="wrapper">
    <h2>Exam Results</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Exam Name</th>
                <th>Course Name</th>
                <th>Score</th>
                <th>Rank</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $result) : ?>
                <tr>
                    <td><?php echo $result['exam_name']; ?></td>
                    <td><?php echo $result['course_name']; ?></td>
                    <td><?php echo $result['score']; ?></td>
                    <td><?php echo $result['rank']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php include 'clock.php'; ?>
</body>

</html>
