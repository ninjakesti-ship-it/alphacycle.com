<?php
include 'db_connect.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST["name"]);
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm  = $_POST["confirm"];
    $phone    = trim($_POST["phone"]);
    $dob      = $_POST["dob"];
    $city     = trim($_POST["city"]);
    $country  = trim($_POST["country"]);

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, dob, city, country) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $name, $email, $hashed_password, $phone, $dob, $city, $country);

            if ($stmt->execute()) {
                $success = "Registration successful. <a href='userlogin.php'>Login here</a>.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - AlphaFuel</title>
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

.register-container {
  background-color: #111;
  padding: 40px;
  border: 2px solid #00ffff;
  border-radius: 16px;
  box-shadow: 0 0 20px rgba(0, 255, 255, 0.2);
  width: 350px;
  text-align: center;
}

.register-container h2 {
  margin-bottom: 25px;
  font-size: 24px;
  color: #00ffff;
  text-transform: uppercase;
  letter-spacing: 1.5px;
}

.register-container input[type="text"],
.register-container input[type="email"],
.register-container input[type="password"] {
  width: 100%;
  padding: 12px 15px;
  margin: 10px 0;
  border: 1px solid #00ffff;
  border-radius: 6px;
  background-color: #222;
  color: #00ffff;
  font-size: 14px;
}

.register-container button {
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

.register-container button:hover {
  background-color: #00ffff;
  color: #111;
}

.register-container a {
  color: #00ffff;
  text-decoration: none;
}

.register-container a:hover {
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

.success {
  color: #00ff99;
  background-color: #003322;
  padding: 10px;
  margin-bottom: 15px;
  border: 1px solid #00ff99;
  border-radius: 5px;
}
/* General form input styling */
.register-container input[type="text"],
.register-container input[type="email"],
.register-container input[type="password"],
.register-container input[type="tel"],
.register-container input[type="date"] {
  width: 100%;
  padding: 12px;
  margin-bottom: 15px;
  border: 1px solid #00ffff; /* cyan border */
  border-radius: 6px;
  background-color: #0d1117; /* dark background */
  color: #00ffff; /* cyan text */
  font-size: 14px;
}

/* Focus effect */
.register-container input:focus {
  outline: none;
  border-color: #00ffff;
  box-shadow: 0 0 8px #00ffff66;
}
.register-container label {
  display: block;
  margin-bottom: 5px;
  color: #00ffff;
  font-size: 13px;
  letter-spacing: 1px;
}

    </style>
</head>
<body>
  <div class="register-container">
    <h2>Create Account</h2>
    <?php if ($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
      <p class="success"><?= $success ?></p>
    <?php endif; ?>
    <form method="POST">
      <input type="text" name="name" placeholder="Full Name" required><br>
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <input type="password" name="confirm" placeholder="Confirm Password" required><br>
      <input type="tel" name="phone" placeholder="Phone Number" required><br>
      <input type="date" name="dob" placeholder="Date of Birth" required><br>
      <input type="text" name="city" placeholder="City" required><br>
      <input type="text" name="country" placeholder="Country" required><br>
      <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="userlogin.php">Login here</a></p>
  </div>
</body>
</html>
