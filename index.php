<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VirtualExam - Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('background.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .container {
            text-align: center;
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease;
            transform: translateY(-5px);
        }

        h1 {
            margin-bottom: 30px;
            color: #333;
        }

        .buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .admin-btn {
            background-color: #007bff;
            color: #fff;
        }

        .student-btn {
            background-color: #28a745;
            color: #fff;
        }

        .btn:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }

        .floating-text {
            position: absolute;
            font-size: 48px;
            font-weight: 700;
            color: blue;
            animation: floatText 10s infinite linear;
        }

        @keyframes floatText {
            0% {
                transform: translate(0, 0);
            }
            25% {
                transform: translate(-100%, 100%);
            }
            50% {
                transform: translate(100%, 0);
            }
            75% {
                transform: translate(0, -100%);
            }
            100% {
                transform: translate(0, 0);
            }
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="floating-text"> <img src="Virtual.jpg" alt="Profile Picture" height="200px" weight="200px"></div>
    <div class="container">
        <h1>VirtualExam</h1>
        
        <div class="buttons">
            <a href="admin_login.php" class="btn admin-btn"><i class="fas fa-user-cog"></i> Admin Panel</a>
            <a href="student_login.php" class="btn student-btn"><i class="fas fa-user-graduate"></i> Student Portal</a>
            <a href="student_registration.php" class="btn student-btn"><i class="fas fa-user-plus"></i> Request to Join</a>
        </div>
    </div>
</body>
</html>