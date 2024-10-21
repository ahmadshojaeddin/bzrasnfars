<?php

// $shouldRedirectToInspectionsList = false;

// Handle form submission for new entry
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {

        // Database connection and page logic
        include_once '../db/config.php';
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        // Create connection
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        $conn->set_charset("utf8");

        $insp_id = $_POST['insp_id'];
        $date_ = $_POST['date_'];
        $state_code = $_POST['state_code'];
        $status_code = $_POST['status_code'];

        // update jsonString in the inspections table
        $stmt = $conn->prepare("UPDATE setad.inspections SET date_=?, state_code=?, status_code=? WHERE id=?;");
        $stmt->bind_param("siii", $date_, $state_code, $status_code, $insp_id);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        // Redirect or output a success message
        echo json_encode(['success' => true, 'message' => 'تغییرات اعمال شد']);
        exit();

    } catch (mysqli_sql_exception $e) {

        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit();

    }

}

?>


<head>

    <style>
        /* CSS styles */
        .form-container {
            background-color: transparent;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        label {
            margin-right: 10px;
            font-weight: bold;
        }

        select,
        input[type="text"] {
            padding: 5px;
            margin-right: 20px;
            border-radius: 0;
            /*4px;*/
            border: 1px solid #ccc;
        }

        button {
            padding: 8px 15px;
            background-color: #5cb85c;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
            color: white;
            flex-grow: 0;
        }

        .table-container {
            margin-top: 20px;
            position: relative;
            /* برای نگه داشتن دکمه در محدوده جدول */
            padding-bottom: 50px;
            /* ایجاد فضای کافی زیر جدول برای دکمه */
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        thead {
            background-color: #ddd;
        }

        a {
            color: blue;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .back-button {

            display: inline-block;
            /* از حالت block به inline-block تغییر دهید */
            margin-top: 20px;
            background-color: #5cb85c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            text-align: center;
            width: 100px;
            clear: both;
            /* اطمینان از اینکه دکمه بعد از جدول قرار بگیرد */

        }
    </style>


    <!-- Province Dropdown -->
    <script src="lib/provincedropdown/provincedropdown.js"></script>
    <link rel="stylesheet" href="lib/provincedropdown/provincedropdown.css" />


    <!-- Jalali Calendar -->
    <script src="lib/jalalidatepicker/jalalidatepicker.min.js"></script>
    <link rel="stylesheet" href="lib/jalalidatepicker/jalalidatepicker.min.css" />


    <style>
        select,
        input[type="date"] {
            width: 15%;
            padding: 10px;
            /* margin-bottom: 15px; */
            border: 1px solid #ccc;
            /* border-radius: 5px; */
            font-size: 14px;
        }

        #date {
            width: 15%;
            padding: 10px;
            /* margin-bottom: 15px; */
            border: 1px solid #ccc;
            /* border-radius: 5px; */
            font-size: 14px;
        }

        .modal {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
            background: #FFF;
            box-shadow: 0 0 8px rgba(0, 0, 0, .3);
            transition: margin-top 0.3s ease, height 0.3s ease;
            transform: translateZ(0);
            box-sizing: border-box;
            z-index: 999;
            border-radius: 3px;
            max-width: 600px;
            display: block;
            height: 400px;
            overflow: scroll;
        }
    </style>

</head>

<div class="form-container">

    <script>

        function submit_edit() {

            // Prevent the form from submitting
            event.preventDefault();

            let insp_id = <?php echo isset($_GET['insp_id']) ? (int) $_GET['insp_id'] : 0; ?>;
            let date_ = document.getElementById('date').value;  // Set date
            let state_code = document.getElementById('province').value;  // Set state code (province)
            let status_code = document.getElementById('status').value;  // Set status code

            // alert(insp_id + ',' + date_ + ',' + state_code + ',' + status_code);

            fetch('/setad/php/inspections/edit.php', {

                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'insp_id': insp_id,
                    'date_': date_,
                    'state_code': state_code,
                    'status_code': status_code
                })

            }).then(response => response.text()) // Get the raw response text

                .then(text => {
                    try {
                        // Try parsing the text as JSON
                        const data = JSON.parse(text);
                        if (data.success) {
                            alert(data.message); // Show success message
                            window.location.href = window.location.href; // Redirect to the inspections list page
                        } else {
                            alert(data.message); // Show error message
                        }
                    } catch (error) {
                        // Log the raw text for debugging
                        console.error('Response is not valid JSON:', text);
                        alert('Error: ' + text); // Show the raw response to the user
                    }
                })
                .catch(error => console.error('Fetch error:', error));

        }

    </script>

    <form autocomplete="off">

        <div class="form-row">
            <!-- <label for="province">استان:</label>
            <select id="province" name="province">
                <option value="">انتخاب کنید</option>
                <option value="1">استان ۱</option>
                <option value="2">استان ۲</option>
            </select> -->
            <label for="province">انتخاب استان:</label>
            <select class="province-dropdown" id="province" name="province"></select>


            <label for="status">وضعیت:</label>
            <select id="status" name="status">
                <option value="1">در دست اقدام</option>
                <option value="2">تکمیل شده</option>
            </select>

            <!-- <label for="date">تاریخ:</label>
            <input type="text" id="date" name="date" style="border-radius: 0px !important;"> -->
            <label for="date">انتخاب تاریخ:</label>
            <input data-jdp id="date" name="date" placeholder="تاریخ بازرسی" />
            <script>
                jalaliDatepicker.startWatch({});
            </script>

            <button type="submit" style="border-radius: 0px !important;" onclick="submit_edit()">ثبت</button>

        </div>

    </form>


    <?php

    $insp_id = isset($_GET['insp_id']) ? (int) $_GET['insp_id'] : 0;
    $date_ = '';
    $state_code = 0;
    $status_code = 0;

    try {

        // Database connection and page logic
        // include_once '../php/db/config.php';
        include_once 'db/config.php';
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        // Create connection
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        $conn->set_charset("utf8");

        $stmt = $conn->prepare("SELECT date_, state_code, status_code from setad.inspections WHERE id=?;");
        $stmt->bind_param("i", $insp_id);
        $stmt->execute();

        // Bind the result to $jsonString
        $stmt->bind_result($date_, $state_code, $status_code);

        // Fetch the result into the $jsonString variable
        if (!$stmt->fetch()) {
            echo "error: No result found for id: " . $insp_id;
        } // else {
        //     echo 'result: ' . $insp_id . ' , ' . $date_ . ' , ' . $state_code . ' , ' . $status_code;
        // }
    
        $stmt->close();
        $conn->close();


    } catch (mysqli_sql_exception $e) {

        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit();

    }



    ?>


