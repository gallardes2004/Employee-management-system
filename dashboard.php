<?php
include_once("connection.php");
include_once("function.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$result = $conn->query("SELECT name FROM Users WHERE user_id = $user_id");
$user = $result->fetch_assoc();
$result = $conn->query("SELECT name, profile_picture FROM Users WHERE user_id = $user_id");
$user = $result->fetch_assoc();
// echo "<img src='uploads/" . $user['profile_picture'] . "' width='100' height='100'><br>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f3e6ff; /* Soft light purple background */
            color: #5e4b8b; /* Dark purple text color */
            margin: 0;
            padding: 0;
            text-align: center; /* Center the whole page content */
        }

        h2 {
            color: #9b63c8; /* Soft lavender for the header */
            margin-top: 40px;
            font-size: 32px;
        }

        nav {
            background-color: #a678d3; /* Lavender navigation bar */
            padding: 15px;
            text-align: center;
            border-radius: 15px;
            margin-top: 20px;
        }

        nav a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            margin: 0 15px;
            padding: 10px 15px;
            border-radius: 25px;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #8c56a3; /* Darker purple on hover */
        }

        .content {
            background-color: #ffffff; /* White content background */
            border-radius: 20px;
            padding: 30px;
            margin: 30px auto;
            width: 80%;
            max-width: 800px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .content h1 {
            font-size: 36px;
            color: #9b63c8;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .content h3 {
            color: #a678d3;
            font-size: 24px;
        }

        .content p {
            color: #6a4c8c;
            font-size: 18px;
            line-height: 1.6;
        }

        /* Profile picture styling */
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%; /* Circular image */
            border: 5px solid #9b63c8; /* Purple border around the image */
            margin-top: 20px;
            object-fit: cover; /* Ensures the image scales well within the circle */
            display: block; /* Ensures the image is centered */
            margin-left: auto;
            margin-right: auto;
        }

        a {
            color: #a678d3; /* Light purple link color */
            font-weight: bold;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #8c56a3; /* Darker purple on hover */
        }

        /* Style for the Logout button */
        .logout-btn {
            background-color: #d18adf;
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 16px;
            display: inline-block;
            margin-top: 20px;
        }

        .logout-btn:hover {
            background-color: #b66aa7; /* Darker purple on hover for button */
        }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo $user['name']; ?>!</h2>
    
    <!-- Profile Picture centered in the page -->
    <img src="uploads/<?php echo $user['profile_picture']; ?>" alt="Profile Picture" class="profile-img">
    
    <nav>
        <a href="library.php">Library</a> |
        <?php if (isAdmin()): ?>
        <a href="manage_users.php">Manage Users</a> |
        <?php endif; ?>
        <a href="changepassword.php">Change Password</a> |
        <a href="update_profile.php">Update Profile</a> |
        <a href="logout.php" class="logout-btn">Logout</a>
    </nav>

    <div class="content">
        <h1>Employee Management System</h1>
        <h3>Welcome Panel</h3>
        <p>This is a standard dashboard for our website project. You can manage your projects, view the library, and change your password. Enjoy your time!</p>
    </div>
</body>
</html>
