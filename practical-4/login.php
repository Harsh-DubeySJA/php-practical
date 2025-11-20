<?php
    $default_user = "Harsh";
    $default_pass = "easy password";

    if (isset($_POST['username']) && isset($_POST['password'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];
        if (strcasecmp($user, $default_user) == 0 && strcmp($pass, $default_pass) == 0) {
            http_response_code(200);
            echo "Welcome " . $user;
        } else {
            http_response_code(400);
            echo "username/password is wrong.";
        }
    }

    if (isset($_GET['username'])) {
        http_response_code(200);
        echo $default_user;
    }
