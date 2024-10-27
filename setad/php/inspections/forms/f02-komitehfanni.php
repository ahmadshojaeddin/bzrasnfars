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
        $stmt = $conn->prepare("UPDATE setad.inspections SET f01_rotbeh_json=? WHERE id=?;");
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
    $stmt = $conn->prepare("SELECT f01_rotbeh_json, st.name_ as state_name, date_, insst.desc_ as status_desc 
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

            fetch('/setad/php/inspections/forms/f01-rotbeh.php', {

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

            <div class="section-title">2) عملکرد کمیته فنی</div>

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


                <div class="form-group">
                    <label>1- آیا جلسات کمیته فنی (کمیته رسیدگی به تخلفات فراهم‌کنندگان خدمات سلامت و بیمه‌شدگان) مطابق
                        دستورالعمل ابلاغی به صورت مستمر برگزار می‌گردد؟</label>
                    <div>
                        <label>
                            <input type="radio" name="question1" value="yes">
                            بلي
                        </label>
                        <label>
                            <input type="radio" name="question1" value="no">
                            خير
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>2- آیا جلسات کمیته فنی با حضور اعضای ثابت کمیته برگزار می‌گردد؟</label>
                    <div>
                        <label>
                            <input type="radio" name="question2" value="yes">
                            بلي
                        </label>
                        <label>
                            <input type="radio" name="question2" value="no">
                            خير
                        </label>
                    </div>
                    <textarea name="question2_details" class="form-control" placeholder="توضیحات"></textarea>
                </div>

                <div class="form-group">
                    <label>3- آیا اقدامات نظارتی مطابق جدول‌های (1) و (2) موارد مغایر با ضوابط و مقررات بیمه‌های پایه
                        دستورالعمل ابلاغی انجام می‌گردد؟</label>
                    <div>
                        <label>
                            <input type="radio" name="question3" value="yes">
                            بلي
                        </label>
                        <label>
                            <input type="radio" name="question3" value="no">
                            خير
                        </label>
                    </div>
                    <textarea name="question3_details" class="form-control" placeholder="توضیحات"></textarea>
                </div>

                <div class="form-group">
                    <label>4- آیا تصمیمات کمیته فنی اجرا می‌گردد؟</label>
                    <div>
                        <label>
                            <input type="radio" name="question4" value="yes">
                            بلي
                        </label>
                        <label>
                            <input type="radio" name="question4" value="no">
                            خير
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>5- آیا تصمیمات کمیته فنی مطابق ضوابط به مراکز یا بیمه‌شدگان اطلاع‌رسانی می‌گردد؟</label>
                    <div>
                        <label>
                            <input type="radio" name="question5" value="yes">
                            بلي
                        </label>
                        <label>
                            <input type="radio" name="question5" value="no">
                            خير
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>6- آیا سوابق تصمیمات اتخاذ شده در مورد مراکز طرف قرارداد در سیستم جامع اسناد پزشکی (TMDS) ثبت
                        می‌گردد؟</label>
                    <div>
                        <label>
                            <input type="radio" name="question6" value="yes">
                            بلي
                        </label>
                        <label>
                            <input type="radio" name="question6" value="no">
                            خير
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>7- آیا برخورد‌های نظارتی سوء‌استفاده از کد ملی بیمه‌شدگان اجرا می‌گردد؟</label>
                    <div>
                        <label>
                            <input type="radio" name="question7" value="yes">
                            بلي
                        </label>
                        <label>
                            <input type="radio" name="question7" value="no">
                            خير
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>سایر موارد ارزیابی عملکرد کمیته فنی:</label>
                    <textarea name="question7_details" class="form-control" placeholder="توضیحات"></textarea>
                    <!-- <small class="form-text text-muted">
                        <br>
                        1) در مواردی در صورتجلسات کمیته فنی در ستون تصمیم متخذه و دلایل مربوطه جهت خلاصه نویسی تصمیم سطر
                        بالا در سطر بعدی بصورت ایضا ذکر شده که در مواردی ممکن است قسمتی از تصمیمات سطر بالا در سطر بعدی
                        تصمیم گیری نشود ولی با این علامت باعث ایجاد تصمیم یکسان جهت دو مرکز شده ولی اجرایی نشده است.
                        بطور مثال: صورتجلسه کمیته فنی بهمن ماه 1401 در خصوص تصویربرداری ایرانیان 2113 که کسر خسارت در
                        سیستم مشاهده نشد.
                        <br>
                        2) در خصوص اخذ جرایم تصویب شده در کمیته فنی در موارد بسیاری که با حضور رییس محترم اداره نظارت و
                        بازرسی بررسی گردید جرایم در بخش کسورات فنی ثبت شده و مبالغ جرایم در ستون جرایم کارت عملکرد ثبت
                        نشده که قابل صحت سنجی و پیگیری نمی‌باشد. دکتر محمد علی اسماعیل زاده 66566، آزمایشگاه ابوریحان
                        1022-و....
                        <br>
                        3) در خصوص اثر بخشی و بازدارندگی تصمیمات کمیته فنی در مواردی مشاهده می شود در طی ماههای مختلف
                        مرکز به کمیته فنی ارجاع شده ولی عملکرد مغایر با ضوابط و مقررات همچنان ادامه دارد و در زمانهای
                        مختلف وقت همکاران رسیدگی و نظارت و بازرسی به کرات صرف بررسی عملکرد مرکز مذکور شده و در ارجاع به
                        کمیته فنی نیاز به تصمیمات بازدارنده و اثر بخش می باشد.
                        بطور مثال: داروخانه دکتر اسعدی با کد 559، داروخانه دکتر منوچهر1352
                        <br>
                        4) در مواردی اجرای تصمیم اتخاذ شده در کمیته فنی در سیستم مشاهده نشد. مانند: کمیته فنی بهمن ماه
                        1401 درمانگاه غدیر کد351 ، کمیته فنی آبان ماه 1401 درمانگاه 552 حرعاملی
                        <br>
                        5) در مواردی اجرای تصمیمات کمیته فنی پیگیری نشده است.
                        مانند: دکتر لاله کوهستانی 90368 کمیته فنی شهریور ماه 1402 میزان خسارت 6852160 ریال باید از پزشک
                        اخذ گردد، ولی مبلغ 1.900.000 ریال از مرکز اخذ شده است و مرکز لغو قرارداد شده است. باقی مبلغ فعلا
                        اخذ نشده است.
                        <br>
                        6) پیشنهاد می‌گردد در خصوص نامه دعوت حضوری مرکز، مراتب در اقدامات مکاتبه جهت پیگیری و اجرای
                        تصمیمات کمیته فنی ثبت گردد.
                    </small> -->
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