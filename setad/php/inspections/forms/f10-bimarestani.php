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
        // $stmt = $conn->prepare("UPDATE setad.inspections f05_bazrasi_json=? WHERE id=?;");
        $stmt = $conn->prepare("UPDATE setad.inspections SET f05_bazrasi_json=? WHERE id=?;");
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
    // $stmt = $conn->prepare("SELECT f05_bazrasi_json from setad.inspections WHERE id=?;");
    $stmt = $conn->prepare("SELECT f05_bazrasi_json, st.name_ as state_name, date_, insst.desc_ as status_desc 
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

            fetch('/setad/php/inspections/forms/f05-bazrasi.php', {

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

            <div class="section-title">10) عملکرد واحد رسیدگی صورتحساب‌های بیمارستانی و مراکز جراحی محدود</div>

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
                    <label>1- در واحد رسیدگی صورتحساب‌های بیمارستانی و مراکز جراحی محدود؛ ماهیانه بطور متوسط
                        ............ پرونده رسیدگی می‌شود.</label>
                    <br>
                    <br>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>نوع پرونده</th>
                                <th>بستری</th>
                                <th>تحت نظر (زیر 6 ساعت)</th>
                                <th>جمع کل</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>تعداد</td>
                                <td><input type="text" name="col1" class="form-control"></td>
                                <td><input type="text" name="col2" class="form-control"></td>
                                <td><input type="text" name="col3" class="form-control"></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <br>
                </div>

                <div>
                    <label>2- تعداد پرسنل واحد رسیدگی صورتحساب‌های بیمارستانی و مراکز جراحی محدود</label>
                    <input type="text" name="question2_count" placeholder="نفر">
                    <label>می‌باشند.</label>
                </div>
                <div>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question2_notes"></textarea>
                </div>

                <div>
                    <label>3- آیا نسبت پرسنل شاغل در واحد به حجم کار کافی است؟</label>
                    <label><input type="radio" name="question3" value="yes"> بلی</label>
                    <label><input type="radio" name="question3" value="no"> خیر</label>
                </div>
                <div>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question3_notes"></textarea>
                </div>

                <div>
                    <label>4- آیا مدارک مثبته بر اساس مصوبه شورای‌عالی بیمه سلامت ضمیمه صورتحساب بیمارستانی
                        می‌باشد‌؟</label>
                    <label><input type="radio" name="question4" value="yes"> بلی</label>
                    <label><input type="radio" name="question4" value="no"> خیر</label>
                </div>

                <div>
                    <label>5- آیا صورتحساب ها و ضمائم دارای مهر و امضاء جراح و مهر مرکز درمانی می‌باشد؟</label>
                    <label><input type="radio" name="question5" value="yes"> بلی</label>
                    <label><input type="radio" name="question5" value="no"> خیر</label>
                </div>
                <div>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question5_notes"></textarea>
                </div>

                <div>
                    <label>6- آیا بیمارستان‌ها دارای گواهینامه اعتبار بخشی معتبر می‌باشند؟</label>
                    <label><input type="radio" name="question6" value="yes"> بلی</label>
                    <label><input type="radio" name="question6" value="no"> خیر</label>
                </div>

                <div>
                    <label>7- آیا در صورت عدم ارائه اعتبار بخشی معتبر، بر اساس دستورالعمل رسیدگی اسناد بستری (مصوبه 96
                        شورای‌عالی بیمه سلامت کشور) اقدام شده است؟</label>
                    <label><input type="radio" name="question7" value="yes"> بلی</label>
                    <label><input type="radio" name="question7" value="no"> خیر</label>
                </div>
                <div>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question7_notes"></textarea>
                </div>

                <div>
                    <label>8- پرداخت صورتحساب ها بر اساس اعتبار بخشی بیمارستان صورت می‌گیرد؟</label>
                    <label><input type="radio" name="question8" value="yes"> بلی</label>
                    <label><input type="radio" name="question8" value="no"> خیر</label>
                </div>

                <div>
                    <label>9- رسیدگی اولیه صورتحساب بیمارستانی در دفتر اسناد پزشکی انجام می‌شود؟</label>
                    <label><input type="radio" name="question9" value="yes"> بلی</label>
                    <label><input type="radio" name="question9" value="no"> خیر</label>
                </div>
                <div>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question9_notes"></textarea>
                </div>

                <div>
                    <label>10- آیا رسیدگی مجدد صورتحساب بیمارستانی توسط مقوم انجام می‌شود؟</label>
                    <label><input type="radio" name="question10" value="yes"> بلی</label>
                    <label><input type="radio" name="question10" value="no"> خیر</label>
                </div>
                <div>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question10_notes"></textarea>
                </div>

                <div>
                    <label>11- برچسب تجهیزات پزشکی استفاده شده در عمل جراحی به برگه شرح عمل یا تصویر آن ضمیمه
                        می‌باشد؟</label>
                    <label><input type="radio" name="question11" value="yes"> بلی</label>
                    <label><input type="radio" name="question11" value="no"> خیر</label>
                </div>

                <div>
                    <label>12- آیا پرداخت اعضای هیأت علمی و پزشکان درمانی تمام وقت بر اساس آئین‌نامه انجام
                        می‌شود؟</label>
                    <label><input type="radio" name="question12" value="yes"> بلی</label>
                    <label><input type="radio" name="question12" value="no"> خیر</label>
                </div>

                <div>
                    <label>13- آیا ثبت کسورات در سیستم TMDS بصورت تفکیکی صورت می‌گیرد؟</label>
                    <label><input type="radio" name="question13" value="yes"> بلی</label>
                    <label><input type="radio" name="question13" value="no"> خیر</label>
                </div>

                <div>
                    <label>14- صورتحساب‌های مراکز جراحی محدود و بیمارستان‌های خصوصی طرف قرارداد که بصورت گلوبال درخواست
                        می‌شود، کنترل می‌گردد؟</label>
                    <label><input type="radio" name="question14" value="yes"> بلی</label>
                    <label><input type="radio" name="question14" value="no"> خیر</label>
                </div>
                <div>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question14_notes"></textarea>
                </div>

                <div>
                    <label>15- نحوه کنترل رسیدگی به صورتحساب های بستری بصورت مجدد، بر اساس معیار خاصی صورت
                        می‌گیرد؟</label>
                    <label><input type="radio" name="question15" value="yes"> بلی</label>
                    <label><input type="radio" name="question15" value="no"> خیر</label>
                </div>
                <div>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question15_notes"></textarea>
                </div>

                <div>
                    <label>16- بر اساس دستورالعمل گلوبال، در صورت تمام وقت بودن هر یک از پزشکان ارائه دهنده خدمات، صرفاً
                        بابت حق العمل جراحی، بیهوشی و ویزیت اولیه نوزاد سالم ارزش ریالی ضریب کای دوم به سر جمع هزینه
                        گلوبال اضافه می‌گردد، آیا دستورالعمل رعایت می‌شود؟</label>
                    <label><input type="radio" name="question16" value="yes"> بلی</label>
                    <label><input type="radio" name="question16" value="no"> خیر</label>
                </div>
                <div>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question16_notes"></textarea>
                </div>

                <div>
                    <label>17- آیا گزارش شرح عمل؛ دارای زمان شروع و پایان جراحی با تایید جراح می‌باشد؟</label>
                    <label><input type="radio" name="question17" value="yes"> بلی</label>
                    <label><input type="radio" name="question17" value="no"> خیر</label>
                </div>

                <div>
                    <label>18- در صورت عدم درج زمان شروع و پایان جراحی با تایید جراح در برگه شرح عمل، گزارش کتبی به مرکز
                        مربوطه (به تفکیک شماره پرونده یا در صورت امکان نام پزشک) انجام می‌شود؟</label>
                    <label><input type="radio" name="question18" value="yes"> بلی</label>
                    <label><input type="radio" name="question18" value="no"> خیر</label>
                </div>
                <div>
                    <label>توضیحات:</label>
                    <textarea class="form-control"
                        name="question18_notes">برگه های شرح عمل بصورت تایپ شده بودند و بصورت اتوماتیک ساعات عمل درج و ثبت گردیده بود</textarea>
                </div>

                <div>
                    <label>19- در صورت عدم درج زمان شروع و پایان جراحی با تایید جراح در برگه شرح عمل، 10 درصد حق العمل
                        جراح به صورت غیرقابل برگشت کسر می‌گردد؟</label>
                    <label><input type="radio" name="question19" value="yes"> بلی</label>
                    <label><input type="radio" name="question19" value="no"> خیر</label>
                </div>
                <div>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question19_notes"></textarea>
                </div>

                <div>
                    <label>20- آیا وضعیت اجرای سامانه رسا در استان مطلوب می‌باشد؟</label>
                    <label><input type="radio" name="question20" value="yes"> بلی</label>
                    <label><input type="radio" name="question20" value="no"> خیر</label>
                </div>
                <div>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question20_notes"></textarea>
                </div>

                <!-- Random Patient Samples -->
                <br>
                <br>
                <label for=""><b>سایر موارد عملکرد واحد رسیدگی صورتحساب‌های بیمارستانی و مراکز جراحی محدود</b></label>
                <br>
                <label for="">1) در نمونه‌های راندوم بررسی شده در برخی پرونده‌ها موارد زیر مشاهده گردید:</label>

                <!-- Patient #1 -->
                <br>
                <br>
                <label for="">بیمار نمونه 1: </label>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>نام بیمار</th>
                            <th>کد ملی</th>
                            <th>شماره پرونده</th>
                            <th>تاریخ ترخیص</th>
                            <th>نام مرکز</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="pat1col1" class="form-control"></td>
                            <td><input type="text" name="pat1col2" class="form-control"></td>
                            <td><input type="text" name="pat1col3" class="form-control"></td>
                            <td><input type="text" name="pat1col4" class="form-control"></td>
                            <td><input type="text" name="pat1col5" class="form-control"></td>
                        </tr>
                    </tbody>
                </table>
                <textarea class="form-control" name="pat1desc" id="pat1desc" placeholder="توضیحات"></textarea>

                <!-- Patient #2 -->
                <br>
                <br>
                <label for="">بیمار نمونه 2: </label>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>نام بیمار</th>
                            <th>کد ملی</th>
                            <th>شماره پرونده</th>
                            <th>تاریخ ترخیص</th>
                            <th>نام مرکز</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="pat2col1" class="form-control"></td>
                            <td><input type="text" name="pat2col2" class="form-control"></td>
                            <td><input type="text" name="pat2col3" class="form-control"></td>
                            <td><input type="text" name="pat2col4" class="form-control"></td>
                            <td><input type="text" name="pat2col5" class="form-control"></td>
                        </tr>
                    </tbody>
                </table>
                <textarea class="form-control" name="pat2desc" id="pat2desc" placeholder="توضیحات"></textarea>

                <!-- Patient #3 -->
                <br>
                <br>
                <label for="">بیمار نمونه 3: </label>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>نام بیمار</th>
                            <th>کد ملی</th>
                            <th>شماره پرونده</th>
                            <th>تاریخ ترخیص</th>
                            <th>نام مرکز</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="pat3col1" class="form-control"></td>
                            <td><input type="text" name="pat3col2" class="form-control"></td>
                            <td><input type="text" name="pat3col3" class="form-control"></td>
                            <td><input type="text" name="pat3col4" class="form-control"></td>
                            <td><input type="text" name="pat3col5" class="form-control"></td>
                        </tr>
                    </tbody>
                </table>
                <textarea class="form-control" name="pat3desc" id="pat3desc" placeholder="توضیحات"></textarea>

                <!-- Patient #4 -->
                <br>
                <br>
                <label for="">بیمار نمونه 4: </label>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>نام بیمار</th>
                            <th>کد ملی</th>
                            <th>شماره پرونده</th>
                            <th>تاریخ ترخیص</th>
                            <th>نام مرکز</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="pat4col1" class="form-control"></td>
                            <td><input type="text" name="pat4col2" class="form-control"></td>
                            <td><input type="text" name="pat4col3" class="form-control"></td>
                            <td><input type="text" name="pat4col4" class="form-control"></td>
                            <td><input type="text" name="pat4col5" class="form-control"></td>
                        </tr>
                    </tbody>
                </table>
                <textarea class="form-control" name="pat4desc" id="pat4desc" placeholder="توضیحات"></textarea>

                <!-- Patient #5 -->
                <br>
                <br>
                <label for="">بیمار نمونه 5: </label>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>نام بیمار</th>
                            <th>کد ملی</th>
                            <th>شماره پرونده</th>
                            <th>تاریخ ترخیص</th>
                            <th>نام مرکز</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="pat5col1" class="form-control"></td>
                            <td><input type="text" name="pat5col2" class="form-control"></td>
                            <td><input type="text" name="pat5col3" class="form-control"></td>
                            <td><input type="text" name="pat5col4" class="form-control"></td>
                            <td><input type="text" name="pat5col5" class="form-control"></td>
                        </tr>
                    </tbody>
                </table>
                <textarea class="form-control" name="pat5desc" id="pat5desc" placeholder="توضیحات"></textarea>

                <br>
                <br>



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