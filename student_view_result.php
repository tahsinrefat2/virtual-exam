<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        #content {
            padding: 20px;
            margin-left: 250px;
        }

        h2 {
            color: #333;
        }

        .result-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .result-container p {
            margin: 0;
            padding: 5px 0;
        }

        .result-container p:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .question-container {
            margin-bottom: 20px;
        }

        .question {
            font-weight: bold;
        }

        .options {
            margin-top: 5px;
            margin-left: 20px;
        }

        .correct {
            color: green;
            font-weight: bold;
        }

        .wrong {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div id="sidebar">
    <?php include 'student_slidebar.php'; ?>
</div>
<h2>Exam Result</h2>
<div id="content" class="wrapper">
    
    <?php
    // Include the database connection file
    require_once 'conn.php';

    // Start session
    session_start();

    // Check if student is not logged in
    if (!isset($_SESSION["student_id"])) {
        // Redirect to student login page
        header("location: student_login.php");
        exit;
    }

    // Check if exam ID is provided in the URL
    if (!isset($_GET['exam_id'])) {
        echo "Exam ID is missing.";
        exit;
    }

    // Retrieve student ID and exam ID from session and URL
    $student_id = $_SESSION["student_id"];
    $exam_id = $_GET['exam_id'];

    // Retrieve exam details from the database
    $sql_exam = "SELECT * FROM Exams WHERE exam_id = :exam_id";
    $stmt_exam = $conn->prepare($sql_exam);
    $stmt_exam->bindParam(':exam_id', $exam_id);
    $stmt_exam->execute();
    $exam = $stmt_exam->fetch();

    // Retrieve result for the student from the database
    $sql_result = "SELECT * FROM Results WHERE student_id = :student_id AND exam_id = :exam_id";
    $stmt_result = $conn->prepare($sql_result);
    $stmt_result->bindParam(':student_id', $student_id);
    $stmt_result->bindParam(':exam_id', $exam_id);
    $stmt_result->execute();
    $result = $stmt_result->fetch();

    // Check if the student has attempted the exam
    if (!$result) {
        echo "You have not attempted this exam yet.";
        exit;
    }

    // Display result
    echo '<div class="result-container">';
    echo '<p><strong>Exam Name:</strong> ' . $exam['exam_name'] . '</p>';
    echo '<p><strong>Course:</strong> ' . getCourseName($exam['course_id'], $conn) . '</p>';
    echo '<p><strong>Score:</strong> ' . $result['score'] . '</p>';
    echo '<h3>Questions:</h3>';

    // Retrieve questions and options for the given exam
    $sql_questions = "SELECT q.question_id, q.question_text, q.marks, q.option_1, q.option_2, q.option_3, q.option_4, q.correct_option, sa.selected_option_id
                      FROM questions q
                      LEFT JOIN student_answers sa ON q.question_id = sa.question_id AND sa.exam_id = :exam_id AND sa.student_id = :student_id
                      WHERE q.exam_id = :exam_id";
    $stmt_questions = $conn->prepare($sql_questions);
    $stmt_questions->bindParam(':exam_id', $exam_id);
    $stmt_questions->bindParam(':student_id', $student_id);
    $stmt_questions->execute();
    $questions = $stmt_questions->fetchAll(PDO::FETCH_ASSOC);

    foreach ($questions as $question) {
        echo '<div class="question-container">';
        echo '<p class="question"><strong>Question:</strong> ' . $question['question_text'] . '</p>';
        echo '<p><strong>Marks:</strong> ' . $question['marks'] . '</p>';
        echo '<div class="options">';
        for ($i = 1; $i <= 4; $i++) {
            $option_class = '';
            if ($question['selected_option_id'] == $i) {
                if ($i == $question['correct_option']) {
                    $option_class = 'correct'; // Chosen and correct answer
                } else {
                    $option_class = 'wrong'; // Chosen but wrong answer
                }
            } elseif ($i == $question['correct_option']) {
                $option_class = 'correct'; // Correct answer
            }
            echo '<label class="' . $option_class . '">';
            echo '<input type="radio" disabled';
            if ($question['selected_option_id'] == $i) {
                echo ' checked';
            }
            echo '>';
            echo $question['option_' . $i];
            echo '</label>';
            echo '<br>';
        }
        echo '</div>';
        echo '</div>';
    }

    echo '</div>';

    // Close the database connection
    unset($conn);

    // Function to get course name by course ID
    function getCourseName($course_id, $conn) {
        $sql = "SELECT course_name FROM Courses WHERE course_id = :course_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['course_name'];
    }
    ?>
</div>
<?php include 'clock.php'; ?>
</body>
</html>