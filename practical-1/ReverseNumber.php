<?php
    if (isset($_POST['number']) && is_int((int) $_POST['number'])) {
        http_response_code(200);
        echo reverseNumber((int) $_POST['number']);
    } else {
        http_response_code(400);
        echo "Invalid Input";
    }

    function reverseNumber($number) {
        $reverse = 0;
        while ($number > 0) {
            $reverse = $reverse * 10 + ($number % 10);
            $number = (int) ($number / 10);
        }
        return $reverse;
    }
