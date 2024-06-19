<?php
require 'vendor/autoload.php';
use PragmaRX\Google2FA\Google2FA;

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    if (strlen($username) < 3 || strlen($password) < 6) {
        echo 'Username must be at least 3 characters and password must be at least 6 characters long.';
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $google2fa = new Google2FA();
    $secret = $google2fa->generateSecretKey();

    try {
        $stmt = $pdo->prepare('INSERT INTO users (username, password, google2fa_secret) VALUES (:username, :password, :google2fa_secret)');
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':google2fa_secret', $secret, PDO::PARAM_STR);
        $stmt->execute();
        
        $user_id = $pdo->lastInsertId();
        $stmt = $pdo->prepare('INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)');
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':role_id', $role, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: index.php');
    } catch (PDOException $e) {
        echo 'Registration failed: ' . $e->getMessage();
    }
}


