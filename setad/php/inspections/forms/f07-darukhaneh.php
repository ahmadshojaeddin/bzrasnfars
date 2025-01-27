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
        $stmt = $conn->prepare("UPDATE setad.inspections SET f07_darukhaneh_json=? WHERE id=?;");
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
    $stmt = $conn->prepare("SELECT f07_darukhaneh_json, st.name_ as state_name, date_, insst.desc_ as status_desc 
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

            fetch('/setad/php/inspections/forms/f07-darukhaneh.php', {

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

            <div class="section-title">1-7) رسیدگی و کنترل اسناد داروخانه‌ها</div>

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
                    <label>1- عملکرد واحد به طور متوسط ماهیانه به شرح زیر است: ( متوسط سه ماهه دوم سال 1402)</label>
                    <br>
                    <br>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>نوع سند</th>
                                <th>تعداد سند</th>
                                <th>تعداد نسخه</th>
                                <th>میانگین ریالی نسخه</th>
                                <th>میانگین اقلام نسخه</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>داروخانه</td>
                                <td><input type="text" name="general_practitioner_centers" class="form-control"></td>
                                <td><input type="text" name="specialist_centers" class="form-control"></td>
                                <td><input type="text" name="pharmacy_centers" class="form-control"></td>
                                <td><input type="text" name="dentist_centers" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>داروخانه ویژه</td>
                                <td><input type="text" name="dentist_visits" class="form-control"></td>
                                <td><input type="text" name="paraclinic_visits" class="form-control"></td>
                                <td><input type="text" name="clinic_visits" class="form-control"></td>
                                <td><input type="text" name="surgery_visits" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>جمع</td>
                                <td><input type="text" name="general_practitioner_non_visits" class="form-control"></td>
                                <td><input type="text" name="specialist_non_visits" class="form-control"></td>
                                <td><input type="text" name="pharmacy_non_visits" class="form-control"></td>
                                <td><input type="text" name="dentist_non_visits" class="form-control"></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <br>
                </div>


                <!-- Question 2 -->
                <div>
                    <label for="q2">2- تعداد پرسنل واحد رسیدگی صورتحساب‌های داروخانه‌ها</label>
                    <input type="text" id="q2" name="q2" placeholder="تعداد">
                    <label>می باشد</label>
                </div>

                <!-- Question 3 -->
                <div>
                    <label>3- آیا نسبت پرسنل شاغل در واحد داروخانه به حجم کار واحد کافی است؟</label>
                    <label>
                        <input type="radio" name="q3" value="yes"> بلی
                    </label>
                    <label>
                        <input type="radio" name="q3" value="no"> خیر
                    </label>
                </div>
                <div>
                    <label for="q3_comments">توضیحات:</label>
                    <textarea id="q3_comments" name="q3_comments"
                        class="form-control">علاوه بر 6 نفر کارشناش رسیدگی 2 نفر وظیفه پاسخگویی به بیماران و کنترل مجدد  اسناد و صورتحسابها و برنامه ریزی جهت امور پرسنل را به عهده دارند. با توجه به بررسی نامحسوس نسخ مراکز و عدم وجود دستورالعمل رسیدگی برای نسخ الکترونیک و کاستی های موجود درسیستم جامع و زمان بر بودن تجزیه و تحلیل داده های آماری و عدم توانایی پرسنل رسیدگی با روش های جدید نیروی کافی وجود ندارد.</textarea>
                </div>

                <!-- Question 4-1 -->
                <div>
                    <label>1-4- آیا رسیدگی مکانیزه مطابق دستورالعمل ابلاغی انجام می‌شود؟</label>
                    <label>
                        <input type="radio" name="q4_1" value="yes"> بلی
                    </label>
                    <label>
                        <input type="radio" name="q4_1" value="no"> خیر
                    </label>
                </div>
                <div>
                    <label for="q4_1_comments">توضیحات:</label>
                    <textarea id="q4_1_comments" name="q4_1_comments"
                        class="form-control">در این استان باروش های ابداعی و خلاقانه خود به صورت نامحسوس اسناد ونسخ راکنترل می نمایند.</textarea>
                </div>

                <!-- Question 4-2 -->
                <div>
                    <label>2-4- آیا عملکرد نسخه الکترونیک داروخانه‌ها مورد رسیدگی و ارزیابی قرار می‌گیرد؟</label>
                    <label>
                        <input type="radio" name="q4_2" value="yes"> بلی
                    </label>
                    <label>
                        <input type="radio" name="q4_2" value="no"> خیر
                    </label>
                </div>
                <div>
                    <label for="q4_2_comments">توضیحات:</label>
                    <textarea id="q4_2_comments" name="q4_2_comments" class="form-control"></textarea>
                </div>

                <!-- Question 4-3 -->
                <div>
                    <label>3-4- آیا رسیدگی دستی مطابق دستورالعمل‌های ابلاغی انجام می‌گردد؟</label>
                    <label>
                        <input type="radio" name="q4_3" value="yes"> بلی
                    </label>
                    <label>
                        <input type="radio" name="q4_3" value="no"> خیر
                    </label>
                </div>
                <div>
                    <label for="q4_3_comments">توضیحات:</label>
                    <textarea id="q4_3_comments" name="q4_3_comments"
                        class="form-control">بعضی از نسخ تیک کنترل نداشت. همچنین بعضی از مراکز نسخ را بدون پانچ کردن وشماره گذاری ارسال نموده بودند که احتمال مفقود شدن نسخ کاغذی وجود دارد.</textarea>
                </div>

                <!-- Question 4-4 -->
                <div>
                    <label>4-4- آیا نسخ کسور رسیدگی دستی پیوست سند می‌گردد؟</label>
                    <label>
                        <input type="radio" name="q4_4" value="yes"> بلی
                    </label>
                    <label>
                        <input type="radio" name="q4_4" value="no"> خیر
                    </label>
                </div>

                <!-- Question 4-5 -->
                <div>
                    <label>5-4- آیا نسخ غیرالکترونیک واحدهای داروخانه وابسته به مراکز دانشگاه علوم پزشکی توسط مسئول فنی
                        معرفی‌شده، مهر و امضاء می‌گردد؟</label>
                    <label>
                        <input type="radio" name="q4_5" value="yes"> بلی
                    </label>
                    <label>
                        <input type="radio" name="q4_5" value="no"> خیر
                    </label>
                </div>

                <!-- Question 4-6 -->
                <div>
                    <label>6-4- آیا کارشناسان واحد در راستای کنترل غیرحضوری (سیستمی) عملکرد مراکز داروخانه، گزارش تهیه
                        می‌نمایند؟</label>
                    <label>
                        <input type="radio" name="q4_6" value="yes"> بلی
                    </label>
                    <label>
                        <input type="radio" name="q4_6" value="no"> خیر
                    </label>
                </div>
                <div>
                    <label for="q4_6_comments">توضیحات:</label>
                    <textarea id="q4_6_comments" name="q4_6_comments" class="form-control"></textarea>
                </div>

                <!-- Question 5-1 -->
                <div>
                    <label>1-5- آیا آموزش، بازآموزی و اطلاع‌رسانی دستورالعمل‌ها به کارشناسان رسیدگی، مناسب
                        می‌باشد؟</label>
                    <label>
                        <input type="radio" name="q5_1" value="yes"> بلی
                    </label>
                    <label>
                        <input type="radio" name="q5_1" value="no"> خیر
                    </label>
                </div>

                <!-- Question 5-2 -->
                <div>
                    <label>2-5- آیا فرآیندی برای کنترل اسناد ویژه ( از نظر مبلغ درخواستی، نوع خدمت و ...) وجود
                        دارد؟</label>
                    <label>
                        <input type="radio" name="q5_2" value="yes"> بلی
                    </label>
                    <label>
                        <input type="radio" name="q5_2" value="no"> خیر
                    </label>
                </div>
                <div>
                    <label for="q5_2_comments">توضیحات:</label>
                    <textarea id="q5_2_comments" name="q5_2_comments"
                        class="form-control">کنترل مجدد وبررسی دقیقتر اسناد ویژه توسط یک نفر انجام می گردد.</textarea>
                </div>

                <!-- Question 5-3 -->
                <div>
                    <label>3-5- آیا عملکرد مراکزی که رعایت ضوابط و مقررات را نمی‌نمایند، جهت پیگیری و تصمیم‌گیری به
                        واحدهای مرتبط گزارش می‌گردد؟</label>
                    <label>
                        <input type="radio" name="q5_3" value="yes"> بلی
                    </label>
                    <label>
                        <input type="radio" name="q5_3" value="no"> خیر
                    </label>
                </div>

                <!-- Question 5-4 -->
                <div>
                    <label>4-5- آیا ارزیابی مستمر از عملکرد کارکنان واحد رسیدگی داروخانه‌ها انجام می‌شود؟</label>
                    <label>
                        <input type="radio" name="q5_4" value="yes"> بلی
                    </label>
                    <label>
                        <input type="radio" name="q5_4" value="no"> خیر
                    </label>
                </div>
                <div>
                    <label for="q5_4_comments">توضیحات:</label>
                    <textarea id="q5_4_comments" name="q5_4_comments" class="form-control"></textarea>
                </div>




                <br>
                <br>
                <br>
                <br>
                <div class="section-title">2-7) واحد تشکیل پرونده و تائید نسخ دارویی</div>

                <script>
                    let title = "<?php echo "کد: " . $insp_id . " , استان: " . $provinceName . " , تاریخ: " . $inspDate . " , وضعیت: " . $inspStatus; ?>";
                    document.write(`<h6 class="mb-4">( ${title} )</h4>`);
                </script>

                <hr style="border: 1px solid black; width: 100%; margin: 20px auto;">
                <br>
                <!-- <hr style="border: 1px solid black; width: 45%;"> -->
                <br>
                <br>



                <div class="form-group">
                    <label>1- عملکرد واحد به طور متوسط ماهیانه به شرح زیر است: ( متوسط سه ماهه دوم سال 1402)</label>
                    <br>
                    <br>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>نوع خدمت</th>
                                <th>تشکیل پرونده</th>
                                <th>ویرایش پرونده</th>
                                <th>تایید نسخه</th>
                                <th>جمع</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>تعداد</td>
                                <td><input type="text" name="general_practitioner_centers2" class="form-control"></td>
                                <td><input type="text" name="specialist_centers2" class="form-control"></td>
                                <td><input type="text" name="pharmacy_centers2" class="form-control"></td>
                                <td><input type="text" name="dentist_centers2" class="form-control"></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <br>
                </div>



                <!-- Question 2 -->
                <div>
                    <label for="q2">2- تعداد پرسنل واحد تشکیل پرونده و تائید نسخ دارویی</label>
                    <input type="text" id="q2" name="q2" placeholder="تعداد">
                    <label>نفر می باشد</label>
                </div>
                <div>
                    <label for="q2_comments">توضیحات:</label>
                    <textarea id="q2_comments" name="q2_comments" class="form-control"></textarea>
                </div>

                <!-- Question 3 -->
                <div>
                    <label>3- آیا نسبت پرسنل شاغل در واحد تشکیل پرونده و تائید نسخ دارویی به حجم کار کافی است؟</label>
                    <label>
                        <input type="radio" name="q3" value="yes"> بلی
                    </label>
                    <label>
                        <input type="radio" name="q3" value="no"> خیر
                    </label>
                </div>
                <div>
                    <label for="q3_comments">توضیحات:</label>
                    <textarea id="q3_comments" name="q3_comments" class="form-control"></textarea>
                </div>

                <!-- Question 4 -->
                <div>
                    <label>4- آیا کارشناسان تایید دارو در داروخانه‌های ویژه مستقر هستند؟</label>
                    <label>
                        <input type="radio" name="q4" value="yes"> بلی
                    </label>
                    <label>
                        <input type="radio" name="q4" value="no"> خیر
                    </label>
                </div>
                <div>
                    <label for="q4_comments">توضیحات:</label>
                    <textarea id="q4_comments" name="q4_comments" class="form-control"></textarea>
                </div>

                <!-- Question 5 -->
                <div>
                    <label>5- آیا آموزش‌های مستمر در خصوص تشکیل پرونده و تائید نسخ دارویی بر اساس دستورالعمل‌های ابلاغی
                        به کارشناسان انجام می‌گردد؟</label>
                    <label>
                        <input type="radio" name="q5" value="yes"> بلی
                    </label>
                    <label>
                        <input type="radio" name="q5" value="no"> خیر
                    </label>
                </div>
                <div>
                    <label for="q5_comments">توضیحات:</label>
                    <textarea id="q5_comments" name="q5_comments" class="form-control"></textarea>
                </div>

                <!-- Question 6 -->
                <div>
                    <label>6- آیا نسخ مطابق بخشنامه‌های ابلاغی مورد تائید قرار می‌گیرد؟</label>
                    <label>
                        <input type="radio" name="q6" value="yes"> بلی
                    </label>
                    <label>
                        <input type="radio" name="q6" value="no"> خیر
                    </label>
                </div>
                <div>
                    <label for="q6_comments">توضیحات:</label>
                    <textarea id="q6_comments" name="q6_comments"
                        class="form-control">برخی موارد در خصوص عدم رعایت ضوابط در بند 10 ذکر شده است.</textarea>
                </div>

                <!-- Question 7 -->
                <div>
                    <label>7- آیا در ایام تعطیل، واحد تایید دارو فعال می‌باشد؟</label>
                    <label>
                        <input type="radio" name="q7" value="yes"> بلی
                    </label>
                    <label>
                        <input type="radio" name="q7" value="no"> خیر
                    </label>
                </div>
                <div>
                    <label for="q7_comments">توضیحات:</label>
                    <textarea id="q7_comments" name="q7_comments" class="form-control"></textarea>
                </div>

                <!-- Question 8 -->
                <div>
                    <label>8- آیا عملکرد مراکزی که رعایت ضوابط و مقررات را نمی‌نمایند، جهت پیگیری و تصمیم‌گیری به
                        واحدهای مرتبط گزارش می‌گردد؟</label>
                    <label>
                        <input type="radio" name="q8" value="yes"> بلی
                    </label>
                    <label>
                        <input type="radio" name="q8" value="no"> خیر
                    </label>
                </div>

                <!-- Question 9 -->
                <div>
                    <label>9- آیا ارزیابی مستمر از عملکرد کارکنان واحد تشکیل پرونده و تائید نسخ دارویی انجام
                        می‌شود؟</label>
                    <label>
                        <input type="radio" name="q9" value="yes"> بلی
                    </label>
                    <label>
                        <input type="radio" name="q9" value="no"> خیر
                    </label>
                </div>
                <div>
                    <label for="q9_comments">توضیحات:</label>
                    <textarea id="q9_comments" name="q9_comments" class="form-control"></textarea>
                </div>

                <!-- Question 10 -->
                <br />
                <br />
                <div>
                    <label for="q10_comments">10- ارزیابی سوابق بیماران دارای پرونده دارویی و نسخ دارویی:</label>
                    <br />نسخ ثبت شده و مدارک و مستندات بیماران پرونده‌ای در سیستم جامع اسناد پزشکی و پورتال معاونت درمان
                    با روش نمونه‌گیری تصادفی مورد بررسی قرار گرفت. برخی مشاهدات به عنوان نمونه به شرح زیر می‌باشد:</p>



                    <!-- Question 10-1 -->
                    <br />
                    <br />
                    <div class="form-group">
                        <label>1) بررسی نسخ و پرونده بیماران داروی آدالیمومب (سینورا) (کد 16164):</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="participation" id="sinora_yes"
                                value="yes">
                            <label class="form-check-label" for="sinora_yes">
                                در بررسی داروی آدالیمومب (سینورا)، مورد خاصی مشاهده نگردید.
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="participation" id="sinora_no"
                                value="no">
                            <label class="form-check-label" for="sinora_no">
                                در بررسی داروی آدالیمومب (سینورا)، موارد زیر مشاهده گردید:
                            </label>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ردیف</th>
                                <th>کد ملی بیمار</th>
                                <th>نام پزشک</th>
                                <th>نظام پزشکی</th>
                                <th>تخصص</th>
                                <th>توضیحات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><input type="text" name="code_melli1_sinora" class="form-control"></td>
                                <td><input type="text" name="dr_name1_sinora" class="form-control"></td>
                                <td><input type="text" name="dr_nezam1_sinora" class="form-control"></td>
                                <td><input type="text" name="dr_special1_sinora" class="form-control"></td>
                                <td><input type="text" name="desc1_sinora" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><input type="text" name="code_melli2_sinora" class="form-control"></td>
                                <td><input type="text" name="dr_name2_sinora" class="form-control"></td>
                                <td><input type="text" name="dr_nezam2_sinora" class="form-control"></td>
                                <td><input type="text" name="dr_special2_sinora" class="form-control"></td>
                                <td><input type="text" name="desc2_sinora" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td><input type="text" name="code_melli3_sinora" class="form-control"></td>
                                <td><input type="text" name="dr_name3_sinora" class="form-control"></td>
                                <td><input type="text" name="dr_nezam3_sinora" class="form-control"></td>
                                <td><input type="text" name="dr_special3_sinora" class="form-control"></td>
                                <td><input type="text" name="desc3_sinora" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td><input type="text" name="code_melli4_sinora" class="form-control"></td>
                                <td><input type="text" name="dr_name4_sinora" class="form-control"></td>
                                <td><input type="text" name="dr_nezam4_sinora" class="form-control"></td>
                                <td><input type="text" name="dr_special4_sinora" class="form-control"></td>
                                <td><input type="text" name="desc4_sinora" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td><input type="text" name="code_melli5_sinora" class="form-control"></td>
                                <td><input type="text" name="dr_name5_sinora" class="form-control"></td>
                                <td><input type="text" name="dr_nezam5_sinora" class="form-control"></td>
                                <td><input type="text" name="dr_special5_sinora" class="form-control"></td>
                                <td><input type="text" name="desc5_sinora" class="form-control"></td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- End of Question 10-1 -->




                    <!-- Question 10-2 -->
                    <br />
                    <br />
                    <div class="form-group">
                        <label>2) بررسی نسخ و پرونده بیماران داروی لیراگلوتاید (ویکتوزا) (کد 22666):</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="participation" id="victoza_yes"
                                value="yes">
                            <label class="form-check-label" for="victoza_yes">
                                در بررسی داروی لیراگلوتاید (ویکتوزا)، مورد خاصی مشاهده نگردید.
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="participation" id="victoza_no"
                                value="no">
                            <label class="form-check-label" for="victoza_no">
                                در بررسی داروی لیراگلوتاید (ویکتوزا)، موارد زیر مشاهده گردید:
                            </label>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ردیف</th>
                                <th>کد ملی بیمار</th>
                                <th>نام پزشک</th>
                                <th>نظام پزشکی</th>
                                <th>تخصص</th>
                                <th>توضیحات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><input type="text" name="code_melli1_victoza" class="form-control"></td>
                                <td><input type="text" name="dr_name1_victoza" class="form-control"></td>
                                <td><input type="text" name="dr_nezam1_victoza" class="form-control"></td>
                                <td><input type="text" name="dr_special1_victoza" class="form-control"></td>
                                <td><input type="text" name="desc1_victoza" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><input type="text" name="code_melli2_victoza" class="form-control"></td>
                                <td><input type="text" name="dr_name2_victoza" class="form-control"></td>
                                <td><input type="text" name="dr_nezam2_victoza" class="form-control"></td>
                                <td><input type="text" name="dr_special2_victoza" class="form-control"></td>
                                <td><input type="text" name="desc2_victoza" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td><input type="text" name="code_melli3_victoza" class="form-control"></td>
                                <td><input type="text" name="dr_name3_victoza" class="form-control"></td>
                                <td><input type="text" name="dr_nezam3_victoza" class="form-control"></td>
                                <td><input type="text" name="dr_special3_victoza" class="form-control"></td>
                                <td><input type="text" name="desc3_victoza" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td><input type="text" name="code_melli4_victoza" class="form-control"></td>
                                <td><input type="text" name="dr_name4_victoza" class="form-control"></td>
                                <td><input type="text" name="dr_nezam4_victoza" class="form-control"></td>
                                <td><input type="text" name="dr_special4_victoza" class="form-control"></td>
                                <td><input type="text" name="desc4_victoza" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td><input type="text" name="code_melli5_victoza" class="form-control"></td>
                                <td><input type="text" name="dr_name5_victoza" class="form-control"></td>
                                <td><input type="text" name="dr_nezam5_victoza" class="form-control"></td>
                                <td><input type="text" name="dr_special5_victoza" class="form-control"></td>
                                <td><input type="text" name="desc5_victoza" class="form-control"></td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- End of Question 10-2 -->



                </div>

                <!-- todo:... -->
                <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
                <div style="border: 10px; padding: 100px; margin: 100px; background-color: orange;">
                    <p>to do...</p>
                </div>
                <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->



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