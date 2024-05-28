<?php
// Include the database connection file
require_once 'conn.php';

// Initialize session
session_start();

// Check if exam_id parameter is set in the URL
if (!isset($_GET['exam_id']) || empty($_GET['exam_id'])) {
    // Redirect to error page if exam_id is not provided
    header("location: error.php");
    exit;
}

// Get exam_id from the URL
$exam_id = $_GET['exam_id'];

// Fetch exam details
$sql_exam = "SELECT * FROM Exams WHERE exam_id = :exam_id";
$stmt_exam = $conn->prepare($sql_exam);
$stmt_exam->bindParam(':exam_id', $exam_id);
$stmt_exam->execute();
$exam = $stmt_exam->fetch();

// Fetch questions for the given exam_id
$sql_questions = "SELECT * FROM questions WHERE exam_id = :exam_id";
$stmt_questions = $conn->prepare($sql_questions);
$stmt_questions->bindParam(':exam_id', $exam_id);
$stmt_questions->execute();
$questions = $stmt_questions->fetchAll(PDO::FETCH_ASSOC);

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    // Redirect to student login page if not logged in
    header("location: student_login.php");
    exit;
}

// Get student ID
$student_id = $_SESSION['student_id'];

// Check if the student has already attempted the exam
$sql_attempted = "SELECT * FROM Results WHERE student_id = :student_id AND exam_id = :exam_id";
$stmt_attempted = $conn->prepare($sql_attempted);
$stmt_attempted->bindParam(':student_id', $student_id);
$stmt_attempted->bindParam(':exam_id', $exam_id);
$stmt_attempted->execute();
$attempted_exam = $stmt_attempted->fetch();

