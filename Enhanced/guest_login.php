<?php
require 'security_config.php';
startSecureSession();

$csrf_token = $_POST['csrf_token'] ?? '';
if (!validateCsrfToken($csrf_token)) {
    $_SESSION['error'] = 'Invalid CSRF token. Please try again.';
    header('Location: index.php');
    exit();
}

$_SESSION['username'] = 'Guest';
$_SESSION['user_id'] = 0; 
$_SESSION['role'] = 'Guest';

header('Location: main.php'); 
exit();

