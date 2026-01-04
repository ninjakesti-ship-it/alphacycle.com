<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: userlogin.php");
    exit();
}

$user_email = $_SESSION['user']['email'];
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $dob = $_POST['dob'];
    $city = trim($_POST['city']);
    $country = trim($_POST['country']);
    $phone = trim($_POST['phone']);

    $sql = "UPDATE users SET name=?, dob=?, city=?, country=?, phone=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $dob, $city, $country, $phone, $user_email);

    if ($stmt->execute()) {
        $success = "Your information has been updated successfully.";
        $_SESSION['user']['name'] = $name; // Update session
    } else {
        $error = "Failed to update your information. Please try again.";
    }
}

// Fetch current user info
$stmt = $conn->prepare("SELECT name, dob, city, country, phone FROM users WHERE email=?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Your Profile</title>
    <style>
        body {
            background-color: #f3f4f6;
            color: #111;
            font-family: 'Orbitron', sans-serif;
            padding: 2rem;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-top: 1rem;
            font-weight: bold;
        }

        input[type="text"], input[type="email"], input[type="date"], input[type="tel"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #00bcd4;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 2rem;
            width: 100%;
            font-size: 1rem;
        }

        button:hover {
            background-color: #0097a7;
        }

        .message {
            text-align: center;
            margin-top: 1rem;
            font-weight: bold;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }
    </style>
</head>
<body>

<div style="position: absolute; top: 20px; left: 20px;">
    <a href="view_users.php" 
       style="background-color: #007bff; 
              color: white; 
              padding: 8px 15px; 
              border-radius: 5px; 
              text-decoration: none; 
              font-size: 14px;
              box-shadow: 0 2px 5px rgba(0,0,0,0.2);
              transition: background-color 0.3s ease;">
        ⬅ Back
    </a>
</div>

<div class="container">
    <h2>Edit Your Profile</h2>

    <?php if ($success): ?>
        <p class="message success"><?= htmlspecialchars($success) ?></p>
    <?php elseif ($error): ?>
        <p class="message error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="name">Full Name:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>" required>

        <label for="dob">Date of Birth:</label>
        <input type="date" name="dob" id="dob" value="<?= htmlspecialchars($user['dob']) ?>" required>

        <label for="city">City:</label>
        <input type="text" name="city" id="city" value="<?= htmlspecialchars($user['city']) ?>" required>

        <label for="country">Country:</label>
        <input type="text" name="country" id="country" value="<?= htmlspecialchars($user['country']) ?>" required>

        <label for="phone">Phone Number:</label>
        <input type="tel" name="phone" id="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>

        <button type="submit">Update Profile</button>
    </form>
</div>

</body>
</html>
