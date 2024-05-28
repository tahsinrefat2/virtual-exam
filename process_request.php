<?php
// Include database connection
require_once 'conn.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if accept button is clicked
    if (isset($_POST['accept'])) {
        // Retrieve student ID
        $student_id = $_POST['student_id'];

        // Fetch student information from requested_students table
        $sql = "SELECT * FROM requested_students WHERE student_id = :student_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        // Insert student information into Students table
        $sql = "INSERT INTO Students (student_name, course_id, username, password, email, dob, phone_number, gender, address, security_question, security_question_answer)
                VALUES (:student_name, :course_id, :username, :password, :email, :dob, :phone_number, :gender, :address, :security_question, :security_question_answer)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':student_name', $student['student_name']);
        $stmt->bindParam(':course_id', $student['course_id']);
        $stmt->bindParam(':username', $student['username']);
        $stmt->bindParam(':password', $student['password']);
        $stmt->bindParam(':email', $student['email']);
        $stmt->bindParam(':dob', $student['dob']);
        $stmt->bindParam(':phone_number', $student['phone_number']);
        $stmt->bindParam(':gender', $student['gender']);
        $stmt->bindParam(':address', $student['address']);
        $stmt->bindParam(':security_question', $student['security_question']);
        $stmt->bindParam(':security_question_answer', $student['security_question_answer']);

        // If insertion is successful, delete the student from requested_students table
        if ($stmt->execute()) {
            // Delete student from requested_students table
            $sql = "DELETE FROM requested_students WHERE student_id = :student_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':student_id', $student_id);
            $stmt->execute();

            // Redirect back to requested_students.php
            header("Location: requested_students.php");
            exit;
        } else {
            // If insertion fails, show error message
            echo "<script>alert('Failed to accept request');</script>";
            echo "<script>window.location.href = 'requested_students.php';</script>";
            exit;
        }
    }
    // Handle reject button if needed
    elseif (isset($_POST['reject'])) {
        // Retrieve student ID
        $student_id = $_POST['student_id'];

        // Delete the student from requested_students table
        $sql = "DELETE FROM requested_students WHERE student_id = :student_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':student_id', $student_id);

        // If deletion is successful, redirect back to requested_students.php
        if ($stmt->execute()) {
            header("Location: requested_students.php");
            exit;
        } else {
            // If deletion fails, show error message
            echo "<script>alert('Failed to reject request');</script>";
            echo "<script>window.location.href = 'requested_students.php';</script>";
            exit;
        }
    }
}
?>
