<?php
session_start();
// Include the database connection file
include 'db_connect.php'; 

// Hardcoded credentials for demonstration purposes.
// In a real application, you would fetch and verify credentials from a secure database.
$correct_username = 'admin';
$correct_password = 'password123'; // In production, this would be a hashed password

$error_message = '';

// --- Handle Logout Request ---
if (isset($_GET['logout'])) {
    // Unset all of the session variables
    $_SESSION = array();
    // Destroy the session
    session_destroy();
    // Redirect back to the login page
    header('Location: admin_login.php');
    exit();
}

// --- Handle Login Form Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Verify credentials
    if ($username === $correct_username && $password === $correct_password) {
        // Set session variables to grant admin access
        $_SESSION['user'] = [
            'is_admin' => true,
            'username' => $username
        ];
        // Redirect to the admin dashboard page
        header('Location: admindashboard.php');
        exit();
    } else {
        $error_message = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Inter", sans-serif;
            background-color: #0d0e12;
            color: #e0e0e0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
            background-color: #1a1c22;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            padding: 3rem;
            border: 1px solid #3a3d45;
        }
        .login-form h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #00ffaa;
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #a0a0a0;
        }
        .login-form input {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1.5rem;
            background-color: #262930;
            border: 1px solid #3a3d45;
            border-radius: 6px;
            color: #e0e0e0;
            font-size: 1rem;
        }
        .login-form button {
            width: 100%;
            background-color: #00ffff;
            color: #0d0e12;
            padding: 0.75rem;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }
        .login-form button:hover {
            background-color: #00c0c0;
            transform: translateY(-2px);
        }
        .error-message {
            background-color: #ff4d4d;
            color: #0d0e12;
            padding: 0.75rem;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <form method="post" class="login-form">
            <h2>Admin Login</h2>
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
            
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
