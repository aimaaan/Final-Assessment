<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["loggedIn"])) {
    $_SESSION["loggedIn"] = false;
}

if ($_SESSION["loggedIn"]) {
    echo "Welcome, " . htmlspecialchars($_SESSION["username"]);
}

if (isset($_SESSION['expiry_time']) && time() > $_SESSION['expiry_time']) {

    $redirectAfterLogin = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : 'main.php';

    session_destroy();
    session_start();
    $_SESSION['redirect_after_login'] = $redirectAfterLogin;

    header('Location: index.php'); 
    exit();
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];

    header("Location: index.php"); 
    exit();
}

if (basename($_SERVER['PHP_SELF']) == 'index.php' && isset($_SESSION['redirect_after_login'])) {
    $redirectURL = $_SESSION['redirect_after_login'];
    unset($_SESSION['redirect_after_login']); 
    header("Location: $redirectURL");
    exit();
}

// Function to authorize user based on roles
function authorize($allowed_roles) {
    if (!in_array($_SESSION['role'], $allowed_roles)) {
        echo "You are not authorized to view this page.";
        exit();
    }
}
