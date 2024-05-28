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

// Check if exam_id parameter is set in the URL
if (!isset($_GET['exam_id']) || empty($_GET['exam_id'])) {
    // Redirect to error page if exam_id is not provided
    header("location: error.php");
    exit;
}

// Fetch exam details
$exam_id = $_GET['exam_id'];

$sql_exam = "SELECT e.exam_name, c.course_name
             FROM exams e
             INNER JOIN courses c ON e.course_id = c.course_id
             WHERE e.exam_id = :exam_id";
$stmt_exam = $conn->prepare($sql_exam);
$stmt_exam->bindParam(':exam_id', $exam_id);
$stmt_exam->execute();
$exam_details = $stmt_exam->fetch(PDO::FETCH_ASSOC);

// Fetch results for the selected exam
$sql_results = "SELECT r.*, s.student_name
                FROM Results r
                INNER JOIN Students s ON r.student_id = s.student_id
                WHERE r.exam_id = :exam_id
                ORDER BY r.score DESC";
$stmt_results = $conn->prepare($sql_results);
$stmt_results->bindParam(':exam_id', $exam_id);
$stmt_results->execute();
$results = $stmt_results->fetchAll(PDO::FETCH_ASSOC);


// Close connection
unset($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Results by Exam</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* style_results_exam.css */

.wrapper {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

h1 {
    color: #333;
    text-align: center;
    margin-bottom: 30px;
    font-size: 36px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #666;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid #ccc;
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f9f9f9;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #ddd;
}

p {
    color: #666;
    text-align: center;
    font-size: 18px;
}

    </style>
</head>
<body>
<div id="sidebar">
    <?php include 'slidebar.php'; ?> <!-- Include the sidebar -->
</div>
<div id="content" class="wrapper">
    <h1>View Results for <?php echo $exam_details['exam_name']; ?></h1>
    <h2>Course: <?php echo $exam_details['course_name']; ?></h2>
    <?php if (!empty($results)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Score</th>
                    <th>Rank</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; ?>
                <?php foreach ($results as $result): ?>
                    <tr>
                        <td><?php echo $result['student_id']; ?></td>
                        <td><?php echo $result['student_name']; ?></td>
                        <td><?php echo $result['score']; ?></td>
                        <td><?php echo $rank++; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No results found for this exam.</p>
    <?php endif; ?>
</div>
</body>
</html>
