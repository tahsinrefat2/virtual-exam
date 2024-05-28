<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        h1 {
            color: #333;
            text-align: center;
        }

        p {
            line-height: 1.6;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        .feature {
            animation: slideInLeft 1s ease;
        }

        .summary {
            animation: slideInRight 1s ease;
        }

        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>About VirtualExam</h1>
    
    <div class="feature">
        <h2>Features:</h2>
        <ul>
            <li><strong>Interactive Learning:</strong> Engage with interactive quizzes and activities designed to make learning fun and engaging. Our platform offers gamified learning experiences to keep you motivated.</li>
            <li><strong>Real-time Feedback:</strong> Receive instant feedback on your performance. Our system provides detailed explanations for correct and incorrect answers, helping you learn from your mistakes.</li>
            <li><strong>Adaptive Learning:</strong> Personalized learning paths tailored to your strengths and weaknesses. Our adaptive algorithms adjust course materials and assessments based on your progress, ensuring optimal learning outcomes.</li>
            <li><strong>Collaborative Study Groups:</strong> Connect with peers through collaborative study groups. Share notes, discuss concepts, and collaborate on projects to enhance your understanding of the material.</li>
            <li><strong>Immersive Simulations:</strong> Experience real-world scenarios through immersive simulations. Our platform offers virtual labs and simulations that allow you to apply theoretical knowledge in practical settings.</li>
            <li><strong>Mobile Accessibility:</strong> Access course materials and assessments anytime, anywhere, from any device. Our mobile-friendly platform ensures seamless learning on the go.</li>
        </ul>
    </div>

    <div class="summary">
        <h2>Summary:</h2>
        <p>VirtualExam revolutionizes the way students engage with course materials and assessments. By combining interactive learning experiences, real-time feedback, and adaptive algorithms, we empower learners to achieve their academic goals more effectively. Join us today and embark on a journey of discovery and growth!</p>
    </div>
</div>

</body>
</html>
