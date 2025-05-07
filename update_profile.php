<?php
session_start();
include_once("connection.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_dir = "uploads/";
    $file = $_FILES["profile_picture"];
    $filename = basename($file["name"]);
    $target_file = $target_dir . $filename;
    
    // Check if the file is an image
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        echo "<p>File is not an image.</p>";
        exit();
    }

    // Check file size (5MB max)
    if ($file["size"] > 5 * 1024 * 1024) {
        echo "<p>Sorry, your file is too large. Maximum size is 5MB.</p>";
        exit();
    }

    // Restrict allowed file formats
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if (!in_array($file_extension, $allowed_extensions)) {
        echo "<p>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>";
        exit();
    }

    // Sanitize the filename
    $filename = preg_replace("/[^a-zA-Z0-9\._-]/", "_", $filename);

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "<p>Sorry, file already exists.</p>";
        exit();
    }

    // Move the uploaded file
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        // Update the database with the new profile picture
        $stmt = $conn->prepare("UPDATE Users SET profile_picture = ? WHERE user_id = ?");
        $stmt->bind_param("si", $filename, $user_id);
        if ($stmt->execute()) {
            echo "<p>Profile picture updated!</p>";
        } else {
            echo "<p>Failed to update profile picture in the database.</p>";
        }
    } else {
        echo "<p>Sorry, there was an error uploading your file.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Profile Picture</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        label {
            font-size: 16px;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }
        input[type="file"] {
            padding: 10px;
            margin: 15px 0;
            background-color: #f1f1f1;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            font-size: 16px;
            color: #d9534f;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Upload Profile Picture</h2>

        <form method="POST" enctype="multipart/form-data">
            <label>Choose a Profile Picture:</label>
            <input type="file" name="profile_picture" accept="image/*">
            <button type="submit">Upload</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Display success or error message here
            if (isset($success_message)) {
                echo "<p class='message' style='color: green;'>$success_message</p>";
            }
            if (isset($error_message)) {
                echo "<p class='message'>$error_message</p>";
            }
        }
        ?>
    </div>

</body>
</html>
