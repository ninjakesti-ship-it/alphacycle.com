<?php
// This PHP script handles the stock update via an AJAX request.

// Include the database connection file.
include 'db_connect.php';

// Set the content type to JSON to tell the browser what to expect.
header('Content-Type: application/json');

// Define the allowed database tables to prevent SQL injection.
$allowed_tables = ["mountain","gravel","road","ch","kids","ebikes","accessories","clothing"];

// Check if the request is a POST method.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // If not a POST request, return an error message as JSON.
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Get and sanitize the input from the POST request.
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$table = $_POST['table'] ?? '';
$stock = isset($_POST['stock']) ? (int)$_POST['stock'] : -1;

// Validate the input to ensure it's safe and correct.
if ($id <= 0 || $stock < 0 || !in_array($table, $allowed_tables, true)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input provided.']);
    exit;
}

// Prepare and execute a query to check if the table exists.
$chk = $conn->prepare("SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?");
$chk->bind_param('s', $table);
$chk->execute();
$chk->store_result();

// If the table doesn't exist, return an error.
if ($chk->num_rows === 0) {
    $chk->close();
    echo json_encode(['success' => false, 'message' => "Table '$table' does not exist."]);
    exit;
}
$chk->close();

// Prepare and execute the update query.
$stmt = $conn->prepare("UPDATE `$table` SET stock = ? WHERE id = ?");
$stmt->bind_param('ii', $stock, $id);
$stmt->execute();

// Check if the update was successful and return a corresponding JSON response.
if ($stmt->affected_rows >= 0) {
    echo json_encode(['success' => true, 'message' => 'Stock updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update stock or no changes were made.']);
}

// Close the statement and database connection.
$stmt->close();
$conn->close();

// Exit to ensure no extra output is sent.
exit;
