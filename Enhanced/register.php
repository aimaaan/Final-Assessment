<?php
require 'security_config.php';
startSecureSession();

require 'db.php';
require 'vendor/autoload.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

function sanitizeInput($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

$error_username = '';
$error_password = '';
$error_role = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!validateCsrfToken($csrf_token)) {
        $_SESSION['error'] = 'Invalid CSRF token. Please try again.';
        header('Location: registration.php');
        exit();
    }

    $username = sanitizeInput(trim($_POST['username']));
    $password = trim($_POST['password']);
    $role = sanitizeInput(trim($_POST['role']));

    // Username validation
    if (strlen($username) < 3 || strlen($username) > 20 || !preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        $error_username = "Username must be 3-20 characters long and contain only letters, numbers, and underscores.";
    }

    // Password validation
    $password_pattern = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{12,}$/';
    if (empty($password)) {
        $error_password = "Password is required.";
    } elseif (!preg_match($password_pattern, $password)) {
        $error_password = "Password must be at least 12 characters long and include at least one number, one lowercase letter, one uppercase letter, and one special character.";
    } elseif ($password === $username) {
        $error_password = "Password cannot be the same as the username.";
    } else {
        // Check if the password was previously used
        $stmt = $pdo->prepare('SELECT password FROM previous_passwords WHERE username = :username');
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $previous_passwords = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($previous_passwords as $previous_password) {
            if (password_verify($password, $previous_password)) {
                $error_password = "You cannot reuse a previous password.";
                break;
            }
        }
    }

    // Role validation
    if (empty($role) || !preg_match("/^[0-9]+$/", $role)) {
        $error_role = "Invalid role selected.";
    }

    if (empty($error_username) && empty($error_password) && empty($error_role)) {
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

            // Store the current password in previous_passwords table
            $stmt = $pdo->prepare('INSERT INTO previous_passwords (username, password) VALUES (:username, :password)');
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $stmt->execute();

            // Redirect to display QR code
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            header('Location: qr.php');
            exit();
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Registration failed. Please try again.';
            header('Location: registration.php');
            exit();
        }
    }
}