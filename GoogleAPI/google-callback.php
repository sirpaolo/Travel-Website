<?php

// google-callback.php, Processes Google’s response and logs user in

require __DIR__ . '/config.php';               // load config
require __DIR__ . '/../vendor/autoload.php';   // load composer


// config.php already calls session_start() and creates $conn

// Initialize Google OAuth client and request the permissions (email, profile) needed for Google Login
$client = new Google_Client();

// Permissions to perform google login
$client->setClientId(GOOGLE_CLIENT_ID); // Identify which Google app is making the request
$client->setClientSecret(GOOGLE_CLIENT_SECRET); //// Prove the request is coming from the app
$client->setRedirectUri(GOOGLE_REDIRECT_URL); //Tell Google where to send the user after login
$client->addScope("email"); // Request access to the user's email address
$client->addScope("profile"); // Request access to the user's basic profile information (name, picture)


//If the user did NOT finish the Google login process successfull or The user has not granted Google permission
if (!isset($_GET['code'])) { 
    $auth_url = $client->createAuthUrl(); // Generate the Google login URL (where user chooses account / grants permission)
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL)); // Redirect the user to the Google login/consent page
    exit();
}

$token = $client->fetchAccessTokenWithAuthCode($_GET['code']); // Exchange the authorization code (from $_GET['code']) for an access token
if (empty($token) || isset($token['error'])) { // validates If token is missing or contains an error,
    $errMsg = "Failed to get access token.";
    if (is_array($token) && isset($token['error'])) {
        $errMsg .= " Error: " . $token['error'] . (isset($token['error_description']) ? " - " . $token['error_description'] : "");
    }
    die($errMsg);
}

$client->setAccessToken($token); // Stores the token so the client can make authenticated requests.
$oauth = new Google_Service_Oauth2($client); //creates a new service object to access the Google user info endpoint.
$userInfo = $oauth->userinfo->get(); //Retrieves the actual user's Google profile JSON.

// Ensure DB connection is valid
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Check if user exists
$sql_check = "SELECT * FROM ACCOUNTS WHERE PROVIDER_ID = ? AND PROVIDER = 'google'";
$params_check = [$userInfo->id];
$result_check = sqlsrv_query($conn, $sql_check, $params_check);
if ($result_check === false) {
    die(print_r(sqlsrv_errors(), true));
}
$row = sqlsrv_fetch_array($result_check, SQLSRV_FETCH_ASSOC);


// Insert if not found
if (!$row) {
    $date = date('Y-m-d H:i:s'); // format: 2025-01-30 14:23:10
    $sql_insert = "INSERT INTO ACCOUNTS (NAME, EMAIL, PASSWORD, PROVIDER, PROVIDER_ID, PICTURE, DATE_TIME) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $params_insert = [ //from Google's user profile API
        $userInfo->name,
        $userInfo->email,
        null,
        'GOOGLE',
        $userInfo->id, 
        $userInfo->picture,
        $date
    ];
    $result_insert = sqlsrv_query($conn, $sql_insert, $params_insert);
    if ($result_insert === false) {
        die(print_r(sqlsrv_errors(), true));
    }
}

// Save to session
$_SESSION['user'] = [
    'name' => $userInfo->name,
    'email' => $userInfo->email,
    'picture' => $userInfo->picture
];

header('Location: ../home.php');
exit();
