<?php
    if (isset($_POST['firstNumber']) && is_int((int) $_POST['firstNumber']) && isset($_POST['secondNumber']) && is_int((int) $_POST['secondNumber'])) {
        http_response_code(200);
        echo ((int) $_POST['firstNumber'] + (int) $_POST['secondNumber']);
    } else {
        http_response_code(400);
        echo "Invalid Input";
    }
