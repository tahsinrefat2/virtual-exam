<?php
// Include the database connection file
require_once 'conn.php';

// Check if student_name is provided in the GET request
if(isset($_GET["student_name"]) && !empty(trim($_GET["student_name"]))) {
    // Prepare a select statement to fetch student details
    $sql_student_details = "SELECT s.student_name, s.username, c.course_name, s.email, s.phone_number, s.dob, s.address 
                            FROM Students s
                            JOIN Courses c ON s.course_id = c.course_id
                            WHERE s.student_name = :student_name";
    if($stmt_student_details = $conn->prepare($sql_student_details)) {
        // Bind student_name parameter
        $stmt_student_details->bindParam(":student_name", $param_student_name, PDO::PARAM_STR);
        // Set parameters
        $param_student_name = trim($_GET["student_name"]);
        // Attempt to execute the prepared statement
        if($stmt_student_details->execute()) {
            // Check if student exists
            if($stmt_student_details->rowCount() == 1) {
                // Fetch student details
                $row = $stmt_student_details->fetch(PDO::FETCH_ASSOC);
                // Display student details
                echo "<p><strong>Name:</strong> " . $row["student_name"] . "</p>";
                echo "<p><strong>Username:</strong> " . $row["username"] . "</p>";
                echo "<p><strong>Course:</strong> " . $row["course_name"] . "</p>";
                echo "<p><strong>Email:</strong> " . $row["email"] . "</p>";
                echo "<p><strong>Phone Number:</strong> " . $row["phone_number"] . "</p>";
                echo "<p><strong>Date of Birth:</strong> " . $row["dob"] . "</p>";
                echo "<p><strong>Address:</strong> " . $row["address"] . "</p>";
            } else {
                echo "Student not found.";
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        // Close statement
        unset($stmt_student_details);
    }
} else {
    echo "Invalid request.";
}
?>
