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

// Function to delete exam
function deleteExam($conn, $exam_id)
{
    $sql = "DELETE FROM Exams WHERE exam_id = :exam_id";
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameter
        $stmt->bindParam(":exam_id", $exam_id, PDO::PARAM_INT);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

// Function to change exam status
function changeExamStatus($conn, $exam_id, $status)
{
    $sql = "UPDATE Exams SET status = :status WHERE exam_id = :exam_id";
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bindParam(":exam_id", $exam_id, PDO::PARAM_INT);
        $stmt->bindParam(":status", $status, PDO::PARAM_STR);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

// Check if exam ID is provided for deletion
if (isset($_GET["delete_exam_id"])) {
    $delete_exam_id = $_GET["delete_exam_id"];
    if (deleteExam($conn, $delete_exam_id)) {
        // Exam deleted successfully, redirect to view exams page
        header("location: view_exams.php");
        exit;
    } else {
        echo "Failed to delete exam.";
    }
}

// Check if exam ID and status are provided for status change
if (isset($_GET["exam_id"]) && isset($_GET["status"])) {
    $exam_id = $_GET["exam_id"];
    $status = $_GET["status"];
    if (changeExamStatus($conn, $exam_id, $status)) {
        // Status changed successfully
        if ($status == 'active') {
            echo "<script>alert('Exam is now active.');</script>";
        }
        // Redirect to the same page
        header("location: view_exams.php");
        exit;
    } else {
        echo "Failed to change exam status.";
    }
}

// Fetch all exams with their course names
$sql = "SELECT Exams.exam_id, Exams.exam_name, Exams.status, Courses.course_name 
        FROM Exams 
        INNER JOIN Courses ON Exams.course_id = Courses.course_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Exams</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
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
        <h2>View Exams</h2>
        <table id="examsTable">
            <thead>
                <tr>
                    <th>Exam Name</th>
                    <th>Course Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->rowCount() > 0) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $status_class = $row['status'] == 'active' ? 'active' : 'inactive';
                        $status_text = ucfirst($row['status']);
                        echo "<tr>";
                        echo "<td>" . $row['exam_name'] . "</td>";
                        echo "<td>" . $row['course_name'] . "</td>";
                        echo "<td><span class='status-btn $status_class' data-exam-id='{$row['exam_id']}' data-status='{$row['status']}'>$status_text</span></td>";
                        echo "<td>
                                <a href='view_questions.php?exam_id=" . $row['exam_id'] . "' class='action-btn'>View Questions</a>
                                <a href='add_question.php?exam_id=" . $row['exam_id'] . "' class='action-btn'>Add Question</a>
                                <a href='view_results_by_exam.php?exam_id=" . $row['exam_id'] . "' class='action-btn'>View Result</a>
                                <a href='view_exams.php?delete_exam_id=" . $row['exam_id'] . "' onclick='return confirm(\"Are you sure you want to delete this exam?\");' class='action-btn'>Delete Exam</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No exams found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('#examsTable').DataTable();
        });

        function deleteExam(examId) {
            if (confirm("Are you sure you want to delete this exam?")) {
                window.location.href = 'view_exams.php?delete_exam_id=' + examId;
            }
        }

        $(document).on('click', '.status-btn', function() {
            var examId = $(this).data('exam-id');
            var currentStatus = $(this).data('status');
            var newStatus = currentStatus === 'active' ? 'inactive' : 'active';
            if (confirm("Are you sure you want to change the status to " + newStatus + "?")) {
                window.location.href = 'view_exams.php?exam_id=' + examId + '&status=' + newStatus;
            }
        });
    </script>
</body>

</html>

</html>
