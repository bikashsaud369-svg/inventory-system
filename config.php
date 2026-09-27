<?php
// Start session only if it is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Session is used to store login information
}

$host     = "localhost";     
$dbname   = "inventory_db";   
$username = "root";           
$password = "";               

// Create PDO connection (secure way to connect PHP with MySQL)
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Show error messages if something goes wrong
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if user is already logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']); // Returns true if user_id exists in session
}

// Protect pages - redirect to login if user is not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php"); // go to login page
        exit; 
    }
}
?>
