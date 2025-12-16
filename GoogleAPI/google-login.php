<?php
// google-login.php 
// Sends user to Google for login

// Load configuration (Google API keys, redirect URL, DB connection, session_start())
require __DIR__ . '/config.php'; 

// Load Composer's autoloader so Google API Client library is available
require __DIR__ . '/../vendor/autoload.php'; 

// Initialize Google OAuth client and request the permissions (email, profile) needed for Google Login
$client = new Google_Client();

// Permissions to perform google login
$client->setClientId(GOOGLE_CLIENT_ID); // Identify which Google app is making the request
$client->setClientSecret(GOOGLE_CLIENT_SECRET); //// Prove the request is coming from the app
$client->setRedirectUri(GOOGLE_REDIRECT_URL); //Tell Google where to send the user after login
$client->addScope("email"); // Request access to the user's email address
$client->addScope("profile"); // Request access to the user's basic profile information (name, picture)

// Create the URL that sends the user to Google's login page
$auth_url = $client->createAuthUrl();

// Send (redirect) the user to Google so they can choose an account and log in
header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));

exit();