// If the student has already attempted the exam, show a notification
if ($attempted_exam) {
    $_SESSION['error_message'] = "You have already attempted this exam.";
    header("location: student_exams.php");
    exit;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize score
    $score = 0;

    // Loop through submitted answers and calculate the score
    foreach ($_POST['answers'] as $question_id => $selected_option_id) {
        // Find the question and its correct option
        foreach ($questions as $question) {
            if ($question['question_id'] == $question_id) {
                // Check if the selected option is correct
                if ($question['correct_option'] == $selected_option_id) {
                    // Increment score by the marks of the question
                    $score += $question['marks'];
                }
                break; // Break the inner loop once the question is found
            }
        }
    }

    // Prepare SQL statement to insert selected answers into student_answers table
    $sql_insert_answer = "INSERT INTO student_answers (student_id, exam_id, question_id, selected_option_id) VALUES (:student_id, :exam_id, :question_id, :selected_option_id)";
    $stmt_insert_answer = $conn->prepare($sql_insert_answer);
    $stmt_insert_answer->bindParam(':student_id', $student_id);
    $stmt_insert_answer->bindParam(':exam_id', $exam_id);

    // Loop through submitted answers and insert them into the database
    foreach ($_POST['answers'] as $question_id => $selected_option_id) {
        $stmt_insert_answer->bindParam(':question_id', $question_id);
        $stmt_insert_answer->bindParam(':selected_option_id', $selected_option_id);
        $stmt_insert_answer->execute();
    }

    // Insert result into Results table
    $sql_insert_result = "INSERT INTO Results (student_id, exam_id, score) VALUES (:student_id, :exam_id, :score)";
    $stmt_insert_result = $conn->prepare($sql_insert_result);
    $stmt_insert_result->bindParam(':student_id', $student_id);
    $stmt_insert_result->bindParam(':exam_id', $exam_id);
    $stmt_insert_result->bindParam(':score', $score);
    $stmt_insert_result->execute();

    // Redirect to student dashboard after submitting exam
    $_SESSION['success_message'] = "Exam submitted successfully! Your score is: $score";
    header("location: student_exams.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attend Exam</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        
        .question-container {
            display: none;
        }

        .question-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        
        /* Reset some default styles */
        body,
        h1,
        p,
        form,
        label,
        input,
        button {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        .heading {
            text-align: center;
            margin-bottom: 20px;
            color: #333333;
        }

        .error-message {
            background-color: #ffcdd2;
            color: #c62828;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .timer {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .question-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .question-text {
            font-weight: bold;
            color: #333333;
        }

        .question-count {
            color: #666666;
        }

        .option {
            display: block;
            margin-bottom: 10px;
        }

        .option input[type="radio"] {
            margin-right: 10px;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .prev-btn,
        .next-btn {
            background-color: #e0e0e0;
            color: #333333;
        }

        .prev-btn:hover,
        .next-btn:hover {
            background-color: #bdbdbd;
        }

        .submit-btn {
            background-color: #2196f3;
            color: #ffffff;
        }

        .submit-btn:hover {
            background-color: #1976d2;
        }
    
    </style>
</head>

<body>
    <div class="container">
        <h1 class="heading">Attend Exam</h1>
        <?php if (isset($_SESSION['error_message'])) : ?>
            <div class="error-message"><?php echo $_SESSION['error_message']; ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <p id="timer" class="timer"></p>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>?exam_id=<?php echo $exam_id; ?>" method="post" class="exam-form" id="exam-form">
            <?php $question_number = 1; ?>
            <?php foreach ($questions as $question) : ?>
                <div class="question-container" id="question-<?php echo $question['question_id']; ?>">
                    <div class="question-info">
                        <p class="question-text"><?php echo $question_number . '. ' . $question['question_text']; ?></p>
                        <p class="question-count" id="question-count"></p>
                    </div>
                    <label class="option">
                        <input type="radio" name="answers[<?php echo $question['question_id']; ?>]" value="1">
                        <?php echo $question['option_1']; ?>
                    </label>
                    <label class="option">
                        <input type="radio" name="answers[<?php echo $question['question_id']; ?>]" value="2">
                        <?php echo $question['option_2']; ?>
                    </label>
                    <label class="option">
                        <input type="radio" name="answers[<?php echo $question['question_id']; ?>]" value="3">
                        <?php echo $question['option_3']; ?>
                    </label>
                    <label class="option">
                        <input type="radio" name="answers[<?php echo $question['question_id']; ?>]" value="4">
                        <?php echo $question['option_4']; ?>
                    </label>
                </div>
                <?php $question_number++; ?>
            <?php endforeach; ?>
            <button type="button" id="prev-btn" onclick="showPrevQuestion()" class="prev-btn">Previous</button>
            <button type="button" id="next-btn" onclick="showNextQuestion()" class="next-btn">Next</button>
            <button type="submit" id="submit-btn" class="submit-btn">Submit Exam</button>
        </form>

    </div>
    <?php include 'clock.php'; ?>
    <script>
        const questions = document.querySelectorAll('.question-container');
        let currentQuestionIndex = 0;
        const totalQuestions = questions.length;

        // Show the first question by default
        questions[currentQuestionIndex].style.display = 'block';
        updateQuestionCount();

        function showPrevQuestion() {
            if (currentQuestionIndex > 0) {
                questions[currentQuestionIndex].style.display = 'none';
                currentQuestionIndex--;
                questions[currentQuestionIndex].style.display = 'block';
                updateQuestionCount();
            }
        }

        function showNextQuestion() {
            if (currentQuestionIndex < questions.length - 1) {
                questions[currentQuestionIndex].style.display = 'none';
                currentQuestionIndex++;
                questions[currentQuestionIndex].style.display = 'block';
                updateQuestionCount();
            }
        }

        function updateQuestionCount() {
            const questionCountElement = document.getElementById('question-count');
            questionCountElement.textContent = `${currentQuestionIndex + 1} of ${totalQuestions}`;
        }

        // Timer functionality
        const examDuration = <?php echo $exam['exam_duration'] * 60; ?>; // Exam duration in seconds
        let remainingTime = examDuration;
        const timerElement = document.getElementById('timer');

        function updateTimer() {
            const minutes = Math.floor(remainingTime / 60);
            const seconds = remainingTime % 60;
            timerElement.textContent = `Remaining Time: ${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            if (remainingTime > 0) {
                remainingTime--;
            } else {
                // Exam time is over, submit the form automatically
                document.getElementById('exam-form').submit();
            }
        }

        // Start the timer
        const timerInterval = setInterval(updateTimer, 1000);
    </script>
</body>

</html>

