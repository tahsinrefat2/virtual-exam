<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Courses</title>
    
    <style>
        /* Course card styling */
        .course-card-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .course-card-container:hover {
            transform: translateY(-5px);
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.2);
        }

        .course-card-container h3 {
            color: #333;
            margin-top: 0;
        }

        .btn-view-announcement {
            display: block;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .btn-view-announcement:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <?php
    // Include the database connection file
    require_once 'conn.php';

    // Fetch all courses from the database
    $sql = "SELECT * FROM Courses";
    $result = $conn->query($sql);
    ?>

    <div id="sidebar">
        <?php include 'slidebar.php'; ?> <!-- Include the sidebar -->
    </div>
    <div id="content" class="wrapper">
        <h2>View Courses</h2>
        <?php
        // Check if courses exist
        if ($result->rowCount() > 0) {
            // Display courses as cards
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="course-card-container">' . "\n";
                echo '<h3>' . $row['course_name'] . '</h3>';
                echo '<a href="view_announcements.php?course_id=' . $row['course_id'] . '" class="btn-view-announcement">View Announcements</a>';
                echo '<a href="view_students_by_course.php?course_id=' . $row['course_id'] . '" class="btn-view-announcement">View Students</a>';
                echo '</div>';
            }
        } else {
            echo "<p>No courses found</p>";
        }
        ?>
    </div>
</body>

</html>
