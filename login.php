<?php
session_start();
include_once("connection.php");
include_once("function.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (!empty($email) && !empty($password)) {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT user_id, name, password, role FROM Users WHERE email = ?");
        $stmt->bind_param("s", $email); // Bind the email parameter
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // Store user data in session
                $_SESSION["role"] = $row['role'];
                $_SESSION["user_id"] = $row['user_id'];
                logAction($conn, $_SESSION['user_id'], "Logged in");
                header("Location: dashboard.php");
                exit();
            } else {
                // Incorrect password
                $error = "Invalid password.";
            }
        } else {
            // No user found with the given email
            $error = "No user found with that email.";
        }

        $stmt->close(); // Close the prepared statement
    } else {
        // Empty fields
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        label {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color:rgb(175, 76, 153);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        a {
            text-align: center;
            display: block;
            color:rgb(175, 76, 145);
            margin-top: 15px;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Login</button>
        </form>
        <a href="register.php">Don't have an account? Register</a>
    </div>
</body>
</html>
