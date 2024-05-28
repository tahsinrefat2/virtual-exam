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
$student_name = $username = $password = $email = $phone_number = $address = $security_question = $security_question_answer = $course_id = $gender = $dob = "";
$student_name_err = $username_err = $password_err = $email_err = $phone_number_err = $address_err = $security_question_err = $security_question_answer_err = $course_id_err = $gender_err = $dob_err = "";
$success_message = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate student name
    if (empty(trim($_POST["student_name"]))) {
        $student_name_err = "Please enter student name.";
    } else {
        $student_name = trim($_POST["student_name"]);
    }

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate phone number
    if (empty(trim($_POST["phone_number"]))) {
        $phone_number_err = "Please enter phone number.";
    } else {
        $phone_number = trim($_POST["phone_number"]);
    }

    // Validate address
    if (empty(trim($_POST["address"]))) {
        $address_err = "Please enter address.";
    } else {
        $address = trim($_POST["address"]);
    }

    // Validate security question
    if (empty(trim($_POST["security_question"]))) {
        $security_question_err = "Please enter security question.";
    } else {
        $security_question = trim($_POST["security_question"]);
    }

    // Validate security question answer
    if (empty(trim($_POST["security_question_answer"]))) {
        $security_question_answer_err = "Please enter security question answer.";
    } else {
        $security_question_answer = trim($_POST["security_question_answer"]);
    }

    // Validate course ID
    if (empty(trim($_POST["course_id"]))) {
        $course_id_err = "Please select a course.";
    } else {
        $course_id = trim($_POST["course_id"]);
    }

    // Validate gender
    if (empty(trim($_POST["gender"]))) {
        $gender_err = "Please select gender.";
    } else {
        $gender = trim($_POST["gender"]);
    }

    // Validate date of birth
    if (empty(trim($_POST["dob"]))) {
        $dob_err = "Please enter date of birth.";
    } else {
        $dob = trim($_POST["dob"]);
    }

    // Check input errors before inserting into database
    if (empty($student_name_err) && empty($username_err) && empty($password_err) && empty($email_err) && empty($phone_number_err) && empty($address_err) && empty($security_question_err) && empty($security_question_answer_err) && empty($course_id_err) && empty($gender_err) && empty($dob_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO Students (student_name, username, password, email, phone_number, address, security_question, security_question_answer, course_id, gender, dob) VALUES (:student_name, :username, :password, :email, :phone_number, :address, :security_question, :security_question_answer, :course_id, :gender, :dob)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":student_name", $student_name, PDO::PARAM_STR);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":password", $password, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":phone_number", $phone_number, PDO::PARAM_STR);
            $stmt->bindParam(":address", $address, PDO::PARAM_STR);
            $stmt->bindParam(":security_question", $security_question, PDO::PARAM_STR);
            $stmt->bindParam(":security_question_answer", $security_question_answer, PDO::PARAM_STR);
            $stmt->bindParam(":course_id", $course_id, PDO::PARAM_INT);
            $stmt->bindParam(":gender", $gender, PDO::PARAM_STR);
            $stmt->bindParam(":dob", $dob, PDO::PARAM_STR);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Student added successfully
                $success_message = "Student added successfully.";
                // Clear form fields
                $student_name = $username = $password = $email = $phone_number = $address = $security_question = $security_question_answer = $course_id = $gender = $dob = "";

                // Redirect to view_students.php
                header("location: view_students.php");
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
    <title>Add Student</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        
    </style>
</head>

<body>
    <div id="sidebar">
        <?php include 'slidebar.php'; ?> <!-- Include the sidebar -->
    </div>
    <div id="content" class="wrapper">
        <h2>Add Student</h2>
        <p>Please fill in the student details:</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label>Student Name</label>
                <input type="text" name="student_name" value="<?php echo $student_name; ?>">
                <span><?php echo $student_name_err; ?></span>
            </div>
            <div>
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $username; ?>">
                <span><?php echo $username_err; ?></span>
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password" value="<?php echo $password; ?>">
                <span><?php echo $password_err; ?></span>
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $email; ?>">
                <span><?php echo $email_err; ?></span>
            </div>
            <div>
                <label>Phone Number</label>
                <input type="text" name="phone_number" value="<?php echo $phone_number; ?>">
                <span><?php echo $phone_number_err; ?></span>
            </div>
            <div>
                <label>Gender</label>
                <select name="gender">
                    <option value="">Select gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="others">Others</option>
                </select>
                <span><?php echo $gender_err; ?></span>
            </div>
            <div>
                <label>Date of Birth</label>
                <input type="date" name="dob" value="<?php echo $dob; ?>">
                <span><?php echo $dob_err; ?></span>
            </div>
            <div>
                <label>Address</label>
                <input type="text" name="address" value="<?php echo $address; ?>">
                <span><?php echo $address_err; ?></span>
            </div>
            <div>
                <label>Security Question</label>
                <input type="text" name="security_question" value="<?php echo $security_question; ?>">
                <span><?php echo $security_question_err; ?></span>
            </div>
            <div>
                <label>Security Question Answer</label>
                <input type="text" name="security_question_answer" value="<?php echo $security_question_answer; ?>">
                <span><?php echo $security_question_answer_err; ?></span>
            </div>
            <div>
                <label>Select Course</label>
                <select name="course_id">
                    <option value="">Select a course</option>
                    <?php
                    // Fetch all courses from database
                    $sql_courses = "SELECT course_id, course_name FROM Courses";
                    $result_courses = $conn->query($sql_courses);
                    if ($result_courses->rowCount() > 0) {
                        while ($row_course = $result_courses->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $row_course['course_id'] . "'>" . $row_course['course_name'] . "</option>";
                        }
                    }
                    ?>
                </select>
                <span><?php echo $course_id_err; ?></span>
            </div>
            
            <div>
                <input type="submit" value="Add Student">
            </div>
        </form>

        <?php if (!empty($success_message)) : ?>
            <div>
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
