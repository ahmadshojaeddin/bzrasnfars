<!-- test -->
<div id="test" class="row m-3">

    <?php

    echo "ver 2.0<br>";

    // $servername = "localhost";
    // $dbname = "bzrasnfa_db";
    // $username = "bzrasnfa";
    // $password = "D9TUarnQF3";

    $servername = "localhost";
    $dbname = "bzrasnfa_db";
    $username = "root";
    $password = "1234";


    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        $conn->set_charset("utf8");
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        echo "connected cuccessfully...  ;)<br>";
        echo "<br>";

        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);

        echo "rows count: " . $result->num_rows . "<br>";
        echo "<br>";

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                echo $row["id"] . "- " . $row["name"] . " " . $row["surename"] . "<br>";
            }
        } else {
            echo "0 results";
        }

        $conn->close();
    } catch (mysqli_sql_exception $e) {

        echo "error: " . $e->getMessage();
    }

    ?>


    <?php

    // $servername = "localhost";
    // $dbname = "bzrasnfa_db";
    // $username = "bzrasnfa";
    // $password = "D9TUarnQF3";

    // try {
    //     $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    //     // set the PDO error mode to exception
    //     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //     echo "Connected successfully";
    // } catch (PDOException $e) {
    //     echo "Connection failed: " . $e->getMessage();
    // }

    ?>




</div>