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

            <div class="section-title">9) عملکرد واحد نظارت بیمارستانی</div>

            <script>
                let title = "<?php echo "کد: " . $insp_id . " , استان: " . $provinceName . " , تاریخ: " . $inspDate . " , وضعیت: " . $inspStatus; ?>";
                document.write(`<h6 class="mb-4">( ${title} )</h4>`);
            </script>

            <hr style="border: 1px solid black; width: 100%; margin: 20px auto;">
            <br>


            <!-- <hr style="border: 1px solid black; width: 45%;"> -->


            <form id="myForm" action="submit_form.php" method="post">


                <!-- Question 1 -->
                <div>
                    <label for="q1_hospital">1- تعداد</label>
                    <input type="text" id="q1_hospital" name="q1_hospital">
                    <label for="q1_hospital">مرکز بیمارستانی‌ و</label>
                    <input type="text" id="q1_surgery" name="q1_surgery">
                    <label for="q1_surgery">مرکز جراحی محدود طرف قرارداد می‌باشند.</label>
                </div>

                <!-- Question 2 -->
                <div>
                    <label for="q2_personnel">2- تعداد پرسنل واحد ناظرین بیمارستانی</label>
                    <input type="text" id="q2_personnel" name="q2_personnel">
                    <label for="q2_personnel">نفر می‌باشند.</label>
                </div>

                <!-- Question 3 -->
                <div>
                    <label>3- آیا نسبت پرسنل شاغل در واحد به حجم کار کافی است؟</label>
                    <label><input type="radio" name="q3" value="yes"> بلي</label>
                    <label><input type="radio" name="q3" value="no"> خير</label>
                </div>
                <div>
                    <label for="q3_comments">توضیحات:</label>
                    <textarea id="q3_comments" name="q3_comments" class="form-control"></textarea>
                </div>

                <!-- Question 4 -->
                <div>
                    <label>4- آیا گردش سالیانه ناظرین بیمارستان (با در نظر گرفتن موقعیت جغرافیایی) صورت می‌گیرد؟</label>
                    <label><input type="radio" name="q4" value="yes"> بلي</label>
                    <label><input type="radio" name="q4" value="no"> خير</label>
                </div>

                <!-- Question 5 -->
                <div>
                    <label>5- آیا در شهرهای فاقد ناظر بیمارستانی، مفاد دستورالعمل شماره 15962/4020 مورخ 18/05/1378 ریاست
                        هیأت مدیره و مدیرعامل وقت سازمان اجرا شده است؟</label>
                    <label><input type="radio" name="q5" value="yes"> بلي</label>
                    <label><input type="radio" name="q5" value="no"> خير</label>
                </div>


                <br>
                <br>
                <br>

                <div class="form-group">
                    <label><b>توضیحات: بیمارستان‌های فاقد کارشناس ناظر بیمارستانی</b></label>
                    <br>
                    <br>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ردیف</th>
                                <th>نام مرکز</th>
                                <th>نام شهرستان</th>
                                <th>متوسط تعداد صورتحسابها</th>
                                <th>شعبه یا درمانگاه ملکی</th>
                                <th>توضیحات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><input type="text" name="row1_col2" class="form-control"></td>
                                <td><input type="text" name="row1_col3" class="form-control"></td>
                                <td><input type="text" name="row1_col4" class="form-control"></td>
                                <td><input type="text" name="row1_col5" class="form-control"></td>
                                <td><input type="text" name="row1_col6" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td><input type="text" name="row2_col2" class="form-control"></td>
                                <td><input type="text" name="row2_col3" class="form-control"></td>
                                <td><input type="text" name="row2_col4" class="form-control"></td>
                                <td><input type="text" name="row2_col5" class="form-control"></td>
                                <td><input type="text" name="row2_col6" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td><input type="text" name="row3_col2" class="form-control"></td>
                                <td><input type="text" name="row3_col3" class="form-control"></td>
                                <td><input type="text" name="row3_col4" class="form-control"></td>
                                <td><input type="text" name="row3_col5" class="form-control"></td>
                                <td><input type="text" name="row3_col6" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td><input type="text" name="row4_col2" class="form-control"></td>
                                <td><input type="text" name="row4_col3" class="form-control"></td>
                                <td><input type="text" name="row4_col4" class="form-control"></td>
                                <td><input type="text" name="row4_col5" class="form-control"></td>
                                <td><input type="text" name="row4_col6" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td><input type="text" name="row5_col2" class="form-control"></td>
                                <td><input type="text" name="row5_col3" class="form-control"></td>
                                <td><input type="text" name="row5_col4" class="form-control"></td>
                                <td><input type="text" name="row5_col5" class="form-control"></td>
                                <td><input type="text" name="row5_col6" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td><input type="text" name="row6_col2" class="form-control"></td>
                                <td><input type="text" name="row6_col3" class="form-control"></td>
                                <td><input type="text" name="row6_col4" class="form-control"></td>
                                <td><input type="text" name="row6_col5" class="form-control"></td>
                                <td><input type="text" name="row6_col6" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td><input type="text" name="row7_col2" class="form-control"></td>
                                <td><input type="text" name="row7_col3" class="form-control"></td>
                                <td><input type="text" name="row7_col4" class="form-control"></td>
                                <td><input type="text" name="row7_col5" class="form-control"></td>
                                <td><input type="text" name="row7_col6" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td><input type="text" name="row8_col2" class="form-control"></td>
                                <td><input type="text" name="row8_col3" class="form-control"></td>
                                <td><input type="text" name="row8_col4" class="form-control"></td>
                                <td><input type="text" name="row8_col5" class="form-control"></td>
                                <td><input type="text" name="row8_col6" class="form-control"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div style="white-space: nowrap;">
                    <label>ضمنا در مراکز</label>
                    <input type="text" name="marakez" id="marakez" placeholder="شماره مراکز"
                        style="display: inline-block; width: auto;">
                    <label><b>از همکار ملکی جهت رویت استفاده نموده و کارشناسان ناظر پرونده ها را رسیدگی
                            می‌نمایند.</b></label>
                </div>
                <br>
                <br>


                <div>
                    <label>6- آیا کنترل اسناد مثبته (سابقه 60 روز، 5 ساله و ...) صورت می‌گیرد؟</label>
                    <label><input type="radio" name="question6" value="yes"> بلی</label>
                    <label><input type="radio" name="question6" value="no"> خیر</label>
                </div>

                <div>
                    <label>7- آیا بیماران تحت تکفل‌بودن (شامل فرزندان دختر بالای 18 و ...) بر اساس دستورالعمل ابلاغی
                        کنترل شده است؟</label>
                    <label><input type="radio" name="question7" value="yes"> بلی</label>
                    <label><input type="radio" name="question7" value="no"> خیر</label>
                </div>

                <div>
                    <label>8- آیا رسیدگی اولیه صورتحساب‌ها توسط ناظر در بیمارستان صورت می‌گیرد؟</label>
                    <label><input type="radio" name="question8" value="yes"> بلی</label>
                    <label><input type="radio" name="question8" value="no"> خیر</label>
                </div>

                <div>
                    <label>9- آیا تقویم اسناد بیمارستانی توسط کارشناس رسیدگی‌کننده (فنی) با خودکار سبز و کارشناس
                        حسابداری با خودکار قرمز در صورتحساب درج ‌می‌گردد؟</label>
                    <label><input type="radio" name="question9" value="yes"> بلی</label>
                    <label><input type="radio" name="question9" value="no"> خیر</label>
                </div>
                <div>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question9_notes"></textarea>
                </div>

                <div>
                    <label>10- آیا کارشناس پس از بررسی یک به یک بندهای صورتحساب در صورت تایید مبالغ با علامت (✓) کنار هر
                        رقم آنرا تایید نموده است؟</label>
                    <label><input type="radio" name="question10" value="yes"> بلی</label>
                    <label><input type="radio" name="question10" value="no"> خیر</label>
                </div>
                <div>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question10_notes"></textarea>
                </div>

                <div>
                    <label>11- آیا کارشناس رسیدگی کننده درصورت اعمال تعدیلات رقم صحیح را در کنار آن قید نموده و در
                        مواردی که رقم مربوطه کاملاً غیرقابل محاسبه است با صفر مشخص کرده و توضیح مختصری عنوان نموده
                        اند؟</label>
                    <label><input type="radio" name="question11" value="yes"> بلی</label>
                    <label><input type="radio" name="question11" value="no"> خیر</label>
                </div>
                <div>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question11_notes"></textarea>
                </div>

                <div>
                    <label>12- آیا کارشناس رسیدگی‌کننده، ریز دارو و تجهیزات درخواستی در صورتحساب بستری را با برگه
                        درخواست پزشک معالج، دستورات پرستاری و سایر مستنداتی که بابت مصرف موارد فوق در سند بستری موجود
                        است مطابقت داده و لیست نهایی را تأیید می نماید؟</label>
                    <label><input type="radio" name="question12" value="yes"> بلی</label>
                    <label><input type="radio" name="question12" value="no"> خیر</label>
                </div>

                <div>
                    <label>13- آیا ارزیابی مستمر از عملکرد ناظرین بیمارستانی انجام می‌شود؟</label>
                    <label><input type="radio" name="question13" value="yes"> بلی</label>
                    <label><input type="radio" name="question13" value="no"> خیر</label>
                </div>
                <div>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question13_notes"></textarea>
                </div>

                <div>
                    <label>سایر موارد ارزیابی عملکرد واحد ناظرین بیمارستانی:</label>
                    <textarea class="form-control" name="additional_notes"></textarea>
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