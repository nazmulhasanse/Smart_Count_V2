<?php
    $servername  = "sc2db.liz.com";
    $db_username = "sc2db";
    $db_password = "@dmin@SC2#2024";
    $dbname      = "sc2db";


    // Create a connection to the database
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        // echo "fail";
    }else{
        // echo "success";
    }
?>
