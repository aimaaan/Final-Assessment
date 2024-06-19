<?php
require 'db.php';
require 'vendor/autoload.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $google2fa_code = trim($_POST['google2fa_code']);

    // Retrieve user from the database
    $stmt = $pdo->prepare('SELECT id, password, google2fa_secret FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $google2fa = new GoogleAuthenticator();

        // Verify the 2FA code
        if ($google2fa->checkCode($user['google2fa_secret'], $google2fa_code)) {
            // Start session and store user information
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user['id'];
            header('Location: main.php');
        } else {
            echo 'Invalid 2FA code.';
        }
    } else {
        echo 'Invalid username or password';
    }
}
?>
