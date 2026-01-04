<?php
// Central DB connection. Keep errors logged (not displayed) to avoid breaking JSON responses.
error_reporting(E_ALL);
ini_set('display_errors', 0);              // Don't echo warnings/notices into JSON endpoints
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php-error.log'); // Make sure this path is writable by the web server

$servername = "localhost";  // e.g. localhost
$username   = "root";       // your DB username
$password   = "";           // your DB password
$dbname     = "alphacycles_db"; // your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

// If connected, set proper charset
if (!$conn->connect_error) {
    $conn->set_charset('utf8mb4');
}

if ($conn->connect_error) {
    // Keep this generic so you don't leak details in production
    exit("Database connection failed.");
}