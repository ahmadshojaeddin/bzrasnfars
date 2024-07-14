<?php
    define('DB_SERVER', 'localhost:3306');
    define('DB_USERNAME', 'bzrasnfa');
    define('DB_PASSWORD', 'D9TUarnQF3');
    define('DB_DATABASE', 'bzrasnfa_db');
    try {
        $db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        mysqli_set_charset($db,"utf8");
        // echo "mysqli connected successfullt ;)";
    } catch(Exception $exp) {
        die("ERROR: Could not connect. " . $exp->getMessage());
    }
    // Check connection
    if ($db === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
?>