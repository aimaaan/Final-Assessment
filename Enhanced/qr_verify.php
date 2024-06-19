<?php
require 'db.php';
require 'vendor/autoload.php';
use PragmaRX\Google2FA\Google2FA;

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$google2fa = new Google2FA();
$google2fa_code = trim($_POST['google2fa_code']);

// Retrieve user data
$stmt = $pdo->prepare('SELECT google2fa_secret FROM users WHERE id = :id');
$stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($google2fa->verifyKey($user['google2fa_secret'], $google2fa_code)) {
    // 2FA setup complete
    header('Location: main.php');
} else {
    echo 'Invalid 2FA code. Please try again.';
}

