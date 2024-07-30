<!-- test -->
<div id="test" class="row m-3 h-25">

    <?php
    
        echo "PDO<br>";

        $servername = "localhost";
        $dbname = "bzrasnfa_db";
        $username = "bzrasnfa";
        $password = "D9TUarnQF3";

        try {

            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
            
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

    ?>




</div>