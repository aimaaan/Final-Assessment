<?php
require 'session_checks.php';
require 'db.php';
require 'vendor/autoload.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

$google2fa = new GoogleAuthenticator();
$google2fa_code = trim($_POST['google2fa_code']);

// Retrieve user data
$stmt = $pdo->prepare('SELECT google2fa_secret FROM users WHERE id = :id');
$stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($google2fa->checkCode($user['google2fa_secret'], $google2fa_code)) {
    // 2FA setup complete
    header('Location: main.php');
} else {
    echo 'Invalid 2FA code. Please try again.';
}
