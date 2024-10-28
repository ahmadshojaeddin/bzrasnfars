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
        $stmt = $conn->prepare("UPDATE setad.inspections SET f06_pezeshkan_json=? WHERE id=?;");
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
    $stmt = $conn->prepare("SELECT f06_pezeshkan_json, st.name_ as state_name, date_, insst.desc_ as status_desc 
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

            fetch('/setad/php/inspections/forms/f06-pezeshkan.php', {

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

            <div class="section-title">6) عملکرد واحد رسیدگی صورتحساب‌های پزشکان، دندانپزشکان و درمانگاه‌ها</div>

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
                                <th>تعداد ویزیت</th>
                                <th>تعداد خدمت جنبی</th>
                                <th>جمع</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>پزشک</td>
                                <td><input type="text" name="general_practitioner_centers" class="form-control"></td>
                                <td><input type="text" name="specialist_centers" class="form-control"></td>
                                <td><input type="text" name="pharmacy_centers" class="form-control"></td>
                                <td><input type="text" name="dentist_centers" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>دندانپزشک</td>
                                <td><input type="text" name="dentist_visits" class="form-control"></td>
                                <td><input type="text" name="paraclinic_visits" class="form-control"></td>
                                <td><input type="text" name="clinic_visits" class="form-control"></td>
                                <td><input type="text" name="surgery_visits" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>درمانگاه</td>
                                <td><input type="text" name="general_practitioner_non_visits" class="form-control"></td>
                                <td><input type="text" name="specialist_non_visits" class="form-control"></td>
                                <td><input type="text" name="pharmacy_non_visits" class="form-control"></td>
                                <td><input type="text" name="dentist_non_visits" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>سایر با ذکر نام</td>
                                <td><input type="text" name="specialist_total_visits" class="form-control"></td>
                                <td><input type="text" name="pharmacy_total_visits" class="form-control"></td>
                                <td><input type="text" name="dentist_total_visits" class="form-control"></td>
                                <td><input type="text" name="paraclinic_total_visits" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>جمع کل</td>
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
                <div class="form-group">
                    <label for="clinic-personnel">2- تعداد پرسنل واحد پزشکان، دندانپزشکان و درمانگاه‌ها</label>
                    <input type="text" id="total-clinic-personnel-clinic" placeholder="نفر درمانگاه" required>
                    <label>(</label>
                    <input type="text" id="clinic-personnel-clinic" placeholder="نفر درمانگاه" required>
                    <label> و </label>
                    <input type="text" id="clinic-personnel-doctors" placeholder="نفر پزشکان" required>
                    <label>) نفر می‌باشد.</label>
                </div>

                <!-- Question 3 -->
                <div class="form-group">
                    <label>3- آیا نسبت پرسنل شاغل در واحد پزشکان، دندانپزشکان و درمانگاه‌ها به حجم کار واحد کافی
                        است؟</label>
                    <div>
                        <label><input type="radio" name="sufficient-staff" value="yes"> بلی</label>
                        <label><input type="radio" name="sufficient-staff" value="no"> خیر</label>
                    </div>
                    <textarea class="form-control" placeholder="توضیحات"></textarea>
                </div>

                <!-- Question 4 -->
                <div class="form-group">
                    <label>4- آیا در قرارداد با پزشکان، دندانپزشکان و درمانگاه‌ها محدودیت سقف تعدادی/ ریالی تعیین شده
                        است؟</label>
                    <div>
                        <label><input type="radio" name="contract-limitation" value="yes"> بلی</label>
                        <label><input type="radio" name="contract-limitation" value="no"> خیر</label>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>نوع ویزیت</th>
                            <th>حداقل سقف تعداد/ریالی ویزیت</th>
                            <th>حداکثر سقف تعداد/ ریالی ویزیت</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>پزشک عمومی</td>
                            <td><input type="text" name="general_practitioner_centers" class="form-control"></td>
                            <td><input type="text" name="specialist_centers" class="form-control"></td>
                        </tr>
                        <tr>
                            <td>متخصص و فوق تخصص</td>
                            <td><input type="text" name="dentist_visits" class="form-control"></td>
                            <td><input type="text" name="paraclinic_visits" class="form-control"></td>
                        </tr>
                        <tr>
                            <td>دندانپزشک</td>
                            <td><input type="text" name="general_practitioner_non_visits" class="form-control"></td>
                            <td><input type="text" name="specialist_non_visits" class="form-control"></td>
                        </tr>
                        <tr>
                            <td>درمانگاه</td>
                            <td><input type="text" name="specialist_total_visits" class="form-control"></td>
                            <td><input type="text" name="pharmacy_total_visits" class="form-control"></td>
                        </tr>
                    </tbody>
                </table>

                <label>سقف خدمات تخصصی پزشکان: </label>
                <input id="saghf-khadamate-pezeshkan" type="text" placeholder="تعداد">
                <br>
                <br>
                <textarea id="desc" class="form-control" placeholder="توضیحات"></textarea>
                <br>
                <br>

                <!-- Question 5 -->
                <div class="form-group">
                    <label>5- آیا برای موافقت با ارائه خدمات تخصصی پزشکان متقاضی به نسبت ویزیت‌های انجام شده، سقف تعیین
                        می‌گردد؟</label>
                    <div>
                        <label><input type="radio" name="specialist-services-ceiling" value="yes"> بلی</label>
                        <label><input type="radio" name="specialist-services-ceiling" value="no"> خیر</label>
                    </div>
                    <textarea class="form-control" placeholder="توضیحات"></textarea>
                </div>

                <!-- Question 6 -->
                <div class="form-group">
                    <label>6- آیا ضوابط پرداخت ویزیت و خدمت همزمان رعایت می‌گردد؟</label>
                    <div>
                        <label><input type="radio" name="visit-payment-rules" value="yes"> بلی</label>
                        <label><input type="radio" name="visit-payment-rules" value="no"> خیر</label>
                    </div>
                </div>

                <!-- Question 7 -->
                <div class="form-group">
                    <label>7- آیا مستندات خدمات جنبی پزشکان و دندانپزشکان به همراه صورتحساب ارسال می‌گردد؟</label>
                    <div>
                        <label><input type="radio" name="supplementary-documents" value="yes"> بلی</label>
                        <label><input type="radio" name="supplementary-documents" value="no"> خیر</label>
                    </div>
                    <textarea class="form-control" placeholder="توضیحات"></textarea>
                </div>

                <!-- Question 8 -->
                <div class="form-group">
                    <label>8- آیا رسیدگی و پرداخت خدمات تخصصی پزشکان مطابق دستورالعمل‌های ابلاغی انجام می‌گردد؟</label>
                    <div>
                        <label><input type="radio" name="specialist-service-review" value="yes"> بلی</label>
                        <label><input type="radio" name="specialist-service-review" value="no"> خیر</label>
                    </div>
                    <textarea class="form-control" placeholder="توضیحات"></textarea>
                </div>

                <!-- Question 9 -->
                <div class="form-group">
                    <label>9- آیا در رسیدگی و بررسی عملکرد پزشکان، دندانپزشکان و درمانگاه‌ها از سیستم TMDS استفاده
                        می‌گردد؟</label>
                    <div>
                        <label><input type="radio" name="tmds-system" value="yes"> بلی</label>
                        <label><input type="radio" name="tmds-system" value="no"> خیر</label>
                    </div>
                    <textarea class="form-control" placeholder="توضیحات"></textarea>
                </div>

                <!-- Question 10 -->
                <div class="form-group">
                    <label>10- آیا خدمات جنبی انجام شده در درمانگاه‌ها دارای مدارک و مستندات لازم در صورتحساب ارسالی
                        می‌باشند؟</label>
                    <div>
                        <label><input type="radio" name="clinic-documents" value="yes"> بلی</label>
                        <label><input type="radio" name="clinic-documents" value="no"> خیر</label>
                    </div>
                    <textarea class="form-control" placeholder="توضیحات"></textarea>
                </div>

                <!-- Question 11 -->
                <div class="form-group">
                    <label>11- آیا در صورتحساب ارسالی نسخ کاغذی دارای شماره کنترل مطابق با لیست می‌باشند؟</label>
                    <div>
                        <label><input type="radio" name="paper-versions-control" value="yes"> بلی</label>
                        <label><input type="radio" name="paper-versions-control" value="no"> خیر</label>
                    </div>
                </div>

                <!-- Question 12 -->
                <div class="form-group">
                    <label>12- آیا نسخ کاغذی و لیست صورتحساب‌ها مقومی و تیک زده می‌شوند؟</label>
                    <div>
                        <label><input type="radio" name="paper-versions-checked" value="yes"> بلی</label>
                        <label><input type="radio" name="paper-versions-checked" value="no"> خیر</label>
                    </div>
                    <textarea class="form-control" placeholder="توضیحات"></textarea>
                </div>

                <!-- Question 13 -->
                <div class="form-group">
                    <label>13- در مقومی نسخ کاغذی به نمونه مهر و امضای معرفی شده پزشک دقت می‌شود؟</label>
                    <div>
                        <label><input type="radio" name="doctor-signature-verification" value="yes"> بلی</label>
                        <label><input type="radio" name="doctor-signature-verification" value="no"> خیر</label>
                    </div>
                </div>

                <!-- Question 14 -->
                <div class="form-group">
                    <label>14- آیا خدمات دیالیز انجام شده در درمانگاه‌ها به صورت مکانیزه انجام می‌شود؟</label>
                    <div>
                        <label><input type="radio" name="dialysis-automated" value="yes"> بلی</label>
                        <label><input type="radio" name="dialysis-automated" value="no"> خیر</label>
                    </div>
                </div>

                <!-- Question 15 -->
                <div class="form-group">
                    <label>15- آیا عملکرد پزشکان مشمول آئین‌نامه پرداخت تمام وقتی مطابق ضوابط مربوطه بررسی و کنترل انجام
                        می‌گردد؟</label>
                    <div>
                        <label><input type="radio" name="doctor-performance-check" value="yes"> بلی</label>
                        <label><input type="radio" name="doctor-performance-check" value="no"> خیر</label>
                    </div>
                    <textarea class="form-control" placeholder="توضیحات"></textarea>
                </div>

                <!-- Question 16 -->
                <div class="form-group">
                    <label>16- نسخ خدمات دندانپزشکان بهداشت کاران در شبکه‌های روستایی بر مبنای 50% محاسبه می‌گردد؟</label>
                    <div>
                        <label><input type="radio" name="dental-50-percent" value="yes"> بلی</label>
                        <label><input type="radio" name="dental-50-percent" value="no"> خیر</label>
                    </div>
                    <textarea class="form-control" placeholder="توضیحات"></textarea>
                </div>

                <!-- Question 17 -->
                <div class="form-group">
                    <label>17- آیا عملکرد پزشکان طرف قرارداد در راستای کنترل غیرحضوری (سیستمی) بررسی و گزارش
                        می‌گردد؟</label>
                    <div>
                        <label><input type="radio" name="remote-doctor-performance" value="yes"> بلی</label>
                        <label><input type="radio" name="remote-doctor-performance" value="no"> خیر</label>
                    </div>
                    <textarea class="form-control" placeholder="توضیحات"></textarea>
                </div>

                <!-- Question 18 -->
                <div class="form-group">
                    <label>18- آیا مراکزی که رعایت ضوابط و مقررات مفاد قرارداد را نمی‌نمایند جهت پیگیری لازم انجام
                        می‌گردد؟</label>
                    <div>
                        <label><input type="radio" name="contract-follow-up" value="yes"> بلی</label>
                        <label><input type="radio" name="contract-follow-up" value="no"> خیر</label>
                    </div>
                    <textarea class="form-control"
                        placeholder="در صورت وجود ابهام در رسیدگی و موارد خارج از نرم، با رییس اداره رسیدگی مکتوب اعلام می‌گردد."></textarea>
                </div>

                <!-- Question 19 -->
                <div class="form-group">
                    <label>19- آیا آموزش، بازآموزی و اطلاع‌رسانی دستورالعمل‌ها به کارشناسان رسیدگی، مناسب می‌باشد؟</label>
                    <div>
                        <label><input type="radio" name="training-sufficient" value="yes"> بلی</label>
                        <label><input type="radio" name="training-sufficient" value="no"> خیر</label>
                    </div>
                </div>

                <!-- Question 20 -->
                <div class="form-group">
                    <label>20- آیا ارزیابی مستمر از عملکرد کارکنان واحد پزشکان انجام می‌شود؟</label>
                    <div>
                        <label><input type="radio" name="staff-performance-evaluation" value="yes"> بلی</label>
                        <label><input type="radio" name="staff-performance-evaluation" value="no"> خیر</label>
                    </div>
                    <textarea class="form-control"
                        placeholder="سایر موارد ارزیابی عملکرد واحد رسیدگی صورتحساب‌های پزشکان، دندانپزشکان و درمانگاه‌ها"></textarea>
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