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

// Define variables and initialize with empty values
$exam_name = $course_id = $exam_duration = "";
$exam_name_err = $course_id_err = $exam_duration_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate exam name
    if (empty(trim($_POST["exam_name"]))) {
        $exam_name_err = "Please enter exam name.";
    } else {
        $exam_name = trim($_POST["exam_name"]);
    }

    // Validate course ID
    if (empty(trim($_POST["course_id"]))) {
        $course_id_err = "Please select a course.";
    } else {
        $course_id = trim($_POST["course_id"]);
    }

    // Validate exam duration
    if (empty(trim($_POST["exam_duration"]))) {
        $exam_duration_err = "Please enter exam duration.";
    } elseif (!ctype_digit($_POST["exam_duration"]) || $_POST["exam_duration"] < 1) {
        $exam_duration_err = "Exam duration must be a positive integer.";
    } else {
        $exam_duration = trim($_POST["exam_duration"]);
    }

    // Check input errors before inserting into database
    if (empty($exam_name_err) && empty($course_id_err) && empty($exam_duration_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO Exams (exam_name, course_id, exam_duration) VALUES (:exam_name, :course_id, :exam_duration)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":exam_name", $param_exam_name, PDO::PARAM_STR);
            $stmt->bindParam(":course_id", $param_course_id, PDO::PARAM_INT);
            $stmt->bindParam(":exam_duration", $param_exam_duration, PDO::PARAM_INT);

            // Set parameters
            $param_exam_name = $exam_name;
            $param_course_id = $course_id;
            $param_exam_duration = $exam_duration;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to view exams page
                header("location: view_exams.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Close connection
    unset($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Exam</title>
</head>

<body>
<div id="sidebar">
        <?php include 'slidebar.php'; ?> <!-- Include the sidebar -->
    </div>
    <div id="content" class="wrapper">
        <h2>Add Exam</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Exam Name</label>
                <input type="text" name="exam_name" value="<?php echo $exam_name; ?>">
                <span><?php echo $exam_name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Select Course</label>
                <select name="course_id">
                    <option value="">Select a course</option>
                    <?php
                    // Fetch all courses from database
                    $sql = "SELECT course_id, course_name FROM Courses";
                    $result = $conn->query($sql);
                    if ($result->rowCount() > 0) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $row['course_id'] . "'>" . $row['course_name'] . "</option>";
                        }
                    }
                    ?>
                </select>
                <span><?php echo $course_id_err; ?></span>
            </div>
            <div class="form-group">
                <label>Exam Duration (in minutes)</label>
                <input type="number" name="exam_duration" value="<?php echo $exam_duration; ?>">
                <span><?php echo $exam_duration_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" value="Add Exam">
            </div>
        </form>
    </div>
</body
