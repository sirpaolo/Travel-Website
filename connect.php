<?php

require 'GoogleAPI/config.php';

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

    // GET CURRENT LOGGED-IN email
    $email = $_SESSION['user']['email'] ?? null;

    // GET CUSTOMER_ID by email
    $sql = "SELECT CUSTOMER_ID FROM ACCOUNTS WHERE EMAIL = ?";
    $stmt = sqlsrv_query($conn, $sql, [$email]);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if (!$row) {
        echo "ID not found";
        sqlsrv_close($conn);
        exit;
    }

    $customer_id = $row['CUSTOMER_ID'];


    $destination = $_POST["destination"];
    $country = $_POST["country"];
    $total_php = $_POST["total_php"];

    //insert booking    
    $date = date('Y-m-d H:i:s'); // format: 2025-01-30 14:23:10   
    $sql = "INSERT INTO BOOKINGS (CUSTOMER_ID, DESTINATION, COUNTRY, PAYMENT_AMOUNT, PAYMENT_METHOD, DATE_TIME) VALUES (?, ?, ?, ?, ?, ?)";
    $params = [$customer_id, $destination, $country, $total_php, 'CARD', $date];
    $row = sqlsrv_query($conn, $sql, $params);

    if ($row === false) {
        die(print_r(sqlsrv_errors(),true)); 
    }   else{
            header("Location: success.php");
            exit();

    }         