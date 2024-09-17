<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فرم انتخاب استان و تاریخ</title>
    <style>
        .form-container {

            background-color: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;

        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
        }

        select,
        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        #date {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }
    </style>







    <!-- Province Dropdown -->

    <script src="lib/provincedropdown/provincedropdown.js"></script>
    <!-- <script>alert('test');</script> -->

    <link rel="stylesheet" href="lib/provincedropdown/provincedropdown.css" />





    <!-- Jalali Calendar -->

    <script src="lib/jalalidatepicker/jalalidatepicker.min.js"></script>

    <link rel="stylesheet" href="lib/jalalidatepicker/jalalidatepicker.min.css" />

    <style>
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





<!-- P H P -->

<?php

// Database connection and page logic
include_once('php/db/config.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Create connection
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $conn->set_charset("utf8");

    // Handle form submission for new entry
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $state_code = $_POST['state_code'];
        $date_ = $_POST['date'];
        $status_code = 1; // programmaticallt and statically set to 1: in-working (note: '2: done')

        // Insert the data into the inspections table
        $stmt = $conn->prepare("INSERT INTO setad.inspections (state_code, date_, status_code, desc) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iss", $state_code, $date_, $status_code);
        $stmt->execute();
        $stmt->close();

        echo "<p>اطلاعات با موفقیت ذخیره شد.</p>";
    }

    $conn->close();
} catch (mysqli_sql_exception $e) {
    echo "error: " . $e->getMessage();
}

?>





<!-- H T M L -->

<div class="row">

    <div class="col-4">
    </div>

    <div class="col-4">

        <div class="form-container">

            <h2>بازرسی جدید</h2>

            <form id="inspectionForm" action="http://localhost/setad/index.php?link=addnew" method="post">

                <label for="province">انتخاب استان:</label>
                <select class="province-dropdown" id="province" name="province"></select>

                <label for="date">انتخاب تاریخ:</label>
                <input data-jdp id="date" name="date" placeholder="تاریخ بازرسی" />
                <script>
                    jalaliDatepicker.startWatch({});
                </script>

                <!-- با دو روش متغیر های پنهان به فرم اضافه می کنیم -->
                <!-- یکی همین هایدن اینپوت ها -->
                <!-- Hidden inputs for extra data -->
                <!-- <input type="hidden" name="extra_data1" id="extra_data1" value="value1"> -->
                <!-- <input type="hidden" name="extra_data2" id="extra_data2" value="value2"> -->
                <!-- یکی هم با جاوا اسکریپت که پایین تر استفاده کردم -->

                <button type="submit">ارسال</button>

            </form>

            <script>
                document.getElementById('inspectionForm').addEventListener('submit', function(e) {

                    let provinceElement = document.getElementById('province');
                    let selectedText = provinceElement.options[provinceElement.selectedIndex].text;
                    let splittedText = selectedText.split('|').map(s => s.trim());
                    let state_code = splittedText[0];
                    let state_name = splittedText[1];

                    // Create a new hidden input field for state_code
                    var extraInput = document.createElement('input');
                    extraInput.type = 'hidden';
                    extraInput.name = 'state_code'; // Name for the POST request
                    extraInput.value = state_code; // Value you want to submit
                    // Append the hidden input to the form
                    this.appendChild(extraInput);

                    // Create a new hidden input field for state_code
                    var extraInput = document.createElement('input');
                    extraInput.type = 'hidden';
                    extraInput.name = 'state_name'; // Name for the POST request
                    extraInput.value = state_name; // Value you want to submit
                    // Append the hidden input to the form
                    this.appendChild(extraInput);

                });
            </script>

        </div>

    </div>

    <div class="col-4">
    </div>

</div>