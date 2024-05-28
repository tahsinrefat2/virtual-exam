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

// Check if course_id is set in the URL
if (isset($_GET["course_id"]) && !empty(trim($_GET["course_id"]))) {
    // Prepare a select statement to fetch course name
    $sql_course = "SELECT course_name FROM Courses WHERE course_id = :course_id";
    if ($stmt_course = $conn->prepare($sql_course)) {
        // Bind course_id parameter
        $stmt_course->bindParam(":course_id", $param_course_id, PDO::PARAM_INT);
        // Set parameters
        $param_course_id = trim($_GET["course_id"]);
        // Attempt to execute the prepared statement
        if ($stmt_course->execute()) {
            // Fetch course name
            if ($stmt_course->rowCount() == 1) {
                $row = $stmt_course->fetch(PDO::FETCH_ASSOC);
                $course_name = $row["course_name"];
            } else {
                // Redirect to error page if course not found
                header("location: error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        // Close statement
        unset($stmt_course);
    }
} else {
    // Redirect to error page if course_id is not provided in the URL
    header("location: error.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Students by Course</title>
    <style>
        

        #studentDetailsPanel {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            z-index: 9999;
            display: none;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        #content {
            padding: 20px;
            margin-left: 250px;
            transition: margin-left 0.5s;
        }

        
        /* Student card styling */
        .student {
            margin-bottom: 10px;
            border: 1px solid #ccc;
            padding: 10px;
            background-color: white;
            border-radius: 5px;
            transition: transform 0.3s ease-in-out;
        }

        .student:hover {
            transform: scale(1.02);
        }
    </style>
</head>

<body>
    <div id="sidebar">
        <?php include 'slidebar.php'; ?> <!-- Include the sidebar -->
    </div>
    <div id="content" class="wrapper">
        <h2>Students Enrolled in <?php echo $course_name; ?></h2>
        <?php
        // Prepare a select statement to fetch students enrolled in the course
        $sql_students = "SELECT student_id, student_name FROM Students WHERE course_id = :course_id";
        if ($stmt_students = $conn->prepare($sql_students)) {
            // Bind course_id parameter
            $stmt_students->bindParam(":course_id", $param_course_id, PDO::PARAM_INT);
            // Attempt to execute the prepared statement
            if ($stmt_students->execute()) {
                // Check if there are any students enrolled
                if ($stmt_students->rowCount() > 0) {
                    // Loop through each student and display their name
                    while ($row = $stmt_students->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div class='student' onclick='showStudentDetails(\"" . $row["student_name"] . "\")'>";
                        echo "<p>Name: " . $row["student_name"] . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "No students enrolled in this course.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            unset($stmt_students);
        }
        ?>
    </div>

    <!-- The pop-up panel -->
    <div id="studentDetailsPanel">
        <div id="studentDetailsContent"></div>
        <button onclick="closeStudentDetailsPanel()">Close</button>
    </div>

    <script>
        // Function to fetch and display student details
        function showStudentDetails(studentName) {
            // AJAX request to fetch student details
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        document.getElementById("studentDetailsContent").innerHTML = xhr.responseText;
                        document.getElementById("studentDetailsPanel").style.display = "block";
                    } else {
                        alert('Error: ' + xhr.status);
                    }
                }
            };
            xhr.open('GET', 'get_student_details.php?student_name=' + encodeURIComponent(studentName), true);
            xhr.send();
        }

        // Function to close the pop-up panel
        function closeStudentDetailsPanel() {
            document.getElementById("studentDetailsPanel").style.display = "none";
        }
    </script>
</body>

</html>