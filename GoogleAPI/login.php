<?php

// ensure session is started so we can read redirect_after_login
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}


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
    $sql = "SELECT * FROM ACCOUNTS WHERE EMAIL = ?";
    $result_check = sqlsrv_query($conn, $sql, [$email]);
    if ($result_check === false) {
        sqlsrv_close($conn);
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($result_check, SQLSRV_FETCH_ASSOC);

    if(!$row){
        echo "Email not found";
        sqlsrv_close($conn);
        die();
    }

    // check password and set session
    if ($password == $row['PASSWORD']) {

        // Make sure session exists
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Store user info in session
        $_SESSION['user'] = [
            'userid'   => $row['CUSTOMER_ID'],
            'name'     => $row['NAME'],
            'email'    => $row['EMAIL'],
            'picture'  => $row['PICTURE'] ?? '',
            'provider' => 'local'
        ];

        // log login activity
        $date = date('Y-m-d H:i:s');
        $sql_user = "INSERT INTO LOG_IN (EMAIL, CUSTOMER_ID, DATE_TIME) VALUES (?, ?, ?)";
        sqlsrv_query($conn, $sql_user, [$row['EMAIL'], $row['CUSTOMER_ID'], $date]);

        sqlsrv_close($conn);

        // Redirect user
        if (!empty($_SESSION['redirect_after_login'])) {
            $redirect = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);
            header("Location: $redirect");
            exit();
        }

        header("Location: ../home.php");
        exit();
    }else {
            echo "Incorrect password";
            sqlsrv_close($conn);
            exit();
        }
?>