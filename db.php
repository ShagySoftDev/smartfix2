<?php
// Database connection
$host = "localhost";
$user = "root";        // CHANGE if needed
$pass = "";            // CHANGE if needed
$dbname = "smartfix";  // Ensure you import database.sql first

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Simple helper to sanitize output
function e($str){
    return htmlspecialchars($str ?? "", ENT_QUOTES, 'UTF-8');
}
?>
