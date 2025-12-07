<?php

$serverName="HELIOS"; 
$connectionOptions=[ 
    "Database"=>"DLSU", 
    "Uid"=>"", 
    "PWD"=>"" 
]; 

$conn = sqlsrv_connect($serverName, $connectionOptions);
if($conn == false){
    die(print_r(sqlsrv_errors(), true));
}

$email = $_POST['email'];
$password = $_POST['password'];

// Find account by email only
$sql = "SELECT * FROM USERS WHERE EMAIL = ?";
$result_check = sqlsrv_query($conn, $sql, [$email]);

$row = sqlsrv_fetch_array($result_check);
$username = $row['NAME'];

if(!$row){
    echo "Email not found";
    die();
}

// check password and get name
if($password == $row['PASSWORD']){
    echo "Welcome back, " . $username . "!";
    header("Location: home.php");

} else {
    echo "Incorrect password";
}

sqlsrv_close($conn);
?>
