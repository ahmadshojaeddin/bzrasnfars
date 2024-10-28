<?php

// $shouldRedirectToInspectionsList = false;

// Handle form submission for new entry
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {

        // Database connection and page logic
        // include_once '../php/db/config.php';
        include_once '../../db/config.php';
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        // Create connection
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        $conn->set_charset("utf8");

        // $insp_id = isset($_GET['insp_id']) ? (int) $_GET['insp_id'] : 0;
        $insp_id = $_POST['insp_id'];
        $jsonString = $_POST['jsonString'];

        // update jsonString in the inspections table
        $stmt = $conn->prepare("UPDATE setad.inspections SET f03_elmitakhassosi_json=? WHERE id=?;");
        $stmt->bind_param("si", $jsonString, $insp_id);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        // echo "<p>اطلاعات با موفقیت ذخیره شد.</p>";
        // $shouldRedirectToInspectionsList = true;

        // Redirect or output a success message
        echo json_encode(['success' => true, 'message' => 'فرم ثبت شد']);
        exit();

    } catch (mysqli_sql_exception $e) {

        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit();

    }

}

?>

<?php

try {

    // Database connection and page logic
    // include_once '../php/db/config.php';
    include_once 'php/db/config.php';
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    // Create connection
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $conn->set_charset("utf8");

    $insp_id = isset($_GET['insp_id']) ? (int) $_GET['insp_id'] : 0;
    $jsonString = '{}';
    $provinceName = "(استان)";
    $inspDate = "1111/11/11";
    $inspStatus = "(وضعیت)";


    // update jsonString in the inspections table
    $stmt = $conn->prepare("SELECT f03_elmitakhassosi_json, st.name_ as state_name, date_, insst.desc_ as status_desc 
    FROM setad.inspections AS ins
    LEFT OUTER JOIN setad.states AS st ON ins.state_code = st.id
    LEFT OUTER JOIN setad.inspection_status AS insst ON ins.status_code = insst.id
    WHERE ins.id=?;");

    $stmt->bind_param("i", $insp_id);
    $stmt->execute();


    // Bind the result to $jsonString
    $stmt->bind_result($jsonString, $provinceName, $inspDate, $inspStatus);

    // Fetch the result into the $jsonString variable
    if (!$stmt->fetch()) {
        $jsonString = "error: No result found for id: " . $insp_id;
    }


    $stmt->close();
    $conn->close();


} catch (mysqli_sql_exception $e) {

    echo "<script>alert(" . $e->getMessage() . ");</script>";
    exit();

}

?>




<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عملکرد اداره نظارت و بازرسی</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            direction: rtl;
            text-align: right;
        }

        .form-check-label {
            margin-right: 1.5em;
        }

        /* from gpt: */

        .section-title {
            background-color: #f5a97d;
            padding: 10px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .form-section {
            margin-bottom: 40px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: inline-block;
            margin-bottom: 5px;
        }

        .form-group input[type="radio"],
        .form-group input[type="checkbox"] {
            margin-left: 10px;
        }

        .textarea {
            width: 100%;
            height: 80px;
            margin-top: 10px;
        }

        input[type="text"] {
            border-width: 1px;
        }
        
    </style>


    <script type="text/javascript">

        function save(showAlert = true) {

            // Prevent the form from submitting
            event.preventDefault();

            let jsonString = packFormDataToJson();
            submit_json(jsonString, showAlert);

        }

        function return_() {

            // Prevent the form from submitting
            event.preventDefault();

            let url = "<?php echo "/setad/index.php?link=edit&insp_id=" . $insp_id; ?>";
            window.location.href = url;

        }

        function saveAndReturn() {

            // Prevent the form from submitting
            event.preventDefault();
            save(false);
            return_();

        }


        // جمع آوری داده ها از فرم و تبدیل به جیسون

        function packFormDataToJson() {

            const form = document.getElementById('myForm');
            const formData = new FormData(form);
            const formObject = {};

            // Loop through all form data and build an object
            formData.forEach((value, key) => {
                if (!formObject[key]) {
                    formObject[key] = value;
                } else {
                    // Handle multiple input fields with the same name (e.g., radio buttons)
                    if (!Array.isArray(formObject[key])) {
                        formObject[key] = [formObject[key]];
                    }
                    formObject[key].push(value);
                }
            });

            // Convert the object to a JSON string
            const jsonString = JSON.stringify(formObject);
            return jsonString;

        }


        function submit_json(jsonString, showAlert) {

            let insp_id = <?php echo isset($_GET['insp_id']) ? (int) $_GET['insp_id'] : 0; ?>;

            fetch('/setad/php/inspections/forms/f03-elmitakhassosi.php', {

                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'insp_id': insp_id,
                    'jsonString': jsonString // Send jsonString as a POST parameter
                })

            }).then(response => response.text()) // Get the raw response text

                .then(text => {
                    try {
                        // Try parsing the text as JSON
                        const data = JSON.parse(text);
                        if (data.success) {
                            if (showAlert)
                                alert(data.message); // Show success message
                            window.location.href = window.location.href; // Redirect to the inspections list page
                        } else {
                            if (showAlert)
                                alert(data.message); // Show error message
                        }
                    } catch (error) {
                        // Log the raw text for debugging
                        console.error('Response is not valid JSON:', text);
                        if (showAlert)
                            alert('Error: ' + text); // Show the raw response to the user
                    }

                })
                .catch(error => console.error('Fetch error:', error));

        }




        // توزیع داده ها از جیسون به المنت های روی فرم

        // Function to populate the form with values from JSON string
        function populateForm(jsonString) {

            // Parse the JSON string
            const formData = JSON.parse(jsonString);

            // Loop through each key in the object
            for (let key in formData) {
                // Find the input element using the key as the name attribute
                let inputElement = document.querySelector(`[name="${key}"]`);

                if (inputElement) {
                    // Check if it's a radio button or checkbox
                    if (inputElement.type === "radio" || inputElement.type === "checkbox") {
                        let value = formData[key];
                        let radioElement = document.querySelector(`[name="${key}"][value="${value}"]`);
                        if (radioElement) {
                            radioElement.checked = true;
                        }
                    }
                    // Check if it's a textarea
                    else if (inputElement.tagName === "TEXTAREA") {
                        inputElement.value = formData[key];
                    }
                    // Handle regular input types (text, number, etc.)
                    else {
                        inputElement.value = formData[key];
                    }
                }
            }
        }

    </script>



