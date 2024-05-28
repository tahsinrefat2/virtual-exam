<?php
// Include the database connection file
require_once 'conn.php';

// Initialize session
session_start();

// Function to display success or error message
function displayMessage($message, $type = 'success') {
    $_SESSION['message'] = array('content' => $message, 'type' => $type);
}

// Check if exam_id parameter is set in the URL
if (!isset($_GET['exam_id']) || empty($_GET['exam_id'])) {
    // Redirect to error page if exam_id is not provided
    header("location: error.php");
    exit;
}

// Get exam_id from the URL
$exam_id = $_GET['exam_id'];

// Fetch distinct questions for the given exam_id
$sql_questions = "SELECT question_id, question_text, marks, option_1, option_2, option_3, option_4, correct_option FROM questions WHERE exam_id = :exam_id";
$stmt_questions = $conn->prepare($sql_questions);
$stmt_questions->bindParam(':exam_id', $exam_id);
$stmt_questions->execute();
$questions = $stmt_questions->fetchAll(PDO::FETCH_ASSOC);

// Check if form is submitted for updating or deleting a question
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_question'])) {
        // Update question
        $question_id = $_POST['question_id'];
        $question_text = $_POST['question_text'];
        $sql_update_question = "UPDATE questions SET question_text = :question_text WHERE question_id = :question_id";
        $stmt_update_question = $conn->prepare($sql_update_question);
        $stmt_update_question->bindParam(':question_text', $question_text);
        $stmt_update_question->bindParam(':question_id', $question_id);
        if ($stmt_update_question->execute()) {
            displayMessage("Question updated successfully.");
        } else {
            displayMessage("Failed to update question.", 'error');
        }

        // Update options
        foreach ($_POST['options'] as $option_id => $option_text) {
            $sql_update_option = "UPDATE options SET option_text = :option_text WHERE option_id = :option_id";
            $stmt_update_option = $conn->prepare($sql_update_option);
            $stmt_update_option->bindParam(':option_text', $option_text);
            $stmt_update_option->bindParam(':option_id', $option_id);
            $stmt_update_option->execute();
        }

        // Update correct option
        $correct_option_id = $_POST['correct_option_id_' . $question_id];
        $sql_update_correct_option = "UPDATE questions SET correct_option = :correct_option WHERE question_id = :question_id";
        $stmt_update_correct_option = $conn->prepare($sql_update_correct_option);
        $stmt_update_correct_option->bindParam(':correct_option', $correct_option_id);
        $stmt_update_correct_option->bindParam(':question_id', $question_id);
        $stmt_update_correct_option->execute();
    } elseif (isset($_POST['delete_question'])) {
        // Delete question
        $question_id = $_POST['question_id'];
        $sql_delete_question = "DELETE FROM questions WHERE question_id = :question_id";
        $stmt_delete_question = $conn->prepare($sql_delete_question);
        $stmt_delete_question->bindParam(':question_id', $question_id);
        if ($stmt_delete_question->execute()) {
            // Also delete options related to this question
            $sql_delete_options = "DELETE FROM options WHERE question_id = :question_id";
            $stmt_delete_options = $conn->prepare($sql_delete_options);
            $stmt_delete_options->bindParam(':question_id', $question_id);
            $stmt_delete_options->execute();
            displayMessage("Question deleted successfully.");
        } else {
            displayMessage("Failed to delete question.", 'error');
        }
    }

    // Redirect back to the same page to prevent form resubmission
    header("location: view_questions.php?exam_id=$exam_id");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Questions</title>
    <!-- Add your CSS styles here -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div id="sidebar">
    <?php include 'slidebar.php'; ?> <!-- Include the sidebar -->
</div>
<div id="content" class="wrapper">
    <h1>View Questions for Exam ID <?php echo $exam_id; ?></h1>

    <?php if ($questions): ?>
        <!-- Display questions with options -->
        <?php foreach ($questions as $question): ?>
            <div class="question">
                <form action="<?php echo $_SERVER['PHP_SELF'] . "?exam_id=$exam_id"; ?>" method="post">
                    <input type="hidden" name="question_id" value="<?php echo $question['question_id']; ?>">
                    <label for="question_text">Question Text:</label><br>
                    <textarea name="question_text" rows="4" cols="50"><?php echo $question['question_text']; ?></textarea><br>
                    <label>Options:</label><br>
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <input type="text" name="options[<?php echo $question['option_' . $i]; ?>]" value="<?php echo $question['option_' . $i]; ?>"><br>
                    <?php endfor; ?>
                    <!-- Add a field to update the correct option -->
                    <label>Correct Option:</label><br>
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <input type="radio" name="correct_option_id_<?php echo $question['question_id']; ?>" value="<?php echo $i; ?>" <?php echo $question['correct_option'] == $i ? 'checked' : ''; ?>>
                        <label><?php echo $question['option_' . $i]; ?></label>
                    <?php endfor; ?>
                    <button type="submit" name="update_question">Update</button>
                    <button type="submit" name="delete_question">Delete</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No questions found for Exam ID <?php echo $exam_id; ?></p>
    <?php endif; ?>

    <!-- Display success or error message -->
    <?php if (isset($_SESSION['message'])): ?>
        <div style="color: <?php echo $_SESSION['message']['type'] === 'success' ? 'green' : 'red'; ?>">
            <?php echo $_SESSION['message']['content']; ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- Add a back button or link to go back to the list of exams -->
    <a href="view_exams.php">Back to List of Exams</a>
</div>
</body>
</html>
