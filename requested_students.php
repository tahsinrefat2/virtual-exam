<?php
// Include database connection
require_once 'conn.php';

// Include process_request.php
require_once 'process_request.php';

// Fetch requested students
$sql = "SELECT * FROM requested_students";
$stmt = $conn->query($sql);
$requested_students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requested Students</title>
    <style>
        
        #content {
            margin-left: 250px;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        button {
            padding: 6px 12px;
            cursor: pointer;
        }

        button[name="accept"] {
            background-color: #4CAF50;
            color: white;
            border: none;
        }

        button[name="reject"] {
            background-color: #f44336;
            color: white;
            border: none;
        }
    </style>
</head>
<body>
    <div id="sidebar">
        <?php include 'slidebar.php'; ?> <!-- Include the sidebar -->
    </div>
    <div id="content" class="wrapper">
        <h1>Requested Students</h1>
        <table border="1">
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Course ID</th>
                <th>Action</th>
            </tr>
            <?php foreach ($requested_students as $student) : ?>
                <tr>
                    <td><?php echo $student['student_id']; ?></td>
                    <td><?php echo $student['student_name']; ?></td>
                    <td><?php echo $student['username']; ?></td>
                    <td><?php echo $student['email']; ?></td>
                    <td><?php echo $student['course_id']; ?></td>
                    <td>
                        <form action="process_request.php" method="post">
                            <input type="hidden" name="student_id" value="<?php echo $student['student_id']; ?>">
                            <button type="submit" name="accept">Accept</button>
                            <button type="submit" name="reject">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
