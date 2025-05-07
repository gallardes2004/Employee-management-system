<?php
session_start();
include_once("connection.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .library-content {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            margin-top: 20px;
        }

        .library-content p {
            font-size: 16px;
            color: #555;
        }

        .library-content a {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .library-content a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome to Your Library</h2>
        <div class="library-content">
            <p>This is a placeholder for your library content. You can fill it with books, articles, or other resources.</p>
            <a href="dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>


