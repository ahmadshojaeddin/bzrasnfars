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
        $stmt = $conn->prepare("UPDATE setad.inspections SET f08_paraclinic_json=? WHERE id=?;");
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
    $stmt = $conn->prepare("SELECT f08_paraclinic_json, st.name_ as state_name, date_, insst.desc_ as status_desc 
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

            fetch('/setad/php/inspections/forms/f08-paraclinic.php', {

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

            <div class="section-title">8) عملکرد واحد رسیدگی صورتحساب‌های مؤسسات پاراکلینیک</div>

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
                                <th>میانگین اقلام نسخه</th>
                                <th>میانگین اقلام نسخه</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>آزمایشگاه</td>
                                <td><input type="text" name="general_practitioner_centers" class="form-control"></td>
                                <td><input type="text" name="specialist_centers" class="form-control"></td>
                                <td><input type="text" name="pharmacy_centers" class="form-control"></td>
                                <td><input type="text" name="dentist_centers" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>رادیولوژی</td>
                                <td><input type="text" name="dentist_visits" class="form-control"></td>
                                <td><input type="text" name="paraclinic_visits" class="form-control"></td>
                                <td><input type="text" name="clinic_visits" class="form-control"></td>
                                <td><input type="text" name="surgery_visits" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>سونوگرافی</td>
                                <td><input type="text" name="general_practitioner_non_visits" class="form-control"></td>
                                <td><input type="text" name="specialist_non_visits" class="form-control"></td>
                                <td><input type="text" name="pharmacy_non_visits" class="form-control"></td>
                                <td><input type="text" name="dentist_non_visits" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>سی‌تی اسکن</td>
                                <td><input type="text" name="specialist_total_visits" class="form-control"></td>
                                <td><input type="text" name="pharmacy_total_visits" class="form-control"></td>
                                <td><input type="text" name="dentist_total_visits" class="form-control"></td>
                                <td><input type="text" name="paraclinic_total_visits" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>ام آر آی</td>
                                <td><input type="text" name="specialist_total_visits" class="form-control"></td>
                                <td><input type="text" name="pharmacy_total_visits" class="form-control"></td>
                                <td><input type="text" name="dentist_total_visits" class="form-control"></td>
                                <td><input type="text" name="paraclinic_total_visits" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>سنجش تراکم استخوان</td>
                                <td><input type="text" name="specialist_total_visits" class="form-control"></td>
                                <td><input type="text" name="pharmacy_total_visits" class="form-control"></td>
                                <td><input type="text" name="dentist_total_visits" class="form-control"></td>
                                <td><input type="text" name="paraclinic_total_visits" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>پزشکی هسته‌ای</td>
                                <td><input type="text" name="specialist_total_visits" class="form-control"></td>
                                <td><input type="text" name="pharmacy_total_visits" class="form-control"></td>
                                <td><input type="text" name="dentist_total_visits" class="form-control"></td>
                                <td><input type="text" name="paraclinic_total_visits" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>رادیوتراپی</td>
                                <td><input type="text" name="specialist_total_visits" class="form-control"></td>
                                <td><input type="text" name="pharmacy_total_visits" class="form-control"></td>
                                <td><input type="text" name="dentist_total_visits" class="form-control"></td>
                                <td><input type="text" name="paraclinic_total_visits" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>فیزیوتراپی</td>
                                <td><input type="text" name="specialist_total_visits" class="form-control"></td>
                                <td><input type="text" name="pharmacy_total_visits" class="form-control"></td>
                                <td><input type="text" name="dentist_total_visits" class="form-control"></td>
                                <td><input type="text" name="paraclinic_total_visits" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>سایر با ذکر نام</td>
                                <td><input type="text" name="specialist_total_visits" class="form-control"></td>
                                <td><input type="text" name="pharmacy_total_visits" class="form-control"></td>
                                <td><input type="text" name="dentist_total_visits" class="form-control"></td>
                                <td><input type="text" name="paraclinic_total_visits" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>جمع</td>
                                <td><input type="text" name="general_practitioner_avg_visits" class="form-control"></td>
                                <td><input type="text" name="specialist_avg_visits" class="form-control"></td>
                                <td><input type="text" name="pharmacy_avg_visits" class="form-control"></td>
                                <td><input type="text" name="dentist_avg_visits" class="form-control"></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <br>
                </div>



                <!-- Question 2 -->
                <div>
                    <label for="q2">2- تعداد پرسنل واحد پاراکلینیک</label>
                    <input type="text" id="q2" name="q2" placeholder="تعداد">
                    <label>نفر می باشد</label>
                </div>

                <!-- Question 3 -->
                <div>
                    <label>3- آیا نسبت پرسنل شاغل در واحد پاراکلینیک به حجم کار کافی است؟</label>
                    <label><input type="radio" name="q3" value="yes"> بلي</label>
                    <label><input type="radio" name="q3" value="no"> خير</label>
                </div>
                <div>
                    <label for="q3_comments">توضیحات:</label>
                    <textarea id="q3_comments" name="q3_comments" class="form-control"></textarea>
                </div>

                <!-- Question 4 -->
                <div>
                    <label>4- آیا برای مراکز پاراکلینیک طرف قرارداد سقف ریالی خرید خدمات تعیین شده است؟</label>
                    <label><input type="radio" name="q4" value="yes"> بلي</label>
                    <label><input type="radio" name="q4" value="no"> خير</label>
                </div>
                <div>
                    <label for="q4_comments">توضیحات:</label>
                    <textarea id="q4_comments" name="q4_comments" class="form-control"></textarea>
                </div>

                <!-- Question 5 -->
                <div>
                    <label>5- آیا مدارک و مستندات مشمولین مطابق آئین نامه پزشکان تمام وقتی دریافت و کنترل
                        می‌گردد؟</label>
                    <label><input type="radio" name="q5" value="yes"> بلي</label>
                    <label><input type="radio" name="q5" value="no"> خير</label>
                </div>

                <!-- Question 6.1 -->
                <div>
                    <label>1-6- آیا مقومی مکانیزه طبق دستورالعمل ابلاغی انجام می‌شود؟</label>
                    <label><input type="radio" name="q6_1" value="yes"> بلي</label>
                    <label><input type="radio" name="q6_1" value="no"> خير</label>
                </div>
                <div>
                    <label for="q6_1_comments">توضیحات:</label>
                    <textarea id="q6_1_comments" name="q6_1_comments" class="form-control"></textarea>
                </div>

                <!-- Question 6.2 -->
                <div>
                    <label>2-6- آیا عملکرد نسخه الکترونیک مراکز مورد رسیدگی و ارزیابی قرار می‌گیرد؟</label>
                    <label><input type="radio" name="q6_2" value="yes"> بلي</label>
                    <label><input type="radio" name="q6_2" value="no"> خير</label>
                </div>
                <div>
                    <label for="q6_2_comments">توضیحات:</label>
                    <textarea id="q6_2_comments" name="q6_2_comments" class="form-control"></textarea>
                </div>

                <!-- Question 6.3 -->
                <div>
                    <label>3-6- آیا مقومی دستی مطابق دستورالعمل‌های صادره انجام می‌شود؟</label>
                    <label><input type="radio" name="q6_3" value="yes"> بلي</label>
                    <label><input type="radio" name="q6_3" value="no"> خير</label>
                </div>

                <!-- Question 6.4 -->
                <div>
                    <label>4-6- آیا نسخ کسور مقومی دستی ضمیمه سند می‌گردد؟</label>
                    <label><input type="radio" name="q6_4" value="yes"> بلي</label>
                    <label><input type="radio" name="q6_4" value="no"> خير</label>
                </div>

                <!-- Question 6.5 -->
                <div>
                    <label>5-6- آیا گزارش خدمات ملزم به ثبت تشخیص تحت وب در پورتال کنترل می‌گردد؟</label>
                    <label><input type="radio" name="q6_5" value="yes"> بلي</label>
                    <label><input type="radio" name="q6_5" value="no"> خير</label>
                </div>

                <!-- Question 6.6 -->
                <div>
                    <label>6-6- آیا نسخ دارای ضمائم و مدارک مثبته مانند رادیوتراپی بر اساس بخشنامه‌های ابلاغی رسیدگی
                        می‌شوند؟</label>
                    <label><input type="radio" name="q6_6" value="yes"> بلي</label>
                    <label><input type="radio" name="q6_6" value="no"> خير</label>
                </div>

                <!-- Question 6.7 -->
                <div>
                    <label>7-6- آیا بخشنامه‌های ابلاغی درخصوص نحوه پرداخت خدمات ژنتیک، روش اسپکت، سنجش تراکم استخوان و
                        ... در رسیدگی نسخ رعایت می‌شود؟</label>
                    <label><input type="radio" name="q6_7" value="yes"> بلي</label>
                    <label><input type="radio" name="q6_7" value="no"> خير</label>
                </div>

                <!-- Question 6.8 -->
                <div>
                    <label>8-6- آیا گزارشات خدمات پاراکلینیک مشمول آئین‌نامه اعضای هیات علمی و پزشکان تمام وقتی، تحت وب
                        ثبت می‌گردد؟</label>
                    <label><input type="radio" name="q6_8" value="yes"> بلي</label>
                    <label><input type="radio" name="q6_8" value="no"> خير</label>
                </div>
                <div>
                    <label for="q6_8_comments">توضیحات:</label>
                    <textarea id="q6_8_comments" name="q6_8_comments" class="form-control"></textarea>
                </div>

                <!-- Question 6.9 -->
                <div>
                    <label>9-6- آیا نسخ واحدهای پاراکلینیک وابسته به مراکز دانشگاه علوم پزشکی توسط مسئول فنی معرفی شده،
                        مهر و امضاء می‌گردد؟</label>
                    <label><input type="radio" name="q6_9" value="yes"> بلي</label>
                    <label><input type="radio" name="q6_9" value="no"> خير</label>
                </div>

                <!-- Question 6.10 -->
                <div>
                    <label>10-6- آیا کارشناسان واحد در راستای کنترل غیرحضوری (نامحسوس) عملکرد مؤسسات پاراکلینیک، گزارش
                        تهیه می‌نمایند؟</label>
                    <label><input type="radio" name="q6_10" value="yes"> بلي</label>
                    <label><input type="radio" name="q6_10" value="no"> خير</label>
                </div>
                <div>
                    <label for="q6_10_comments">توضیحات:</label>
                    <textarea id="q6_10_comments" name="q6_10_comments" class="form-control"></textarea>
                </div>

                <!-- Question 7.1 -->
                <div>
                    <label>1-7- آیا نسخ مطابق دستورالعمل و بخشنامه‌های ابلاغی مورد تائید قرار می‌گیرد؟</label>
                    <label><input type="radio" name="q7_1" value="yes"> بلي</label>
                    <label><input type="radio" name="q7_1" value="no"> خير</label>
                </div>
                <div>
                    <label for="q7_1_comments">توضیحات:</label>
                    <textarea id="q7_1_comments" name="q7_1_comments" class="form-control"></textarea>
                </div>

                <!-- Additional Questions -->
                <!-- Continue with similar structure for remaining questions as shown above. -->

                <!-- Additional Comment Section -->
                <div>
                    <label for="additional_comments">سایر موارد عملکرد واحد رسیدگی صورتحساب‌های مؤسسات
                        پاراکلینیک:</label>
                    <textarea id="additional_comments" name="additional_comments" class="form-control"></textarea>
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