<?php
// Include the database connection file
require_once 'conn.php';

// Initialize session
session_start();

// Check if student is not logged in
if (!isset($_SESSION["student_id"])) {
    // Redirect to student login page
    header("location: student_login.php");
    exit;
}

// Fetch student's name from the database
$student_id = $_SESSION["student_id"];
$sql_student_name = "SELECT student_name FROM Students WHERE student_id = :student_id";
$stmt_student_name = $conn->prepare($sql_student_name);
$stmt_student_name->bindParam(':student_id', $student_id);
$stmt_student_name->execute();
$student_name = $stmt_student_name->fetchColumn();

// Define variables and initialize with empty values
$feedback = "";
$name = $student_name; // Initialize with student's name
$anonymous = 0;
$feedback_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate feedback
    if (empty(trim($_POST["feedback"]))) {
        $feedback_err = "Please enter your feedback.";
    } else {
        $feedback = trim($_POST["feedback"]);
    }

    // Check if anonymous checkbox is checked
    if (isset($_POST["anonymous"])) {
        $anonymous = 1;
        // If anonymous, set name to empty
        $name = "";
    }

    // Check input errors before inserting into database
    if (empty($feedback_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO feedbacks (student_id, feedback, name, anonymous) VALUES (:student_id, :feedback, :name, :anonymous)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
            $stmt->bindParam(":feedback", $feedback, PDO::PARAM_STR);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":anonymous", $anonymous, PDO::PARAM_INT);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Show notification and redirect after successful submission
                echo '<script>alert("Feedback submitted successfully!"); window.location.href = "student_enrolled_courses.php";</script>';
                exit;
            } else {
                echo "Something went wrong. Please try again later.";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Feedback</title>
    <!-- Add your CSS styles here -->
</head>
<body>
<?php include 'floating_profile_button.php'; ?>
<div id="sidebar">
        <?php include 'student_slidebar.php'; ?> <!-- Include the sidebar -->
    </div>

    <div id="content" class="wrapper">
    <h1>Post Feedback</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div>
            <label>Feedback:</label>
            <textarea name="feedback" rows="5" cols="50"><?php echo $feedback; ?></textarea>
            <span><?php echo $feedback_err; ?></span>
        </div>
        <div>
            <label>Name (optional):</label>
            <input type="text" name="name" value="<?php echo $name; ?>" readonly>
        </div>
        <div>
            <input type="checkbox" id="anonymous" name="anonymous" value="anonymous">
            <label for="anonymous">Submit anonymously</label>
        </div>
        <div>
            <input type="submit" value="Submit">
        </div>
    </form>
    </div>
    <?php include 'clock.php'; ?>
</body>
</html>
