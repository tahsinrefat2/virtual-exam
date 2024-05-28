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

// Check if student_id is set in GET parameters
if (isset($_GET["student_id"]) && !empty(trim($_GET["student_id"]))) {
    // Prepare a select statement
    $sql = "SELECT * FROM Students WHERE student_id = :student_id";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":student_id", $param_student_id, PDO::PARAM_INT);

        // Set parameters
        $param_student_id = trim($_GET["student_id"]);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Check if student exists
            if ($stmt->rowCount() == 1) {
                // Fetch result row
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // Retrieve values
                $student_name = $row["student_name"];
                $username = $row["username"];
                $email = $row["email"];
                $phone_number = $row["phone_number"];
                $address = $row["address"];
                $course_id = $row["course_id"];

                // Get course name
                $course_name = "";
                $sql_course = "SELECT course_name FROM Courses WHERE course_id = :course_id";
                $stmt_course = $conn->prepare($sql_course);
                $stmt_course->bindParam(":course_id", $course_id, PDO::PARAM_INT);
                if ($stmt_course->execute() && $stmt_course->rowCount() == 1) {
                    $row_course = $stmt_course->fetch(PDO::FETCH_ASSOC);
                    $course_name = $row_course["course_name"];
                }
            } else {
                // Student with the given student_id does not exist, redirect to error page
                header("location: error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        unset($stmt);
    }

    // Close connection
    unset($conn);
} else {
    // Redirect to error page if student_id GET parameter is not set
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Student Details</title>
    <style>
        /* Add your CSS styles for the table here */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        table th {
            background-color: #f2f2f2;
        }

        
    </style>
</head>

<body>
    <div id="sidebar">
        <?php include 'slidebar.php'; ?> <!-- Include the sidebar -->
    </div>
    <div id="content" class="wrapper">
        <h2>Student Details</h2>
        <div>
            <table>
                
                <tr>
                    <th>Student Name</th>
                    <td><?php echo $student_name; ?></td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td><?php echo $username; ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo $email; ?></td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td><?php echo $phone_number; ?></td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?php echo $address; ?></td>
                </tr>
                <tr>
                    <th>Course</th>
                    <td><?php echo $course_name; ?></td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
