<?php
session_start();
include_once("connection.php");
include_once("function.php");

if (!isAdmin()) {
    header("Location: dashboard.php");
    exit();
}

// CSRF token setup
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = (int)$_POST['user_id'];
    $new_role = $_POST['role'];
    $csrf_token = $_POST['csrf_token'];

    $valid_roles = ['user', 'admin'];

    if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
        $message = "Invalid CSRF token.";
    } elseif (!in_array($new_role, $valid_roles)) {
        $message = "Invalid role selected.";
    } elseif ($_SESSION['user_id'] == $user_id && $new_role !== 'admin') {
        $message = "You cannot change your own role.";
    } else {
        $stmt = $conn->prepare("UPDATE Users SET role = ? WHERE user_id = ?");
        $stmt->bind_param("si", $new_role, $user_id);
        if ($stmt->execute()) {
            logAction($conn, $_SESSION['user_id'], "Changed role of user ID $user_id to $new_role");
            $message = "Role updated successfully.";
        } else {
            $message = "Error updating role.";
        }
    }
}

// Get all users
$users = $conn->query("SELECT user_id, name, email, role FROM Users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Role Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        h2 {
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 80%;
        }
        th, td {
            padding: 10px 14px;
            border: 1px solid #ccc;
            text-align: left;
        }
        select, button {
            padding: 6px 10px;
        }
        .message {
            margin-bottom: 20px;
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
        }
        .back-link {
            margin-top: 20px;
            display: inline-block;
        }
    </style>
</head>
<body>

    <h2>User Role Management</h2>

    <?php if (!empty($message)): ?>
        <div class="message <?= strpos($message, 'Error') !== false ? 'error' : '' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Current Role</th>
            <th>Change Role</th>
        </tr>
        <?php while ($user = $users->fetch_assoc()): ?>
            <tr>
                <form method="POST">
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
                        <?php $isSelf = $_SESSION['user_id'] == $user['user_id']; ?>
                        <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <select name="role" <?= $isSelf ? 'disabled' : '' ?>>
                            <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                        <button type="submit" <?= $isSelf ? 'disabled' : '' ?>>Update</button>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <a class="back-link" href="dashboard.php">← Back to Dashboard</a>

</body>
</html>
