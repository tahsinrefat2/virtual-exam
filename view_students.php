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

// Retrieve all students and their course information
$sql = "SELECT Students.student_id, Students.student_name, Students.username, Courses.course_name 
        FROM Students 
        INNER JOIN Courses ON Students.course_id = Courses.course_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Students</title>
     <!-- Include DataTables CSS -->
     <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <!-- Include your custom CSS file -->
    <link rel="stylesheet" href="styles.css">

    <!-- Include jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Include DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

  
</head>
<body>
    <div id="sidebar">
        <?php include 'slidebar.php'; ?> <!-- Include the sidebar -->
    </div>
    <div id="content" class="wrapper">
        <h2>View Students</h2>
        <table id="studentsTable" class="table">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Username</th>
                    <th>Course Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->rowCount() > 0) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . $row['student_name'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['course_name'] . "</td>";
                        echo "<td>";
                        echo "<a href='remove_student.php?student_id=" . $row['student_id'] . "' class='action-btn'' onclick='return confirm(\"Are you sure you want to delete this student?\")'>Remove</a>";
                        echo "<a href='view_student_details.php?student_id=" . $row['student_id'] . "'' class='action-btn'>View Details</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No students found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('#studentsTable').DataTable();
        });
    </script>
</body>

</html>
