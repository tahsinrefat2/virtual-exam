<?php
// Include the database connection file
require_once 'conn.php';

// Initialize session
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get exam_id from the form
    $exam_id = $_POST['exam_id'];

    // Prepare an array to store question data
    $questions = array();

    // Get question data from form
    $question_texts = $_POST['question_text'];
    $marks = $_POST['marks']; // New input field for marks
    $options = array();
    $correct_options = $_POST['correct_option']; // New input field for correct options

    // Loop through each question
    foreach ($question_texts as $key => $question_text) {
        // Add question data to the array
        $questions[] = array(
            'question_text' => $question_text,
            'marks' => $marks[$key], // Store marks for each question
            'options' => array(
                'option_1' => $_POST['option_1'][$key],
                'option_2' => $_POST['option_2'][$key],
                'option_3' => $_POST['option_3'][$key],
                'option_4' => $_POST['option_4'][$key]
            ),
            'correct_option' => $correct_options[$key] // Store correct option for each question
        );
    }

    // Insert questions and options into the database
    foreach ($questions as $question) {
        // Prepare SQL statement for inserting question
        $sql_question = "INSERT INTO questions (exam_id, question_text, marks, option_1, option_2, option_3, option_4, correct_option) 
                         VALUES (:exam_id, :question_text, :marks, :option_1, :option_2, :option_3, :option_4, :correct_option)";
        $stmt_question = $conn->prepare($sql_question);
        $stmt_question->bindParam(':exam_id', $exam_id);
        $stmt_question->bindParam(':question_text', $question['question_text']);
        $stmt_question->bindParam(':marks', $question['marks']);
        $stmt_question->bindParam(':option_1', $question['options']['option_1']);
        $stmt_question->bindParam(':option_2', $question['options']['option_2']);
        $stmt_question->bindParam(':option_3', $question['options']['option_3']);
        $stmt_question->bindParam(':option_4', $question['options']['option_4']);
        $stmt_question->bindParam(':correct_option', $question['correct_option']);
        $stmt_question->execute();
    }

    // Set success message
    $_SESSION['success_message'] = "Questions added successfully!";
    header("location: view_questions.php?exam_id=" . $exam_id);
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div id="sidebar">
        <?php include 'slidebar.php'; ?> <!-- Include the sidebar -->
    </div>
    <div id="content" class="wrapper">
    <h1>Add Question</h1>
    <!-- Display success or error message if set -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div style="color: green;"><?php echo $_SESSION['success_message']; ?></div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div style="color: red;"><?php echo $_SESSION['error_message']; ?></div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    
    <form id="addQuestionForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="exam_id" value="<?php echo $_GET['exam_id']; ?>">
        <div id="questionContainer">
            <div class="question">
                <label for="question_text">Question Text:</label><br>
                <textarea class="question_text" name="question_text[]" rows="4" cols="50" required></textarea><br><br>
                
                <label for="option_1">Option 1:</label>
                <input type="text" class="option" name="option_1[0]" required><br>
                
                <label for="option_2">Option 2:</label>
                <input type="text" class="option" name="option_2[0]" required><br>
                
                <label for="option_3">Option 3:</label>
                <input type="text" class="option" name="option_3[0]" required><br>
                
                <label for="option_4">Option 4:</label>
                <input type="text" class="option" name="option_4[0]" required><br>
                
                <label for="correct_option">Correct Option:</label>
                <select class="correct_option" name="correct_option[]">
                    <option value="1">Option 1</option>
                    <option value="2">Option 2</option>
                    <option value="3">Option 3</option>
                    <option value="4">Option 4</option>
                </select><br><br>

                <label for="marks">Marks:</label>
                <input type="number" name="marks[]" value="1" required min="1"><br><br> <!-- Added marks input field -->
            </div>
        </div>

        <button type="button" id="addQuestion" value="Submit">Add Another Question</button><br><br>

        <input type="submit" value="Submit">
    </form>
    </div>
    <script>
        $(document).ready(function() {
            $("#addQuestion").click(function() {
                var questionCount = $(".question").length + 1;
                var questionHtml = '<div class="question">';
                questionHtml += '<label for="question_text">Question Text:</label><br>';
                questionHtml += '<textarea class="question_text" name="question_text[]" rows="4" cols="50" required></textarea><br><br>';
                questionHtml += '<label for="option_1">Option 1:</label>';
                questionHtml += '<input type="text" class="option" name="option_1[' + (questionCount - 1) + ']" required><br>';
                questionHtml += '<label for="option_2">Option 2:</label>';
                questionHtml += '<input type="text" class="option" name="option_2[' + (questionCount - 1) + ']" required><br>';
                questionHtml += '<label for="option_3">Option 3:</label>';
                questionHtml += '<input type="text" class="option" name="option_3[' + (questionCount - 1) + ']" required><br>';
                questionHtml += '<label for="option_4">Option 4:</label>';
                questionHtml += '<input type="text" class="option" name="option_4[' + (questionCount - 1) + ']" required><br>';
                questionHtml += '<label for="correct_option">Correct Option:</label>';
                questionHtml += '<select class="correct_option" name="correct_option[]">';
                questionHtml += '<option value="1">Option 1</option>';
                questionHtml += '<option value="2">Option 2</option>';
                questionHtml += '<option value="3">Option 3</option>';
                questionHtml += '<option value="4">Option 4</option>';
                questionHtml += '</select><br><br>';

                questionHtml += '<label for="marks">Marks:</label>';
                questionHtml += '<input type="number" name="marks[]" value="1" required min="1"><br><br>'; // Added marks input field

                questionHtml += '</div>';

                $("#questionContainer").append(questionHtml);
            });
        });
    </script>
</body>
</html>
