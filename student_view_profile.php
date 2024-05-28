<?php
// Include the database connection file
require_once 'conn.php';

// Initialize session
session_start();

// Check if student is logged in, redirect to login page if not logged in
if (!isset($_SESSION["student_id"])) {
    header("location: student_login.php");
    exit;
}

// Fetch student information from the database
$student_id = $_SESSION["student_id"];
$sql_student = "SELECT * FROM Students WHERE student_id = :student_id";
$stmt_student = $conn->prepare($sql_student);
$stmt_student->bindParam(':student_id', $student_id);
$stmt_student->execute();
$student = $stmt_student->fetch(PDO::FETCH_ASSOC);

// Close connection
unset($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <style>
       
        h1 {
            text-align: center;
            color: #333;
        }

        .profile-card {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-details {
            text-align: center;
        }

        .profile-details h2 {
            margin-bottom: 10px;
            color: #333;
        }

        .profile-details p {
            color: #666;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
<div id="sidebar">
    <?php include 'student_slidebar.php'; ?>
    <?php include 'clock.php'; ?>
</div>

<div id="content" class="wrapper">

    <h1>Student Profile</h1>
    <div class="profile-card">
        <div class="profile-image">
            <img src="student_profile_icon.png" alt="Profile Picture">
        </div>
        <div class="profile-details">
            <h2><?php echo $student['student_name']; ?></h2>
            <p>Student ID: <?php echo $student['student_id']; ?></p>
            <p>Email: <?php echo $student['email']; ?></p>
            <p>Phone: <?php echo $student['phone_number']; ?></p>
            <p>Date of Birth: </p><?php echo $student['dob']; ?></p>
            <p>Address:  <?php echo $student['address']; ?></p>
            <p>Course:  <?php echo $student['course_id']; ?></p>
            
        </div>
    </div>
</div>
</body>
</html>
