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



<div class="row">

    <div class="col-2">
    </div>

    <div class="col-8">

        <h1 style="padding: 20px;">جدول امتیاز دهی</h1>

        <div class="button-container">
            <button class="button" onclick="window.location.href = '/setad/index.php?link=addnew';">جدید</button>
            <button class="button">مرتب سازی</button>
        </div>

        <!-- مودال کانفریمیشن برای حذف -->
        <div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body text-center" id="confirmationMessage">
                        آبا برای حذف این مورد مطمئن هستید
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-primary" id="yesButton">بله</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">نه</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- جدول موارد بازرسی -->
        <table>

            <thead>
                <tr>
                    <th>شماره</th>
                    <th>استان</th>
                    <th>امتیاز</th>
                    <th>وضعیت</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>

            <tbody>

                <?php

                // Page Number:
                $pageNumber = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                $itemsPerPage = 5;
                $offset = ($pageNumber - 1) * $itemsPerPage;
                // echo ('page: ' . $offset . ' items per page: ' . $itemsPerPage);


                include_once 'php/db/config.php';

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
                    $sql = "SELECT SQL_CALC_FOUND_ROWS ins.id as insp_id, date_, st.name_ as state_name, insst.desc_ as status_desc FROM setad.inspections AS ins
                LEFT OUTER JOIN setad.states AS st ON ins.state_code = st.id
                LEFT OUTER JOIN setad.inspection_status AS insst ON ins.status_code = insst.id
                ORDER BY ins.id DESC
                LIMIT {$offset}, {$itemsPerPage}";
                    $result = $conn->query($sql);

                    // Fetch the total number of records ro Calculate Total Pages Count
                    $totalResult = $conn->query("SELECT FOUND_ROWS() AS total");
                    $rowTotal = $totalResult->fetch_assoc();
                    $totalPages = ceil($rowTotal['total'] / $itemsPerPage);
                    // echo ('total: ' . $totalPages);

                    // Fetch results and store in an array
                    $suggestions = array();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {

                            $insp_id = $row['insp_id'];
                            $date = $row['date_'];
                            $state = $row['state_name'];
                            $status = $row['status_desc'];

                            echo "
                    <tr>
                        <td>$insp_id</td>
                        <td>$state</td>
                        <td>$date</td>
                        <td>$status</td>
                        <td><a href=\"/setad/index.php?link=edit&insp_id=$insp_id\">ویرایش</a></td>
                        <td><a href=\"#\" data-bs-toggle=\"modal\" data-bs-target=\"#confirmationModal\" data-id=\"$insp_id\">حذف</a></td>
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


        <!-- script for delete operation and pass item id to delete modal -->
        <script>
            let itemId = 0; // Variable to store the item ID


            // Event listener to handle all modal showing
            document.querySelectorAll('[data-bs-target="#confirmationModal"]').forEach(function(link) {
                link.addEventListener('click', function() {
                    // Get the ID from the link's data attribute
                    itemId = this.getAttribute('data-id');
                    // alert('itemId: ' + itemId);
                    // Update the modal message to include the item ID
                    document.getElementById('confirmationMessage').innerHTML =
                        `آیا برای حذف ردیف شماره ${itemId} مطمئن هستید؟`;
                });
            });


            // Handle Yes button click
            document.getElementById('yesButton').addEventListener('click', function() {
                // Call your delete function here, passing the itemId
                deleteItem(itemId);
                // Hide the modal
                var modal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal'));
                modal.hide();
            });

            function deleteItem(id) {

                // Create a form and submit it using fetch to the current URL
                fetch('/setad/php/inspections/delete.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            'item_id': id // Send item_id as a POST parameter
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message); // Show success message
                            // Redirect to the inspections list page
                            window.location.href = window.location.href;
                        } else {
                            alert(data.message); // Show error message
                        }
                    })
                    .catch(error => console.error('error: deleteItem(id): ', error));

            }
        </script>




        <div class="button-container mt-2">

            <button id="firstButton" class="button" onclick="firstPage()">اول</button>
            <button id="prevButton" class="button" onclick="prevPage()">قبل</button>
            <div id="pageNumberDiv" style="display: inline;">صفحه : ؟</div>
            <button id="nextButton" class="button" onclick="nextPage()">بعد</button>
            <button id="lastButton" class="button" onclick="lastPage()">آخر</button>

            <script>
                var totalPages = <?php echo $totalPages; ?>;
                var pageNumber = <?php echo $pageNumber; ?>;

                let firstButton = document.getElementById("firstButton");
                let prevButton = document.getElementById("prevButton");
                let nextButton = document.getElementById("nextButton");
                let lastButton = document.getElementById("lastButton");

                nextButton.disabled = false;
                lastButton.disabled = false;
                prevButton.disabled = false;
                firstButton.disabled = false;

                if (pageNumber >= totalPages) {
                    nextButton.disabled = true;
                    lastButton.disabled = true;
                }

                if (pageNumber === 1) {
                    prevButton.disabled = true;
                    firstButton.disabled = true;
                }

                let pageNumberDiv = document.getElementById('pageNumberDiv');
                pageNumberDiv.innerText = 'صفحه: ' + pageNumber;

                function nextPage() {
                    let jsPageNumber = <?php echo $pageNumber; ?>;
                    let nextPageNumber = jsPageNumber + 1;
                    let newURL = window.location.origin + window.location.pathname + '?link=list&page=' + nextPageNumber;
                    window.location.href = newURL;
                }

                function prevPage() {
                    let jsPageNumber = <?php echo $pageNumber; ?>;
                    let nextPageNumber = jsPageNumber - 1;
                    let newURL = window.location.origin + window.location.pathname + '?link=list&page=' + nextPageNumber;
                    window.location.href = newURL;
                }

                function firstPage() {
                    let newURL = window.location.origin + window.location.pathname + '?link=list&page=1';
                    window.location.href = newURL;
                }

                function lastPage() {
                    let jsPageNumber = <?php echo $totalPages; ?>;
                    let newURL = window.location.origin + window.location.pathname + '?link=list&page=' + jsPageNumber;
                    window.location.href = newURL;
                }
            </script>

        </div>

    </div>

    <div class="col-2">
    </div>

    <div class="row"></div>
    <div class="row"></div>