<?php
    require 'security_config.php';
    startSecureSession();

    session_unset();
    session_destroy();

    header('Location: index.php');
    exit();
