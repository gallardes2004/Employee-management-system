<?php
session_start();
include_once("connection.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $old_password = trim($_POST['old_password']);
    $new_password = trim($_POST['new_password']);

    // Basic password strength check (you can improve it further)
    if (strlen($new_password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        // Fetch current password hash
        $stmt = $conn->prepare("SELECT password FROM Users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($current_hashed_password);
        $stmt->fetch();
        $stmt->close();

        // Verify old password
        if (!password_verify($old_password, $current_hashed_password)) {
            $error = "Incorrect current password.";
        } else {
            // Update with new hashed password
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE Users SET password = ? WHERE user_id = ?");
            $update->bind_param("si", $new_hashed_password, $user_id);

            if ($update->execute()) {
                $success = "Password changed successfully.";
                session_regenerate_id(true); // Optional: Security enhancement
            } else {
                $error = "Failed to update password.";
            }
            $update->close();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .message { padding: 10px; margin-bottom: 15px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <h2>Change Password</h2>

    <?php if ($success): ?>
        <div class="message success"><?php echo $success; ?></div>
    <?php elseif ($error): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Current Password:</label><br>
        <input type="password" name="old_password" required><br><br>

        <label>New Password:</label><br>
        <input type="password" name="new_password" required><br><br>

        <button type="submit">Update Password</button>
    </form>
    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>

