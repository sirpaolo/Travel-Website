<?php
    $serverName = "MYPC\SQLEXPRESS"; 
    $connectionOptions = [ 
        "Database" => "dlsu", 
        "Uid" => "",   
        "PWD" => "" 
    ]; 

    $conn = sqlsrv_connect($serverName, $connectionOptions); 
    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $complete_name = $_POST['complete_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if username exists
    $sql_check = "SELECT * FROM final_user WHERE username = ?";
    $result_check = sqlsrv_query($conn, $sql_check, [$username]);
    $row = sqlsrv_fetch_array($result_check);
    if($row){
        echo "User already exists";
        die();
    }

    // Validation of password
    if($password == $confirm_password){
        // Insert new user
        $sql_user = "INSERT INTO final_user (complete_name, username, password) VALUES (?, ?, ?)";
        $result_user = sqlsrv_query($conn, $sql_user, [$complete_name, $username, $password]);
        
        if ($result_user) {

            // Get the newly inserted user's ID
            $sql_get_id = "SELECT id FROM final_user WHERE username = ?";
            $result_get_id = sqlsrv_query($conn, $sql_get_id, [$username]);
            $user_row = sqlsrv_fetch_array($result_get_id);
            $user_id = $user_row['id'];


            
            echo "Registration successful!";

            header("Location: index.html");
        }
    } else {
        echo 'Password does not match';
    }

    sqlsrv_close($conn);
?>

