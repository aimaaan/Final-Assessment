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
            'domain' => $_SERVER['HTTP_HOST'],  // Dynamic domain
            'secure' => true,  // Secure attribute set to true for HTTPS only
            'httponly' => true,
            'samesite' => 'Strict'  // SameSite attribute set to Strict for CSRF protection
        ]);

        session_start();
        session_regenerate_id(true);  // Regenerate session ID to prevent fixation
    }
}

// Implement CSP and other security headers
function setCSP() {
    // Content-Security-Policy
    $csp = "Content-Security-Policy: " .
           "default-src 'self';" .
           " script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js;" .
           " object-src 'none';" .
           " style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com;" .
           " img-src 'self';" .
           " media-src 'none';" .
           " frame-src 'none';" .
           " font-src 'self' https://fonts.gstatic.com;" .
           " connect-src 'self';";

    // Set CSP header
    header($csp);

    // Additional security headers
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: DENY");
    header("X-XSS-Protection: 1; mode=block");
    
    // If HTTPS is used, set Strict-Transport-Security header
    if (isset($_SERVER['HTTPS'])) {
        header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
    }
}

// Function to generate CSRF token 
function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Function to validate CSRF token 
function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Start secure session and apply CSP
startSecureSession();
setCSP();
?>
