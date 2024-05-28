<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>

    <div class="sidebar" id="sidebar">
    
        <div class="sidebar-header">
            <!-- Include the clock -->
            
            <button id="toggleBtn" class="toggle-btn" onclick="toggleSidebar()"><a href="about.php"> <img src="Virtual.jpg"width="150" height="100"</a></button>
        </div>
        <ul class="sidebar-menu">
            <li><a href="student_enrolled_courses.php">Enrolled Courses</a></li>
            <li><a href="student_exams.php">View Exams</a></li>
            <li><a href="student_results.php">View Results</a></li>
            <li><a href="student_feedback.php">Write Feedback</a></li>
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
<?php
