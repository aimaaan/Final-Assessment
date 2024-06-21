<?php
// Set session and cookie security options
function startSecureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        // Session settings are secure when using cookies
        ini_set('session.use_only_cookies', 1);
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params([
            'lifetime' => $cookieParams["lifetime"],
            'path' => $cookieParams["path"],
            'domain' => 'localhost',  // Modify if deploying on a real server
            'secure' => false,  // Set to true if using HTTPS
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        session_start();

        // Regenerate session ID to prevent session fixation attacks
        session_regenerate_id(true);
    } else {
        // Regenerate session if already started to prevent fixation
        session_regenerate_id(true);
    }

    // Setup a cookie directly to specify SameSite attribute if needed
    setcookie(session_name(), session_id(), [
        'samesite' => 'Strict',
        'secure' => false,  // Set to true if using HTTPS
        'httponly' => true,
        'path' => '/',
        'domain' => 'localhost'
    ]);
}

//Implement CSP
function setCSP() {
    $csp = "Content-Security-Policy: default-src 'self';" .
           " script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js;" .
           " object-src 'none';" .
           " style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com;" .
           " img-src 'self';" .
           " media-src 'none';" .
           " frame-src 'none';" .
           " font-src 'self' https://fonts.gstatic.com;" .
           " connect-src 'self';";
    header($csp);
}

function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

startSecureSession();
setCSP();




