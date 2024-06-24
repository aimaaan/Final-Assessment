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
            'secure' => isset($_SERVER['HTTPS']),  // Secure if HTTPS is used
            'httponly' => true,
            'samesite' => 'Strict'
        ]);

        session_start();
        session_regenerate_id(true);  // Regenerate session ID to prevent fixation
    }
}

// Implement CSP
function setCSP() {
    $csp = "Content-Security-Policy: " .
           "default-src 'self';" .
           "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://ajax.googleapis.com https://kit.fontawesome.com;" .
           "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com https://ka-f.fontawesome.com;" .
           "img-src 'self' https://trusted-image-source.com;" . // Include trusted image sources
           "media-src 'self' https://trusted-media-source.com;" . // Include trusted media sources (audio, video)
           "frame-src 'none';" .
           "font-src 'self' https://fonts.gstatic.com https://ka-f.fontawesome.com;" .
           "connect-src 'self';";
    header($csp);

    // Additional security headers
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: DENY");
    header("X-XSS-Protection: 1; mode=block");
    if (isset($_SERVER['HTTPS'])) {
        header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
    }
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
?>
