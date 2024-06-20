<?php
require 'session_checks.php';
require 'db.php';
require 'vendor/autoload.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    if (strlen($username) < 3 || strlen($password) < 6) {
        echo 'Username must be at least 3 characters and password must be at least 6 characters long.';
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Generate 2FA secret
    $google2fa = new GoogleAuthenticator();
    $google2fa_secret = $google2fa->generateSecret();

    try {
        // Insert user into the users table
        $stmt = $pdo->prepare('INSERT INTO users (username, password, google2fa_secret) VALUES (:username, :password, :google2fa_secret)');
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':google2fa_secret', $google2fa_secret, PDO::PARAM_STR);
        $stmt->execute();
        
        // Get the user_id of the newly inserted user
        $user_id = $pdo->lastInsertId();

        // Assign role to the user
        $stmt = $pdo->prepare('INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)');
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':role_id', $role, PDO::PARAM_INT);
        $stmt->execute();

        // Redirect to display QR code
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        header('Location: qr.php');
    } catch (PDOException $e) {
        echo 'Registration failed: ' . $e->getMessage();
    }
}
