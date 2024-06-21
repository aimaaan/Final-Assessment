<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize "loggedIn" session variable if it doesn't exist
if (!isset($_SESSION["loggedIn"])) {
    $_SESSION["loggedIn"] = false;
}

// Check if the user is logged in and display a welcome message if true
if ($_SESSION["loggedIn"]) {
    echo "Welcome, " . htmlspecialchars($_SESSION["username"]);
}

// Check for session expiry or forced logout
if (isset($_SESSION['expiry_time']) && time() > $_SESSION['expiry_time']) {
    // Save the intended destination before destroying the session
    $redirectAfterLogin = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : 'main.php';

    // Destroy the session and start a new one for the redirect
    session_destroy();
    session_start();
    $_SESSION['redirect_after_login'] = $redirectAfterLogin;

    header('Location: index.php'); // Go to login page
    exit();
}

// Check if the user is not logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    // Store the current URL for redirection after login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];

    header("Location: index.php"); // Redirect to login page
    exit();
}

// If the user is logged in and attempts to access the login page, redirect them
if (basename($_SERVER['PHP_SELF']) == 'index.php' && isset($_SESSION['redirect_after_login'])) {
    $redirectURL = $_SESSION['redirect_after_login'];
    unset($_SESSION['redirect_after_login']); // Clear the redirection target after use
    header("Location: $redirectURL");
    exit();
}
