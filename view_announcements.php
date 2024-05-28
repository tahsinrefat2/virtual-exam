<?php
// Include the database connection file
require_once 'conn.php';

// Start session
session_start();

// Check if admin is logged in
if (!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true) {
    // Redirect to admin login page if not logged in
    header("location: admin_login.php");
    exit;
}

// Fetch all courses from the database
$sql = "SELECT * FROM courses";
$coursesResult = $conn->query($sql);

// Check if form is submitted to delete announcement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_announcement"])) {
    // Retrieve announcement ID from form
    $announcement_id = $_POST["announcement_id"];

    // Delete announcement from the database
    $sql = "DELETE FROM announcements WHERE announcement_id = :announcement_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':announcement_id', $announcement_id);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // Announcement deleted successfully, set success message
        $_SESSION['success_message'] = "Announcement deleted successfully!";
        header("location: view_announcements.php");
        exit;
    } else {
        // Error deleting announcement, set error message
        $_SESSION['error_message'] = "Failed to delete announcement.";
        header("location: view_announcements.php");
        exit;
    }
}

// Fetch announcements for the selected course from the database
if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
    $sql = "SELECT * FROM announcements WHERE course_id = :course_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->execute();
    $announcements = $stmt->fetchAll();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Announcements</title>
    
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div id="sidebar">
    <?php include 'slidebar.php'; ?> 
</div>

<div id="content" class="wrapper">
    <h2>View Announcements</h2>
    
    <!-- Display success or error message if set -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success-message"><?php echo $_SESSION['success_message']; ?></div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error-message"><?php echo $_SESSION['error_message']; ?></div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <!-- Display course selection dropdown -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
        <label for="course_id">Select Course:</label>
        <select id="course_id" name="course_id">
            <option value="">All Courses</option>
            <?php while ($row = $coursesResult->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?php echo $row['course_id']; ?>" <?php if(isset($_GET['course_id']) && $_GET['course_id'] == $row['course_id']) echo 'selected'; ?>><?php echo $row['course_name']; ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Filter</button>
    </form>

    <!-- Display existing announcements -->
    <h3>Existing Announcements</h3>
    <?php if (isset($announcements) && $announcements): ?>
        <div class="announcement-cards">
            <?php foreach ($announcements as $announcement): ?>
                <div class="announcement-card">
                    <h4><?php echo $announcement['announcement_title']; ?></h4>
                    <p><?php echo $announcement['announcement_content']; ?></p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <input type="hidden" name="announcement_id" value="<?php echo $announcement['announcement_id']; ?>">
                        <button type="submit" name="delete_announcement">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No announcements found.</p>
    <?php endif; ?>
</div>

</body>
</html>
