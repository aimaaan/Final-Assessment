<?php
require 'security_config.php';
startSecureSession();  

require 'db.php';
require 'vendor/autoload.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

define('MAX_ATTEMPTS', 5);
define('LOCKOUT_DURATION', '15 minutes'); // Lockout duration

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!validateCsrfToken($csrf_token)) {
        $_SESSION['error'] = 'Invalid CSRF token. Please try again.';
        header('Location: index.php');
        exit();
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $google2fa_code = trim($_POST['google2fa_code']);

    // Retrieve user from the database
    $stmt = $pdo->prepare('SELECT users.id, users.password, users.google2fa_secret, users.failed_attempts, users.lockout_time, roles.name as role 
                           FROM users 
                           JOIN user_roles ON users.id = user_roles.user_id 
                           JOIN roles ON user_roles.role_id = roles.id 
                           WHERE username = :username');
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Check if the account is locked
        if ($user['lockout_time'] && new DateTime() < new DateTime($user['lockout_time'])) {
            $_SESSION['error'] = 'Account is locked. Please try again later.';
            header('Location: index.php');
            exit();
        }

        // Verify password
        if (password_verify($password, $user['password'])) {
            $google2fa = new GoogleAuthenticator();

            // Verify the 2FA code
            if ($google2fa->checkCode($user['google2fa_secret'], $google2fa_code)) {
                // Reset failed attempts on successful login
                $stmt = $pdo->prepare('UPDATE users SET failed_attempts = 0, lockout_time = NULL WHERE id = :id');
                $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
                $stmt->execute();

                // Start session and store user information
                $_SESSION['username'] = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'Admin') {
                    header('Location: booking_crud.php');
                } else {
                    header('Location: main.php');
                }
                exit();
            } else {
                $_SESSION['error'] = 'Invalid 2FA code.';
            }
        } else {
            $_SESSION['error'] = 'Invalid username or password.';
        }

        // Increment failed attempts
        $failed_attempts = $user['failed_attempts'] + 1;
        $lockout_time = null;
        if ($failed_attempts >= MAX_ATTEMPTS) {
            $lockout_time = (new DateTime())->modify('+' . LOCKOUT_DURATION)->format('Y-m-d H:i:s');
            $_SESSION['error'] = 'Account locked due to too many failed attempts. Please try again later.';
        }

        // Update failed attempts and lockout time
        $stmt = $pdo->prepare('UPDATE users SET failed_attempts = :failed_attempts, lockout_time = :lockout_time WHERE id = :id');
        $stmt->bindParam(':failed_attempts', $failed_attempts, PDO::PARAM_INT);
        $stmt->bindParam(':lockout_time', $lockout_time, PDO::PARAM_STR);
        $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
        $stmt->execute();
        
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['error'] = 'Invalid username or password.';
        header('Location: index.php');
        exit();
    }
}
