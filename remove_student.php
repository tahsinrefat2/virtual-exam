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

// Check if student_id parameter is provided in the URL
if (isset($_GET["student_id"]) && !empty(trim($_GET["student_id"]))) {
    // Prepare a delete statement
    $sql = "DELETE FROM Students WHERE student_id = :student_id";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":student_id", $param_student_id, PDO::PARAM_INT);

        // Set parameters
        $param_student_id = trim($_GET["student_id"]);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Student deleted successfully, redirect to view_students.php
            header("location: view_students.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        unset($stmt);
    }

    // Close connection
    unset($conn);
} else {
    // Redirect to error page if student_id parameter is not provided
    header("location: error.php");
    exit();
}
?>
