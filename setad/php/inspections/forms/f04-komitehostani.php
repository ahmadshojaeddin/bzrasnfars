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
        $stmt = $conn->prepare("UPDATE setad.inspections SET f04_komitehostani_json=? WHERE id=?;");
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
    $stmt = $conn->prepare("SELECT f04_komitehostani_json, st.name_ as state_name, date_, insst.desc_ as status_desc 
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

            fetch('/setad/php/inspections/forms/f04-komitehostani.php', {

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

            <div class="section-title">4) ارزیابی عملکرد شوراها و کمیته‌های مشترک استانی با سایر سازمان‌ها</div>

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

                <!-- Section 1: شورای استانی سازمان‌های بیمه‌گر -->
                <fieldset>
                    <legend>1- شورای استانی سازمان‌های بیمه‌گر بر اساس تفاهم‌نامه تشکیل شورای هماهنگی سازمان‌های بیمه‌گر
                        (شماره 103241/95 مورخ 29/03/1395)</legend>

                    <!-- Question 1 -->
                    <label>1) آیا استان داراي كميته هماهنگي سازمان‌های بیمه‌گر مي‌باشد؟</label>
                    <label><input type="radio" name="coordination-committee" value="yes" required> بلي</label>
                    <label><input type="radio" name="coordination-committee" value="no" required> خير</label>
                    <br>

                    <!-- Question 2 -->
                    <label>2) آیا جلسات به صورت مستمر برگزار می‌گردد؟</label>
                    <label><input type="radio" name="regular-meetings" value="yes" required> بلي</label>
                    <label><input type="radio" name="regular-meetings" value="no" required> خير</label>
                    <br>
                    <label>توضيحات:</label>
                    <textarea name="regular-meetings-desc" class="form-control" rows="2"></textarea>
                    <br>

                    <!-- Question 3 -->
                    <label>3) آیا جلسات کمیته‌های اصلی (بستری، سرپایی، نظارت و دارو، تجهیزات و بیماران خاص) به صورت
                        مستمر برگزار مي‌گردد؟</label>
                    <label><input type="radio" name="main-committees" value="yes" required> بلي</label>
                    <label><input type="radio" name="main-committees" value="no" required> خير</label>
                    <br>

                    <!-- Question 4 -->
                    <label>4) آیا بازرسی‌های مشترک با سازمان‌های بیمه‌گر انجام می‌گردد؟</label>
                    <label><input type="radio" name="joint-inspections" value="yes" required> بلي</label>
                    <label><input type="radio" name="joint-inspections" value="no" required> خير</label>
                </fieldset>

                <!-- Section 2: ستاد استانی نظارت بر تعرفه‌ها -->
                <fieldset>
                    <legend>2- ستاد استانی نظارت بر تعرفه‌ها بر اساس دستورالعمل اجرایی بسته نظارتی اجرای کتاب ارزش نسبی
                        خدمات سلامت (مصوبه شماره 74450/ت50982هـ مورخ 01/07/1393 هیات وزیران و اصلاحیه شماره 92204/50982
                        مورخ 15/08/1393)</legend>

                    <!-- Question 1 -->
                    <label>1) آیا استان داراي ستاد نظارت بر تعرفه‌ها مي‌باشد؟</label>
                    <label><input type="radio" name="tariff-oversight" value="yes" required> بلي</label>
                    <label><input type="radio" name="tariff-oversight" value="no" required> خير</label>
                    <br>

                    <!-- Question 2 -->
                    <label>2) آیا جلسات به صورت مستمر برگزار مي‌گردد؟</label>
                    <label><input type="radio" name="tariff-regular-meetings" value="yes" required> بلي</label>
                    <label><input type="radio" name="tariff-regular-meetings" value="no" required> خير</label>
                    <br>
                    <label>توضيحات:</label>
                    <textarea name="tariff-meetings-desc" class="form-control" rows="2"></textarea>
                    <br>

                    <!-- Question 3 -->
                    <label>3) آیا سوابق تصمیمات نظارتی ستاد نظارت بر تعرفه‌ها به مدیریت درمان ارسال شده است؟</label>
                    <label><input type="radio" name="tariff-decisions" value="yes" required> بلي</label>
                    <label><input type="radio" name="tariff-decisions" value="no" required> خير</label>
                    <br>
                    <label>توضيحات:</label>
                    <textarea name="tariff-decisions-desc" class="form-control" rows="2"></textarea>
                </fieldset>

                <!-- Section 3: کمیته اجرایی نسخه الکترونیک استان -->
                <fieldset>
                    <legend>3- کمیته اجرایی نسخه الکترونیک استان (ضوابط اجرایی طرح نسخه الکترونیک - مصوبه جلسه 88 شورای
                        عالی بیمه - شماره 600/100 مورخ 29/04/1401)</legend>

                    <!-- Question 1 -->
                    <label>آیا کمیته اجرایی نسخه الکترونیک استان به صورت ماهیانه برگزار می‌گردد؟</label>
                    <label><input type="radio" name="monthly-meetings" value="yes" required> بلي</label>
                    <label><input type="radio" name="monthly-meetings" value="no" required> خير</label>
                    <br>
                    <label>توضيحات:</label>
                    <textarea name="monthly-meetings-desc" class="form-control" rows="2"></textarea>
                </fieldset>

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