</div>




<div class="table-container">

    <table>
        <thead>
            <tr>
                <th>ردیف</th>
                <th>عنوان</th>
                <th>ویرایش</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>۱</td>
                <td>ارزیابی عملکرد اداره رتبه‌بندی فراهم‌کنندگان خدمات و خرید راهبردی</td>
                <?php
                echo "<td><a href=\"index.php?link=editform&form_id=1&insp_id=$insp_id\">ویرایش</a></td>"
                    ?>
            </tr>
            <tr>
                <td>۲</td>
                <td>ارزیابی عملکرد کمیته فنی</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
            <tr>
                <td>۳</td>
                <td>ارزیابی عملکرد شورای علمی تخصصی</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
            <tr>
                <td>۴</td>
                <td>ارزیابی عملکرد شوراها و کمیته‌های مشترک استانی با سایر سازمان‌ها</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
            <tr>
                <td>۵</td>
                <td>ارزیابی عملکرد اداره نظارت و بازرسی</td>
                <?php
                echo "<td><a href=\"index.php?link=editform&form_id=5&insp_id=$insp_id\">ویرایش</a></td>"
                    ?>
            </tr>
            <tr>
                <td>۶</td>
                <td>ارزیابی عملکرد واحد رسیدگی صورت‌حساب‌ها</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
            <tr>
                <td>۷</td>
                <td>ارزیابی عملکرد واحد رسیدگی صورت‌حساب‌های پزشکان</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
            <tr>
                <td>۸</td>
                <td>ارزیابی عملکرد واحد رسیدگی صورت‌حساب‌های دندانپزشکان و درمانگاه‌ها</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
            <tr>
                <td>۹</td>
                <td>ارزیابی عملکرد واحد رسیدگی صورت‌حساب‌های داروخانه‌ها</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
            <tr>
                <td>۱۰</td>
                <td>ارزیابی عملکرد واحد رسیدگی صورت‌حساب‌های مراکز جراحی محدود</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
            <tr>
                <td>۱۱</td>
                <td>ارزیابی عملکرد واحد رسیدگی صورت‌حساب‌های خسارت متفرقه</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
            <tr>
                <td>۱۲</td>
                <td>ارزیابی عملکرد اداره امور مالی و ممیزی</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
        </tbody>
    </table>


    <!-- دکمه بازگشت در پایین صفحه -->
    <script>
        function returnToInspectionsList() {
            window.location.href = "setad/index.php?link=list";
        }
    </script>
    <a href="index.php" class="back-button" onclick="returnToInspectionsList()">بازگشت</a>

</div>





<script>

    document.addEventListener('DOMContentLoaded', () => {

        let insp_id = <?php echo $insp_id; ?>;
        let date_ = "<?php echo $date_; ?>"; // Ensure it's treated as a string
        let state_code = <?php echo $state_code; ?>;
        // alert(typeof state_code);
        let status_code = <?php echo $status_code; ?>;

        // let provinces = '';
        // Array.from(document.getElementById('province').options).forEach((option, index) => {
        //     provinces += `Index ${index}: Value = ${option.value}, Text = ${option.text}` + '\n';
        // });
        // alert(provinces);

        // alert(insp_id + ' , ' + date_ + ' , ' + state_code + ' , ' + status_code);

        if (date_ !== '') {

            document.getElementById('date').value = date_;  // Set date
            document.getElementById('province').value = state_code;  // Set state code (province)
            document.getElementById('status').value = status_code;  // Set status code

        } else {

            alert('بازرسی ای با کدخطای برنامه:  ' + insp_id + ' در دیتابیس پیدا نشد');

        }

    });

</script>