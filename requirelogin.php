<?php
// requirelogin.php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If not logged in, save current URL and redirect to login page.
if (!isset($_SESSION['user'])) {
    // Save where to return after login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];

    // Redirect to the login page (change path to your actual login page)
    header('Location: ../GoogleAPI/login.php?login=1'); // the ?login=1 will tell login.php to auto-open modal
    exit;
}
