<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="styles.css"> <!-- Include your custom CSS file -->
</head>
<body>
    
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            
            <button id="toggleBtn" class="toggle-btn" onclick="toggleSidebar()"><a href="about.php"> <img src="Virtual.jpg"width="150" height="100"</a></button>
        </div>
        <ul class="sidebar-menu">
            
            <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
            <li><a href="add_course.php">Add Course</a></li>
            <li><a href="view_courses.php">View Courses</a></li>
            <li><a href="add_student.php">Add Student</a></li>
            <li><a href="view_students.php">View Students</a></li>
            <li><a href="add_exam.php">Add Exam</a></li>
            <li><a href="view_exams.php">View Exams</a></li>
            <li><a href="view_results_by_course.php">View Results by Course</a></li>
            <li><a href="view_feedbacks.php">View Feedback</a></li>
            <li><a href="post_announcements.php">Post Announcement</a></li>
            <li><a href="requested_students.php">Student Requests</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }
    </script>
</body>
</html>
