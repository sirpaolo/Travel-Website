<?php

session_start();

$serverName="HELIOS"; 
$connectionOptions=[ 
    "Database"=>"DLSU", 
    "Uid"=>"", 
    "PWD"=>"" 
]; 

$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn == false) {
    die(print_r(sqlsrv_errors(), true));
} 

// GOOGLE LOGIN SETTINGS
if (!defined('GOOGLE_CLIENT_ID')) { //identifies the app
    define('GOOGLE_CLIENT_ID', 'yourclientid');
}
if (!defined('GOOGLE_CLIENT_SECRET')) { //proves authenticity
    define('GOOGLE_CLIENT_SECRET', 'yourclientscret');
}
if (!defined('GOOGLE_REDIRECT_URL')) { //where to go after login
    define('GOOGLE_REDIRECT_URL', 'yoururl');
}
