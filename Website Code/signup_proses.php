<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm-password'] ?? '';

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        die('Please fill all fields.');
    }

    if ($password !== $confirm_password) {
        die('Passwords do not match.');
    }

    // Check if username or email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        die('Username or Email already exists.');
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hashed_password]);

    $_SESSION['user'] = $username;
    header('Location: index.html');
    exit();
} else {
    header('Location: signup.html');
    exit();
}
?>
