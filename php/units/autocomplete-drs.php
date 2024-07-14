<?php

include_once('php/db/config.php');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    // Create connection
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    $conn->set_charset("utf8");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = $_GET['query'];

    // Perform SQL query
    $sql = "SELECT * FROM drs WHERE name_ LIKE '%$query%' OR code LIKE '%$query%'";
    $result = $conn->query($sql);

    // Fetch results and store in an array
    $suggestions = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $suggestions[] = $row['name_'] . ' | ' . $row['code'];
        }
    }

    // Return suggestions as JSON
    echo json_encode($suggestions);

    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);

    $conn->close();

} catch (mysqli_sql_exception $e) {

    echo "error: " . $e->getMessage();
}
