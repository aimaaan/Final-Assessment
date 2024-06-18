<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Retrieve user from the database
    $stmt = $pdo->prepare('SELECT id, password FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Start session and store user information
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user['id'];
        header('Location: main.php');
    } else {
        $_SESSION['error'] = 'Invalid username or password';
        header('Location: index.php');
    }
}
