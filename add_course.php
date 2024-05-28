<?php
// Include the database connection file
require_once 'conn.php';

// Initialize session
session_start();

if (!isset($_SESSION["admin_id"])) {
    // Redirect to the login page
    header("location: admin_login.php");
    exit;
}

// Check if admin is logged in, redirect to login page if not logged in
if(!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true) {
    header("location: admin_login.php");
    exit;
}

// Define variables and initialize with empty values
$course_name = "";
$course_name_err = "";
$success_message = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check if course name is empty
    if(empty(trim($_POST["course_name"]))) {
        $course_name_err = "Please enter course name.";
    } else {
        $course_name = trim($_POST["course_name"]);
    }

    // Validate input
    if(empty($course_name_err)) {
        // Check if the course already exists
        $sql_check = "SELECT course_id FROM Courses WHERE course_name = :course_name";
        if($stmt_check = $conn->prepare($sql_check)) {
            // Bind variables to the prepared statement as parameters
            $stmt_check->bindParam(":course_name", $param_course_name, PDO::PARAM_STR);

            // Set parameters
            $param_course_name = $course_name;

            // Attempt to execute the prepared statement
            if($stmt_check->execute()) {
                if($stmt_check->rowCount() > 0) {
                    $course_name_err = "Course already exists.";
                } else {
                    // Prepare an insert statement
                    $sql_insert = "INSERT INTO Courses (course_name) VALUES (:course_name)";
        
                    if($stmt_insert = $conn->prepare($sql_insert)) {
                        // Bind variables to the prepared statement as parameters
                        $stmt_insert->bindParam(":course_name", $param_course_name, PDO::PARAM_STR);
                        
                        // Set parameters
                        $param_course_name = $course_name;
                        
                        // Attempt to execute the prepared statement
                        if($stmt_insert->execute()) {
                            // Course added successfully
                            $success_message = "Course added successfully.";
                            $course_name = ""; // Clear the course name field
                        } else {
                            echo "Oops! Something went wrong. Please try again later.";
                        }
            
                        // Close statement
                        unset($stmt_insert);
                    }
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt_check);
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
    <title>Add Course</title>
    
</head>
<body>
    <div id="sidebar">
        <?php include 'slidebar.php'; ?> <!-- Include the sidebar -->
    </div>
    <div id="content" class="wrapper">
        <h2>Add Course</h2>
        <p>Please fill in the course details:</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label>Course Name</label>
                <input type="text" name="course_name" value="<?php echo $course_name; ?>">
                <span><?php echo $course_name_err; ?></span>
            </div>
            <div>
                <input type="submit" value="Add Course">
            </div>
            <p><?php echo $success_message; ?></p>
        </form>
    </div>
</body>
</html>
