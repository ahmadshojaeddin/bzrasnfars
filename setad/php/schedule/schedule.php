<?php
// #region PHP SERVER SIDE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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


        $year = $_POST['yearText']; // Get the input value
        $action = $_POST['action']; // Determine which button was clicked

        switch ($action) {
            case 'create':
                echo "Adding Year: " . htmlspecialchars($year);
                // Add your logic to handle 'Add' action

                if ($year > 0) {
                    // Fetch all states
                    $statesQuery = "SELECT id FROM setad.states";
                    $statesResult = $conn->query($statesQuery);

                    if ($statesResult->num_rows > 0) {
                        while ($state = $statesResult->fetch_assoc()) {
                            $state_id = $state['id'];

                            // Check if a schedule already exists for this state and year
                            $checkQuery = "SELECT 1 FROM setad.schedules WHERE year_ = $year AND state_id = $state_id";
                            $checkResult = $conn->query($checkQuery);

                            if ($checkResult->num_rows === 0) {
                                // Insert a new schedule if it doesn't exist
                                $insertQuery = "INSERT INTO schedules (year_, state_id, status_id) VALUES ($year, $state_id, 1)";
                                $conn->query($insertQuery);
                            }
                        }
                        echo "Schedules added successfully for year $year.";
                    } else {
                        echo "No states found in the database.";
                    }
                } else {
                    echo "Invalid year provided.";
                }

                break;
            case 'update':
                echo "Updating Year: " . htmlspecialchars($year);
                // Add your logic to handle 'Update' action
                break;
            default:
                echo "Unknown action.";
                break;
        }



        $conn->close();
    } catch (mysqli_sql_exception $e) {

        echo "error: " . $e->getMessage();
    }
}

// #endregion
?>

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


        .text2 {
            all: unset;
            /* Resets all inherited and default browser styles */
            border: 1px solid black;
            /* Explicitly sets the border */
            border-radius: 1px;
            /* Applies your desired radius */
            padding: 5px;
            /* Add padding if necessary */
            box-sizing: border-box;
            /* Ensures consistent box model behavior */
        }

        .dropdown2 {
            /* all: unset; */
            /* Resets all inherited and default browser styles */
            border: 1px solid black;
            /* Explicitly sets the border */
            border-radius: 1px;
            /* Applies your desired radius */
            padding: 5px;
            /* Add padding if necessary */
            box-sizing: border-box;
            /* Ensures consistent box model behavior */
        }
    </style>

</head>



<div class="row">

    <div class="col-2">
    </div>

    <div class="col-8">

        <h1 style="padding: 20px;">برنامه زمان بندی</h1>

        <div class="button-container" style="border: 1px solid black !important; padding: 30px;">
            <form method="POST" action="">
                <label>سال</label>
                <input id="yearText" name="yearText" required class="text2" type="text" value="1404" style="border-radius: 1px !important; width: 50px;">
                <button class="button" type="submit" name="action" value="create">ایجاد برنامه</button>
                <select class="dropdown2" name="dropdown" id="dropdown" style="border-radius: 0; border: 1px solid black; margin-right: 100px;">
                    <option value="item1">انجام نشده</option>
                    <option value="item2">انجام شده</option>
                    <option value="item3">هردو</option>
                </select>
                <button class="button" type="submit" name="action" value="update">بروزرسانی</button>
            </form>
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


        <!-- <br /> -->


        <!-- جدول موارد بازرسی -->
        <table>

            <thead>
                <tr>
                    <th>کد برنامه</th>
                    <th>استان</th>
                    <th>برنامه ریزی</th>
                    <th>وضعیت</th>
                    <th>آخرین بازرسی<br />(سال)</th>
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
                    $sql = "
                            SELECT 
                                SQL_CALC_FOUND_ROWS 
                                sch.id AS sch_id, 
                                sch.year_ AS year_, 
                                sch.date_ AS date_, 
                                st.name_ AS state_name, 
                                insst.desc_ AS status_desc
                            FROM 
                                setad.schedules AS sch
                            LEFT OUTER JOIN 
                                setad.states AS st ON sch.state_id = st.id
                            LEFT OUTER JOIN 
                                setad.inspection_status AS insst ON sch.status_id = insst.id
                            WHERE 
                                sch.year_ = '{$year}'
                            ORDER BY 
                                sch.id ASC
                        ";
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

                            $sch_id = $row['sch_id'];
                            $date = $row['date_'];
                            $state = $row['state_name'];
                            $status = $row['status_desc'];

                            echo "
                                <tr>
                                    <td>$sch_id</td>
                                    <td>$state</td>
                                    <td>$date</td>
                                    <td>$status</td>
                                    <td>1</td>
                                    <td><a href=\"/setad/index.php?link=edit&insp_id=$sch_id\">ویرایش</a></td>
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

        <br/>
        <br/>
        <br/>


        <!-- script for delete operation and pass item id to delete modal -->
        <script>
            let itemId = 0; // Variable to store the item ID


            // Event listener to handle all modal showing
            // document.querySelectorAll('[data-bs-target="#confirmationModal"]').forEach(function(link) {
            //     link.addEventListener('click', function() {
            //         // Get the ID from the link's data attribute
            //         itemId = this.getAttribute('data-id');
            //         // alert('itemId: ' + itemId);
            //         // Update the modal message to include the item ID
            //         document.getElementById('confirmationMessage').innerHTML =
            //             `آیا برای حذف ردیف شماره ${itemId} مطمئن هستید؟`;
            //     });
            // });


            // Handle Yes button click
            // document.getElementById('yesButton').addEventListener('click', function() {
            //     // Call your delete function here, passing the itemId
            //     deleteItem(itemId);
            //     // Hide the modal
            //     var modal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal'));
            //     modal.hide();
            // });

            // function deleteItem(id) {

            //     // Create a form and submit it using fetch to the current URL
            //     fetch('/setad/php/inspections/delete.php', {
            //             method: 'POST',
            //             headers: {
            //                 'Content-Type': 'application/x-www-form-urlencoded',
            //             },
            //             body: new URLSearchParams({
            //                 'item_id': id // Send item_id as a POST parameter
            //             })
            //         })
            //         .then(response => response.json())
            //         .then(data => {
            //             if (data.success) {
            //                 alert(data.message); // Show success message
            //                 // Redirect to the inspections list page
            //                 window.location.href = window.location.href;
            //             } else {
            //                 alert(data.message); // Show error message
            //             }
            //         })
            //         .catch(error => console.error('error: deleteItem(id): ', error));

            // }
        </script>




        <!-- <div class="button-container mt-2">

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

        </div> -->

    </div>

    <div class="col-2">
    </div>

    <div class="row"></div>
    <div class="row"></div>