<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .button-container {
            margin-bottom: 10px;
        }

        .button {
            padding: 10px 15px;
            margin-right: 5px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
    </style>
</head>


<div class="button-container">
    <button class="button">جدید</button>
    <button class="button">مرتب سازی</button>
</div>

<table>
    <thead>
        <tr>
            <th>ردیف</th>
            <th>استان</th>
            <th>تاریخ</th>
            <th>وضعیت</th>
            <th></th>
        </tr>
    </thead>
    <tbody>

        <!-- PHP TEST -->
        <?php

        // Page Number:
        $pageNumber = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $itemsPerPage = 5;
        $offset = ($pageNumber - 1) * $itemsPerPage;
        // echo('page: ' . $pageNumber);


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

            // Perform SQL query with GET PARAM
            $sql = "SELECT ins.id as insp_id, date_, st.name_ as state_name, insst.desc_ as status_desc FROM setad.inspections AS ins
                LEFT OUTER JOIN setad.states AS st ON ins.state_code = st.id
                LEFT OUTER JOIN setad.inspection_status AS insst ON ins.status_code = insst.id;";
            // todo: add LIMIT ?,? for pagination
            // todo: use $conn->prepare()/bind_param()/execute() instead, for paginating results useing $_GET['page_num']...
            $result = $conn->query($sql);

            // Fetch results and store in an array
            $suggestions = array();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {

                    $id = $row['insp_id'];
                    $date = $row['date_'];
                    $state = $row['state_name'];
                    $status = $row['status_desc'];

                    echo "
                    <tr>
                        <td>$id</td>
                        <td>$state</td>
                        <td>$date</td>
                        <td>$status</td>
                        <td><a href=\"#\">ویرایش</a></td>
                    </tr>
                    ";
                }
            }

            $conn->close();
            
        } catch (mysqli_sql_exception $e) {
            echo "error: " . $e->getMessage();
        }



        ?>

    </tbody>
</table>

<script>
    function nextPage() {
        let jsPageNumber = <?php echo $pageNumber; ?>;
        // alert('Page num: ' + (jsPageNumber + 1));
        let nextPageNumber = jsPageNumber + 1;
        // window.location.replace('/setad/index.php?link=inspections&page=' + nextPageNumber);
        // alert('href: ' + window.location.href);
        // alert('origin: ' + window.location.origin);
        // alert('origin + pathname : ' + window.location.origin + window.location.pathname);

        let newURL = window.location.origin + window.location.pathname + '?link=inspections&page=' + nextPageNumber;

        // method 1:
        window.location.href = newURL;
        // method 2:
        // window.history.replaceState(null, '', newURL);
    }
</script>

<div class="button-container mt-2">
    <button class="button">اول</button>
    <button class="button">قبل</button>
    <div style="display: inline;">صفحه : ؟</div>
    <button class="button" onclick="nextPage()">بعد</button>
    <button class="button">آخر</button>
</div>

<div class="row"></div>
<div class="row"></div>