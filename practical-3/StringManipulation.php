<?php
    function invalidInput() {
        http_response_code(400);
        echo "Invalid Input";
    }

    if (isset($_POST['stringLength'])) {
        http_response_code(200);
        echo strlen($_POST['stringLength']);
        die();
    }

    if (isset($_POST['string']) && isset($_POST['substring'])) {
        $input   = $_POST['string'];
        $pattern = $_POST['substring'];
        http_response_code(200);
        if (str_contains($input, $pattern)) {
            echo $input . " contains " . $pattern;
        } else {
            echo $input . " does not contain " . $pattern;
        }
        die();
    }

    if (isset($_POST['string']) && isset($_POST['search']) && isset($_POST['replace'])) {
        $input = $_POST['string'];
        $search = $_POST['search'];
        $replace = $_POST['replace'];
        $new_string = str_replace($search, $replace, $input);
        http_response_code(200);
        echo "After replacing: " . $new_string;
        die();
    }

    if (isset($_POST['whitespace'])) {
        http_response_code(200);
        echo trim($_POST['whitespace']);
        die();
    }

    if (isset($_POST['isString'])) {
        if (strlen($_POST['isString']) == 0) {
            http_response_code(400);
            echo "Empty String";
        } else {
            http_response_code(200);
            echo "Input is a valid string";
        }
        die();
    }

    if (isset($_POST['titleCase'])) {
        http_response_code(200);
        echo ucfirst($_POST['titleCase']);
        die();
    }
    
    invalidInput();
