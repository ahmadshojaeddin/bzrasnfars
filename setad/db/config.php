<?php
/* Database credentials.*/
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
// define('DB_PASSWORD', '21826K@ri');
define('DB_PASSWORD', '1234');
define('DB_NAME', 'setad');

/* Attempt to connect to MySQL database */
$mysqli_result = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($mysqli_result === false) {
    die("db/config.php: Could not connect to MySQL" . mysqli_connect_error());
}