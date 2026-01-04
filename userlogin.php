<?php
session_start();
include 'db_connect.php';

$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Prepare and execute query securely
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Validate user
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {
            // Set session variables
            $_SESSION["user"] = [
                "id" => $user["id"],
                "name" => $user["name"],
                "email" => $user["email"]
            ];

            // ✅ Optional: separate email session if needed
            $_SESSION["user_email"] = $user["email"];

            // Redirect to home page
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Login - AlphaFuel</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #0a0a0a;
      color: #00ffff;
      font-family: 'Orbitron', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .login-container {
      background-color: #111;
      padding: 40px;
      border: 2px solid #00ffff;
      border-radius: 16px;
      box-shadow: 0 0 20px rgba(0, 255, 255, 0.2);
      width: 350px;
      text-align: center;
    }

    .login-container h2 {
      margin-bottom: 25px;
      font-size: 24px;
      color: #00ffff;
      text-transform: uppercase;
      letter-spacing: 1.5px;
    }

    .login-container input[type="email"],
    .login-container input[type="password"] {
      width: 100%;
      padding: 12px 15px;
      margin: 10px 0;
      border: 1px solid #00ffff;
      border-radius: 6px;
      background-color: #222;
      color: #00ffff;
      font-size: 14px;
    }

    .login-container button {
      margin-top: 15px;
      padding: 12px 20px;
      width: 100%;
      background-color: transparent;
      color: #00ffff;
      border: 1.5px solid #00ffff;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .login-container button:hover {
      background-color: #00ffff;
      color: #111;
    }

    .login-container a {
      color: #00ffff;
      text-decoration: none;
    }

    .login-container a:hover {
      text-decoration: underline;
    }

    .error {
      color: #ff3c3c;
      background-color: #330000;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ff3c3c;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>User Login</h2>
    <?php if ($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" action="">
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register</a></p>
  </div>
</body>
</html>
