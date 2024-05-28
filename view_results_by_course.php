<?php
// Include the database connection file
require_once 'conn.php';

// Initialize session
session_start();

// Check if admin is logged in, redirect to login page if not logged in
if (!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true) {
    header("location: admin_login.php");
    exit;
}

// Fetch list of courses
$sql_courses = "SELECT course_id, course_name FROM courses";
$stmt_courses = $conn->prepare($sql_courses);
$stmt_courses->execute();
$courses = $stmt_courses->fetchAll(PDO::FETCH_ASSOC);

// Initialize exams array
$exams = [];

// If form is submitted with selected course
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];

    // Fetch exams for the selected course
    $sql_exams = "SELECT exam_id, exam_name FROM exams WHERE course_id = :course_id";
    $stmt_exams = $conn->prepare($sql_exams);
    $stmt_exams->bindParam(':course_id', $course_id);
    $stmt_exams->execute();
    $exams = $stmt_exams->fetchAll(PDO::FETCH_ASSOC);
}

// Close connection
unset($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Results by Exams</title>
    <link rel="stylesheet" href="style_results.css">
    
</head>

<body>
    <div id="sidebar">
        <?php include 'slidebar.php'; ?> <!-- Include the sidebar -->
    </div>
    <div id="content" class="wrapper">
        <h1>View Results by Exams</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="course_id">Select Course:</label>
            <select name="course_id" id="course_id" onchange="this.form.submit()">
                <option value="">Select Course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo $course['course_id']; ?>"><?php echo $course['course_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <?php if (!empty($exams)): ?>
            <h2>Exams</h2>
            <ul>
                <?php foreach ($exams as $exam): ?>
                    <li>
                        <?php echo $exam['exam_name']; ?>
                        <a href="view_results_by_exam.php?exam_id=<?php echo $exam['exam_id']; ?>">View Results</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>

</html>
