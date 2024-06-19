<?php
require 'db.php';
require 'vendor/autoload.php';
use PragmaRX\Google2FA\Google2FA;
use Endroid\QrCode\QrCode;

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$google2fa = new Google2FA();

// Retrieve user data
$stmt = $pdo->prepare('SELECT google2fa_secret FROM users WHERE id = :id');
$stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$google2fa_url = $google2fa->getQRCodeUrl(
    'YourAppName',
    $_SESSION['username'],
    $user['google2fa_secret']
);

$qrCode = new QrCode($google2fa_url);
header('Content-Type: '.$qrCode->getContentType());
echo $qrCode->writeString();