</head>

<div class="row">

    <div class="col-2"> </div>
    <div class="col-8">
        <div class="container mt-5">

            <div class="section-title">3) ارزیابی عملکرد شورای علمی- تخصصی</div>

            <script>
                let title = "<?php echo "کد: " . $insp_id . " , استان: " . $provinceName . " , تاریخ: " . $inspDate . " , وضعیت: " . $inspStatus; ?>";
                document.write(`<h6 class="mb-4">( ${title} )</h4>`);
            </script>

            <hr style="border: 1px solid black; width: 100%; margin: 20px auto;">
            <br>


            <!-- <hr style="border: 1px solid black; width: 45%;"> -->
            <br>
            <br>

            <form id="myForm" action="submit_form.php" method="post">

                <!-- Question 1 -->
                <div>
                    <label>آیا انتخاب اعضای دائم و موقت شورای علمی تخصصی مطابق شیوه نامه ابلاغی می‌باشد؟</label>
                    <div>
                        <label><input type="radio" name="election" value="yes"> بلي</label>
                        <label><input type="radio" name="election" value="no"> خير</label>
                    </div>
                </div>

                <!-- Question 2 -->
                <div>
                    <label>زمان تشکیل جلسات شورا:</label>
                    <div>
                        <label><input type="radio" name="session_time" value="office_hours"> در ساعات اداری</label>
                        <label><input type="radio" name="session_time" value="off_hours"> در خارج از ساعات اداری</label>
                    </div>
                </div>

                <!-- Day and Time -->
                <div>
                    <label for="meeting_day">روزهای برگزاری:</label>
                    <input type="text" id="meeting_day" name="meeting_day" placeholder="روزهای برگزاری">
                </div>
                <div>
                    <label for="meeting_time">ساعت برگزاری:</label>
                    <input type="text" id="meeting_time" name="meeting_time" placeholder="ساعت برگزاری">
                </div>

                <!-- Question 3 -->
                <div>
                    <label>آیا زمان شروع و پایان جلسات شورا ثبت می‌گردد؟</label>
                    <div>
                        <label><input type="radio" name="time_recording" value="yes"> بلي</label>
                        <label><input type="radio" name="time_recording" value="no"> خير</label>
                    </div>
                </div>

                <!-- Question 4 -->
                <div>
                    <label>ارزیابی شاخص‌های شیوه‌نامه ابلاغی به صورت سه ماهه:</label>
                    <div>
                        <?php echo "استان " . $provinceName . " جزء استان های گرو" . "<input type=\"text\" name=\"state_group\" placeholder=\"گروه\">" . " می باشد."; ?>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <div>
                        <label>توضیحات: عملکرد استان در</label>
                        <input type="text" name="month" placeholder="ماه" style="width: 80px;">
                        <label>سال</label>
                        <input type="text" name="year" placeholder="سال" style="width: 80px;">
                        <label>مورد ارزیابی قرار گرفته است</label>
                    </div>
                </div>
                <br>
                <br>



                <!-- Table -->

                <table border="1" style="border-collapse: collapse; width: 100%;">
                    <tr style="background-color: #6b8e23;">
                        <th>عنوان شاخص</th>
                        <th>شیوه نامه (گروه1)</th>
                        <th>عملکرد 3 ماهه استان</th>
                        <th>توضیحات</th>
                    </tr>
                    <tr>
                        <td>میزان ساعت در ماه</td>
                        <td>20</td>
                        <td><input type="text" name="performance_hours" value=""></td>
                        <td>
                            آیا عملکرد استان با شیوه‌نامه ابلاغی شماره 928 مورخ 1402/08/22 مطابقت دارد؟
                            <br>
                            <label><input type="radio" name="compliance" value="بلی"> بلی</label>
                            <label><input type="radio" name="compliance" value="خیر"> خیر</label>
                        </td>
                    </tr>
                    <tr>
                        <td>تعداد جلسات در ماه</td>
                        <td>8</td>
                        <td><input type="text" name="meeting_count" value=""></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>تعداد پزشک مورد مطالعه در ساعت</td>
                        <td>4</td>
                        <td><input type="text" name="doctor_count" value=""></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>تعداد پرونده مورد مطالعه در ساعت</td>
                        <td>8</td>
                        <td><input type="text" name="file_count" value=""></td>
                        <td></td>
                    </tr>
                    <tr style="background-color: #6b8e23; text-align: center;">
                        <td colspan="4">بازخورد جلسات شورا</td>
                    </tr>
                    <tr>
                        <td>تشکر و قدردانی از عملکرد پزشک</td>
                        <td><input type="text" name="feedback_thanks_input"></td>
                        <td colspan="2"><textarea name="feedback_thanks"></textarea></td>
                    </tr>
                    <tr>
                        <td>ارسال عملکرد و مقایسه با میانگین عملکرد پزشکان همتراز</td>
                        <td><input type="text" name="feedback_comparison_input"></td>
                        <td colspan="2"><textarea name="feedback_comparison"></textarea></td>
                    </tr>
                    <tr>
                        <td>موارد ارجاع به کمیته فنی</td>
                        <td><input type="text" name="feedback_referrals_input"></td>
                        <td colspan="2"><textarea name="feedback_referrals"></textarea></td>
                    </tr>
                    <tr style="background-color: #6b8e23; text-align: center;">
                        <td colspan="4">میزان پیشگیری از هزینه‌ها (به ریال)</td>
                    </tr>
                    <tr>
                        <td>موارد پرونده خسارت</td>
                        <td><input type="text" name="damage_cases"></td>
                        <td><input type="text" name="damage_prevention"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>موارد واحد بیمارستانی</td>
                        <td><input type="text" name="hospital_unit_cases"></td>
                        <td><input type="text" name="hospital_unit_prevention"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>موارد پرونده شیمی درمانی</td>
                        <td><input type="text" name="chemotherapy_cases"></td>
                        <td><input type="text" name="chemotherapy_prevention"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>موارد پرونده هورمون رشد</td>
                        <td><input type="text" name="growth_hormone_cases"></td>
                        <td><input type="text" name="growth_hormone_prevention"></td>
                        <td></td>
                    </tr>
                    <tr style="background-color: #ff6347;">
                        <td>جمع</td>
                        <td colspan="2">
                            <input type="text" name="total_sum" value="" readonly>
                        </td>
                        <td></td>
                    </tr>
                </table>

                <!-- End of Table -->
                <br>
                <br>


                <!-- Question 5 -->
                <div>
                    <label>5- آیا فرایند بررسی عملکرد پزشکان مطابق فرم‌های شماره (1) و (2) شیوه‌نامه انجام
                        می‌گردد؟</label>
                    <div>
                        <label><input type="radio" name="question5" value="بلی"> بلي</label>
                        <label><input type="radio" name="question5" value="خیر"> خير</label>
                    </div>
                </div>

                <!-- Question 6 -->
                <div>
                    <label>6- آیا پزشکانی که بیشترین هزینه‌سازی را دارند در اولویت بررسی و بازنگری مجدد عملکرد قرار
                        می‌گیرند؟</label>
                    <div>
                        <label><input type="radio" name="question6" value="بلی"> بلي</label>
                        <label><input type="radio" name="question6" value="خیر"> خير</label>
                    </div>
                </div>

                <!-- Question 7 -->
                <div>
                    <label>7- آیا فرایند بررسی پرونده بیماران مطابق فرم‌ شماره (3) شیوه‌نامه انجام می‌گردد؟</label>
                    <div>
                        <label><input type="radio" name="question7" value="بلی"> بلي</label>
                        <label><input type="radio" name="question7" value="خیر"> خير</label>
                    </div>
                </div>

                <!-- Question 8 -->
                <div>
                    <label>8- آیا صورتجلسه شورای علمی تخصصی مطابق فرم شماره (4) شیوه نامه ابلاغی ثبت می‌گردد؟</label>
                    <div>
                        <label><input type="radio" name="question8" value="بلی"> بلي</label>
                        <label><input type="radio" name="question8" value="خیر"> خير</label>
                    </div>
                </div>

                <!-- Question 10 -->
                <div>
                    <label>10- آیا گزارش عملکرد شورای علمی تخصص در فرم ماهیانه و سه ماهه ثبت می‌گردد؟</label>
                    <div>
                        <label><input type="radio" name="question10" value="بلی"> بلي</label>
                        <label><input type="radio" name="question10" value="خیر"> خير</label>
                    </div>
                </div>

                <!-- Question 11 -->
                <div>
                    <label>11- آیا مصوبات شورا مطابق شیوه نامه به واحدهای مربوطه ابلاغ می‌گردد؟</label>
                    <div>
                        <label><input type="radio" name="question11" value="بلی"> بلي</label>
                        <label><input type="radio" name="question11" value="خیر"> خير</label>
                    </div>
                </div>

                <!-- Question 12 -->
                <div>
                    <label>12- آیا مستندات برگزاری جلسات شورا مطابق شیوه نامه ثبت و بایگانی می‌گردد؟</label>
                    <div>
                        <label><input type="radio" name="question12" value="بلی"> بلي</label>
                        <label><input type="radio" name="question12" value="خیر"> خير</label>
                    </div>
                </div>

                <!-- Other Comments -->
                <div>
                    <label>سایر موارد ارزیابی عملکرد شورای علمی تخصصی:</label>
                    <textarea name="other_comments" class="form-control"></textarea>
                </div>



                <br />
                <br />
                <button type="submit" class="btn btn-primary" onclick="save()">ثبت</button>
                <button type="submit" class="btn btn-primary" onclick="return_()">بازگشت</button>
                <button type="submit" class="btn btn-primary" onclick="saveAndReturn()">ثبت و بازگشت</button>

            </form>

        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </div> <!-- end of col-8 -->
    <div class="col-2"> </div>
</div> <!-- end of row -->


<script>

    let jsonString = <?php echo json_encode($jsonString); ?>;
    populateForm(jsonString);

</script>