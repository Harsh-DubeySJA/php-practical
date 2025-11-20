<?php
    $servername = "switchyard.proxy.rlwy.net:58983";
    $username = "root";
    $password = "rLrxmuSYmvNfIvMIJUFrQAFnkwYfTxRy";
    $dbname = "railway";
    
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo "Connected successfully to the database!";
    $conn->close();
?>
