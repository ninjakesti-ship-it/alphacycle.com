<?php
// This PHP script connects to the database, fetches user data,
// and handles user deletion.

// Include the database connection file.
// NOTE: Make sure 'db_connect.php' exists and is correctly configured.
// For demonstration, we assume it's in the same directory.
include 'db_connect.php';

// Get the search query from the URL parameter, default to an empty string.
$search = $_GET['search'] ?? '';

// Start with the base SQL query to select all users.
$sql = "SELECT * FROM users";

// If a search query is present, append a WHERE clause to filter the results.
if (!empty($search)) {
    // Sanitize the search input to prevent SQL injection.
    $searchEscaped = $conn->real_escape_string($search);
    $sql .= " WHERE name LIKE '%$searchEscaped%' OR email LIKE '%$searchEscaped%' OR city LIKE '%$searchEscaped%'";
}

// Order the results by creation date in descending order.
$sql .= " ORDER BY created_at DESC";

// Execute the SQL query.
$result = $conn->query($sql);

// Handle the deletion of a user if a POST request is made.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    // Get the user ID from the form.
    $user_id = $_POST['user_id'];
    
    // Sanitize the user ID to prevent SQL injection.
    $userIdEscaped = $conn->real_escape_string($user_id);
    
    // Execute the DELETE query.
    $conn->query("DELETE FROM users WHERE id = '$userIdEscaped'");
    
    // Redirect the user to the same page to show the updated list.
    header("Location: view_users.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users | AlphaFuel Admin</title>
    <style>
        /* Dark Theme Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #121212; /* Very dark background */
            color: #e0e0e0; /* Light gray text */
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #ffffff; /* White heading */
            margin-bottom: 20px;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        }

        .search-form {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-form input[type="text"] {
            padding: 10px;
            width: 300px;
            background-color: #2c2c2c; /* Dark input background */
            border: 1px solid #444; /* Darker border */
            border-radius: 8px;
            color: #e0e0e0;
            transition: all 0.3s ease;
        }

        .search-form input[type="text"]:focus {
            outline: none;
            border-color: #61dafb; /* Light blue focus border */
            box-shadow: 0 0 5px rgba(97, 218, 251, 0.5);
        }

        .search-form button {
            padding: 10px 18px;
            background-color: #61dafb; /* Light blue button */
            color: #121212;
            border: none;
            border-radius: 8px;
            margin-left: 10px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        
        .search-form button:hover {
            background-color: #21a1f1; /* Darker blue on hover */
            transform: translateY(-2px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #1e1e1e; /* Table background */
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            border-radius: 12px;
            overflow: hidden; /* Ensures rounded corners on the table */
        }

        th, td {
            padding: 15px;
            border: 1px solid #333;
            text-align: center;
        }

        th {
            background-color: #282c34; /* Table header dark color */
            color: #fff;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #222222;
        }

        tr:hover {
            background-color: #333333;
            transition: background-color 0.3s ease;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .edit-btn {
            background-color: #2ecc71; /* Green color for edit */
        }

        .delete-btn {
            background-color: #e74c3c; /* Red color for delete */
        }

        .edit-btn:hover {
            background-color: #27ad60;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<h2>Registered Users</h2>

<div class="search-form">
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by name, email, or city" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>DOB</th>
            <th>City</th>
            <th>Country</th>
            <th>Phone Number</th>
            <th>Registered On</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['dob']) ?></td>
                    <td><?= htmlspecialchars($row['city']) ?></td>
                    <td><?= htmlspecialchars($row['country']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn edit-btn">Edit</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="delete_user" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="9">No users found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
