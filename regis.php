<?php 
    $serverName="HELIOS"; 
    $connectionOptions=[ 
        "Database"=>"DLSU", 
        "Uid"=>"", 
        "PWD"=>"" 
    ]; 
    $conn=sqlsrv_connect($serverName, $connectionOptions); 
    if($conn==false) {
        die(print_r(sqlsrv_errors(),true)); 
    } 

    $name=$_POST['name'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $confirm_password=$_POST['confirm_password'];

    // Check if username exists
    $sql_check = "SELECT * FROM USERS WHERE EMAIL = ?";
    $result_check = sqlsrv_query($conn, $sql_check, [$email]);
    $row = sqlsrv_fetch_array($result_check);
    if($row){
        echo "User email already exists";
        die();
    }

    // Validation of password
    if($password == $confirm_password){
        // Insert new user
        $sql_user = "INSERT INTO USERS (NAME, EMAIL, PASSWORD) VALUES (?, ?, ?)";
        $result_user = sqlsrv_query($conn, $sql_user, [$name, $email, $password]);
        

        // Get the newly inserted user's ID
        $sql_get_id = "SELECT USERID FROM USERS WHERE NAME = ?";
        $result_get_id = sqlsrv_query($conn, $sql_get_id, [$name]);
        $user_row = sqlsrv_fetch_array($result_get_id);
        $user_id = $user_row['USERID'];

        // Get new user's name 
        $sql_profile = "SELECT TOP 1 NAME FROM USERS ORDER BY USERID DESC";
        $result_profile = sqlsrv_query($conn, $sql_profile);
        $row_profile = sqlsrv_fetch_array($result_profile);
        $username = $row_profile['NAME'];
        
        echo "Welcome, " . $username . "! Your account has been created.";
        header("Location: home.php");

        
    } else {
        echo 'Password does not match';
    }

    sqlsrv_close($conn);
?>

