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

            <h2 class="mb-4">عملکرد اداره رتبه‌بندی فراهم‌کنندگان و خرید راهبردی</h2>

            <script>
                let title = "<?php echo "کد: " . $insp_id . " , استان: " . $provinceName . " , تاریخ: " . $inspDate . " , وضعیت: " . $inspStatus; ?>";
                document.write(`<h4 class="mb-4">( ${title} )</h4>`);
            </script>

            <!-- <hr style="border: 1px solid black; width: 45%;"> -->
            <br>
            <br>

            <form id="myForm" action="submit_form.php" method="post">


                <!-- Section 1 -->
                <div class="form-section">

                    <div class="section-title">1) عملکرد اداره رتبه‌بندی فراهم‌کنندگان و خرید راهبردی</div>

                    <div class="form-group">
                        <label>1- تعداد مراکز (واحدهای) طرف قرارداد استان
                            <input type="text" name="province_centers" placeholder="تعداد" style="width: 50px;"> مرکز
                            می‌باشد.
                        </label><br>
                        <br>
                        <label>توضیحات: مراکز طرف قرارداد به تفکیک نوع مرکز شامل:
                            <input type="text" name="doctors_dentists" placeholder="تعداد" style="width: 50px;"> پزشک و
                            دندانپزشک،
                            <input type="text" name="pharmacies" placeholder="تعداد" style="width: 50px;"> داروخانه،
                            <input type="text" name="labs" placeholder="تعداد" style="width: 50px;"> آزمایشگاه،
                            <input type="text" name="radiology_units" placeholder="تعداد" style="width: 50px;"> واحد
                            رادیولوژی،
                            <input type="text" name="ultrasound_units" placeholder="تعداد" style="width: 50px;"> واحد
                            سونوگرافی،
                            <input type="text" name="ct_scan_units" placeholder="تعداد" style="width: 50px;"> واحد سی تی
                            اسکن،
                            <input type="text" name="mri_units" placeholder="تعداد" style="width: 50px;"> واحد MRI،
                            <input type="text" name="bmd_units" placeholder="تعداد" style="width: 50px;"> واحد BMD،
                            <input type="text" name="nuclear_medicine_centers" placeholder="تعداد" style="width: 50px;">
                            مرکز پزشکی هسته‌ای،
                            <input type="text" name="radiotherapy_centers" placeholder="تعداد" style="width: 50px;">
                            مرکز رادیوتراپی،
                            <input type="text" name="physiotherapy_centers" placeholder="تعداد" style="width: 50px;">
                            مرکز فیزیوتراپی،
                            <input type="text" name="dialysis_centers" placeholder="تعداد" style="width: 50px;"> مرکز
                            دیالیز،
                            <input type="text" name="lithotripsy_centers" placeholder="تعداد" style="width: 50px;"> مرکز
                            سنگ شکن،
                            <input type="text" name="health_centers" placeholder="تعداد" style="width: 50px;"> مرکز
                            بهداشت،
                            <input type="text" name="clinics" placeholder="تعداد" style="width: 50px;"> درمانگاه،
                            <input type="text" name="limited_surgery_centers" placeholder="تعداد" style="width: 50px;">
                            مرکز جراحی محدود و
                            <input type="text" name="hospitals" placeholder="تعداد" style="width: 50px;"> بیمارستان
                        </label><br>

                        <br>
                        <fieldset>
                            <label>2- تعداد پرسنل اداره رتبه‌بندی و خرید راهبردی</label>
                            <label for="personnel-count">تعداد پرسنل:</label>
                            <input type="number" id="personnel-count" name="personnel-count" required>
                            <span>نفر می‌باشند.</span>
                        </fieldset>

                        <br>
                        <fieldset>
                            <label>3- آیا نسبت پرسنل شاغل در اداره رتبه‌بندی و خرید راهبردی به حجم کار واحد کافی است؟
                            </label>
                            <label>
                                <input type="radio" name="sufficient-personnel" value="yes" required>
                                بلی
                            </label>
                            <label>
                                <input type="radio" name="sufficient-personnel" value="no">
                                خیر
                            </label>
                            <p>توضیحات: با توجه به وظایف مختلف محوله شده به این اداره از جمله پاسخگویی به آمار و
                                اطلاعات، تحلیل هزینه‌ها، کمیته‌های عقد و فسخ قرارداد‌ها و نظارت بر فیشیه ضروری است
                                کارشناس متخصص به این اداره اختصاص یابد.</p>
                        </fieldset>

                    </div>

                </div>

                <!-- Section 2 -->
                <div class="form-section">

                    <div class="section-title">1-1) عملکرد کمیته‌های عقد و فسخ قرارداد</div>

                    <fieldset>
                        <label>1- آیا کمیته عقد قرارداد بر اساس آخرین ضابطه و بخشنامه ابلاغی تشکیل می‌گردد؟</label>
                        <label>
                            <input type="radio" name="contract-committee-formation" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="contract-committee-formation" value="no">
                            خیر
                        </label>
                    </fieldset>

                    <fieldset>
                        <label>2- آیا کمیته فسخ قرارداد بر اساس آخرین ضابطه و بخشنامه ابلاغی تشکیل می‌گردد؟</label>
                        <label>
                            <input type="radio" name="contract-termination-committee-formation" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="contract-termination-committee-formation" value="no">
                            خیر
                        </label>
                    </fieldset>

                    <fieldset>
                        <label>3- آیا تاریخ عقد قرارداد با توجه به تاریخ تشکیل جلسه کمیته عقد و لغو قرارداد رعایت
                            می‌شود؟</label>
                        <label>
                            <input type="radio" name="contract-date-compliance" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="contract-date-compliance" value="no">
                            خیر
                        </label>
                    </fieldset>

                    <fieldset>
                        <label>4- آیا صورتجلسات کمیته عقد قرارداد به تفکیک واحدها مطابق دستورالعمل ابلاغی تنظیم
                            می‌گردد؟</label>
                        <label>
                            <input type="radio" name="minutes-organization" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="minutes-organization" value="no">
                            خیر
                        </label>
                    </fieldset>

                    <fieldset>
                        <label>5- آیا دلایل موافقت یا عدم موافقت با عقد قرارداد با مؤسسه در صورتجلسات درج می‌گردد؟
                        </label>
                        <label>
                            <input type="radio" name="agreement-reason-notation" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="agreement-reason-notation" value="no">
                            خیر
                        </label>
                    </fieldset>

                    <fieldset>
                        <label>6- مکاتبات لازم در موعد مقرر در خصوص عقد، تعلیق یا فسخ قرارداد صورت می‌گیرد؟</label>
                        <label>
                            <input type="radio" name="correspondence-timeliness" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="correspondence-timeliness" value="no">
                            خیر
                        </label>
                    </fieldset>

                    <fieldset>
                        <label>7- آیا کنترل های لازم در خصوص مدارک مورد نیاز جهت عقد قرارداد صورت می‌گیرد؟</label>
                        <label>
                            <input type="radio" name="document-control" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="document-control" value="no">
                            خیر
                        </label>
                    </fieldset>

                    <fieldset>
                        <label>8- آیا انعقاد قرارداد با مؤسسات بر اساس بخش‌ها/ واحدهای مجاز مندرج در پروانه بهره‌برداری
                            یا مجوز فعالیت انجام می‌گردد؟</label>
                        <label>
                            <input type="radio" name="contract-with-institutions" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="contract-with-institutions" value="no">
                            خیر
                        </label>
                    </fieldset>

                    <fieldset>
                        <label>9- آیا توسعه قرارداد بر اساس نامه شماره 38977/4020د مورخ 15/12/1395 وزارت بهداشت رعایت
                            شده است؟</label>
                        <label>
                            <input type="radio" name="contract-development-compliance" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="contract-development-compliance" value="no">
                            خیر
                        </label>
                        <p>یادآوری: مفاد نامه شماره 38977/4020د مورخ 15/12/1395 وزارت بهداشت: پس از صدور پروانه
                            بهره‌برداری و مسئولین فنی مؤسسات پزشکی، هر گونه افزایش بخش بالینی و پاراکلینیک و همچنین
                            تجهیزات پزشکی تملک و دارایی سنگین و مشمول نظام سطح بندی، نیازمند اخذ مجوز از وزارت متبوع
                            می‌باشد.</p>
                        <br>
                    </fieldset>

                    <fieldset>
                        <label>10- مدت زمان اتخاذ تصمیم از زمان درخواست متقاضیان تا تاریخ طرح در کمیته رعایت می‌شود؟
                        </label>
                        <label>
                            <input type="radio" name="decision-timeliness" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="decision-timeliness" value="no">
                            خیر
                        </label>
                    </fieldset>

                    <fieldset>
                        <label>11- مدت زمان پاسخگویی به درخواست متقاضیان که در کمیته طرح گردیده (حداکثر ده روز) رعایت
                            می‌شود؟</label>
                        <label>
                            <input type="radio" name="response-timeliness" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="response-timeliness" value="no">
                            خیر
                        </label>
                        <p>توضیحات:</p><br>
                        <textarea class="form-control" name="desc01" rows="3"></textarea>
                    </fieldset>

                </div>

                <!-- Section 3 -->
                <div class="form-section">

                    <div class="section-title">2-1) پرونده های مراکز طرف قرارد اد</div>

                    <fieldset>
                        <label>1- آیا وضعیت نگهداری پرونده‌های مدارک مراکز طرف قرارداد مناسب می‌باشد؟</label><br>
                        <label>
                            <input type="radio" name="file-maintenance-status" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="file-maintenance-status" value="no">
                            خیر
                        </label><br>
                        <label>وضعیت نگهداری پرونده‌ها:</label><br>
                        <label>
                            <input type="radio" name="file-maintenance-type" value="centralized" required>
                            متمرکز
                        </label>
                        <label>
                            <input type="radio" name="file-maintenance-type" value="decentralized">
                            مستقر در واحدها
                        </label>
                        <label>
                            <input type="radio" name="file-maintenance-type" value="numbered">
                            دارای شماره‌گذاری
                        </label><br>
                        <label>توضیحات:</label><br>
                        <textarea class="form-control" name="file-maintenance-notes" rows="3"
                            placeholder="توضیحات خود را اینجا بنویسید..."></textarea>
                    </fieldset>

                    <fieldset>
                        <label>2- آیا تفکیک محتویات در پرونده ها انجام می‌گردد؟ (چک لیست محتویات، مدارک اصلی، مکاتبات و
                            ...)</label><br>
                        <label>الف) پرونده‌های پزشکان و دندانپزشکان مستقل</label><br>
                        <label>
                            <input type="radio" name="doctor-files-separation" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="doctor-files-separation" value="no">
                            خیر
                        </label><br>
                        <label>ب) پرونده‌های داروخانه‌ها</label><br>
                        <label>
                            <input type="radio" name="pharmacy-files-separation" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="pharmacy-files-separation" value="no">
                            خیر
                        </label><br>
                        <label>ج) پرونده‌های مؤسسات پاراکلینیک</label><br>
                        <label>
                            <input type="radio" name="paraclinic-files-separation" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="paraclinic-files-separation" value="no">
                            خیر
                        </label><br>
                        <label>د) پرونده‌های درمانگاه‌ها</label><br>
                        <label>
                            <input type="radio" name="clinic-files-separation" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="clinic-files-separation" value="no">
                            خیر
                        </label><br>
                        <label>ه) پرونده‌های بیمارستان‌ها و مراکز جراحی محدود</label><br>
                        <label>
                            <input type="radio" name="hospital-files-separation" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="hospital-files-separation" value="no">
                            خیر
                        </label><br>
                        <label>توضیحات:</label><br>
                        <textarea class="form-control" name="files-separation-notes" rows="3"
                            placeholder="توضیحات خود را اینجا بنویسید..."></textarea>
                    </fieldset>

                    <fieldset>
                        <label>3- آیا فرآیند برگ شماری با زدن مهر، درج تاریخ، نام و امضای کارشناس در بایگانی مدارک و
                            محتویات پرونده انجام می‌گردد؟</label><br>
                        <label>
                            <input type="radio" name="document-archiving-process" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="document-archiving-process" value="no">
                            خیر
                        </label><br>
                        <label>توضیحات:</label><br>
                        <textarea class="form-control" name="document-archiving-notes" rows="3"
                            placeholder="توضیحات خود را اینجا بنویسید..."></textarea>
                    </fieldset>

                    <fieldset>
                        <label>4- آیا مدارک و مستندات لازم جهت ادامه قرارداد همکاری مرکز موجود و به روزرسانی شده
                            است؟</label><br>
                        <label>
                            <input type="radio" name="documents-updated" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="documents-updated" value="no">
                            خیر
                        </label><br>
                        <label>توضیحات:</label><br>
                        <textarea class="form-control" name="documents-updated-notes" rows="3"
                            placeholder="توضیحات خود را اینجا بنویسید..."></textarea>
                    </fieldset>

                </div>



                <!-- Section 4 -->
                <div class="form-section">

                    <div class="section-title">3-1) فیشیه مراکز درمانی در سیستم جامع اسناد پزشکی (TMDS)</div>

                    <fieldset>
                        <label>1- آیا تفکیک مرکز با نوع خدمات ارائه آن در سیستم قابل شناسایی می باشد؟</label><br>
                        <label>
                            <input type="radio" name="service-identification" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="service-identification" value="no">
                            خیر
                        </label>
                    </fieldset>

                    <fieldset>
                        <label>2- آیا فیشیه مراکز طرف قرارداد در سیستم جامع اسناد پزشکی (TMDS) کامل است؟ (به روزرسانی
                            اطلاعات پروانه‌ها، تصمیمات و تغییرات در وضعیت قرارداد، نوع خدمات جنبی و سقف ویزیت و خدمات
                            جنبی درج شده باشد)</label><br>
                        <label>الف) فیشیه پزشکان و دندانپزشکان مستقل</label><br>
                        <label>
                            <input type="radio" name="doctor-fees-completeness" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="doctor-fees-completeness" value="no">
                            خیر
                        </label><br>
                        <label>ب) فیشیه داروخانه‌ها</label><br>
                        <label>
                            <input type="radio" name="pharmacy-fees-completeness" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="pharmacy-fees-completeness" value="no">
                            خیر
                        </label><br>
                        <label>ج) فیشیه مؤسسات پاراکلینیک</label><br>
                        <label>
                            <input type="radio" name="paraclinic-fees-completeness" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="paraclinic-fees-completeness" value="no">
                            خیر
                        </label><br>
                        <label>د) فیشیه درمانگاه‌ها</label><br>
                        <label>
                            <input type="radio" name="clinic-fees-completeness" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="clinic-fees-completeness" value="no">
                            خیر
                        </label><br>
                        <label>ه) فیشیه بیمارستان‌ها و مراکز جراحی محدود</label><br>
                        <label>
                            <input type="radio" name="hospital-fees-completeness" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="hospital-fees-completeness" value="no">
                            خیر
                        </label><br>
                        <label>توضیحات:</label><br>
                        <textarea class="form-control" name="fees-completeness-notes" rows="3"
                            placeholder="توضیحات خود را اینجا بنویسید..."></textarea>
                    </fieldset>

                    <fieldset>
                        <label>3- آیا اطلاعات ثبت شده در سیستم با مندرجات پروانه‌های تأسیس (بهره‌برداری) و مسئول/
                            مسئولین فنی مطابقت دارد؟ (مالکیت، تاریخ اعتبار و ...)</label><br>
                        <label>
                            <input type="radio" name="system-data-compliance" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="system-data-compliance" value="no">
                            خیر
                        </label><br>
                        <label>توضیحات:</label><br>
                        <textarea class="form-control" name="system-data-compliance-notes" rows="3"
                            placeholder="توضیحات خود را اینجا بنویسید..."></textarea>
                    </fieldset>

                    <fieldset>
                        <label>4- آیا خدمات قابل ارائه با مستندات معتبر پرونده مطابقت دارد؟</label><br>
                        <label>
                            <input type="radio" name="services-documentation-compliance" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="services-documentation-compliance" value="no">
                            خیر
                        </label><br>
                        <label>توضیحات:</label><br>
                        <textarea class="form-control" name="services-documentation-compliance-notes" rows="3"
                            placeholder="توضیحات خود را اینجا بنویسید..."></textarea>
                    </fieldset>

                    <fieldset>
                        <label>5- آیا کدهای خدمات قابل ارائه در مرکز مطابق دستورالعمل ها و بخشنامه های ابلاغی اجرا
                            می‌شود؟</label><br>
                        <label>
                            <input type="radio" name="service-codes-compliance" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="service-codes-compliance" value="no">
                            خیر
                        </label><br>
                        <label>توضیحات:</label><br>
                        <textarea class="form-control" name="service-codes-compliance-notes" rows="3"
                            placeholder="توضیحات خود را اینجا بنویسید..."></textarea>
                    </fieldset>

                    <fieldset>
                        <label>6- آیا پزشکان تمام وقت مطابق احکام کارگزینی و نامه های ارسالی دانشگاه علوم پزشکی مربوطه
                            ثبت شده است؟</label><br>
                        <label>
                            <input type="radio" name="full-time-doctors-registration" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="full-time-doctors-registration" value="no">
                            خیر
                        </label><br>
                        <label>توضیحات:</label><br>
                        <textarea class="form-control" name="full-time-doctors-registration-notes" rows="3"
                            placeholder="توضیحات خود را اینجا بنویسید..."></textarea>
                    </fieldset>

                    <fieldset>
                        <label>7- آیا اطلاعات تصمیمات مربوط به تعیین سقف ریالی/ تعدادی برای مراکز در سیستم ثبت
                            می‌گردد؟</label><br>
                        <label>
                            <input type="radio" name="financial-capacity-recording" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="financial-capacity-recording" value="no">
                            خیر
                        </label><br>
                        <label>توضیحات:</label><br>
                        <textarea class="form-control" name="financial-capacity-recording-notes" rows="3"
                            placeholder="توضیحات خود را اینجا بنویسید..."></textarea>
                    </fieldset>

                    <fieldset>
                        <label>8- آیا سقف ریالی و تعدادی در سیستم به درستی تعریف شده است؟</label><br>
                        <label>
                            <input type="radio" name="financial-capacity-definition" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="financial-capacity-definition" value="no">
                            خیر
                        </label><br>
                        <label>توضیحات:</label><br>
                        <textarea class="form-control" name="financial-capacity-definition-notes" rows="3"
                            placeholder="توضیحات خود را اینجا بنویسید..."></textarea>
                    </fieldset>

                    <fieldset>
                        <label>9- آیا اطلاعات تصمیمات مربوط به وضعیت قرارداد مراکز در سیستم ثبت می‌گردد؟</label><br>
                        <label>
                            <input type="radio" name="contract-status-recording" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="contract-status-recording" value="no">
                            خیر
                        </label><br>
                        <label>توضیحات:</label><br>
                        <textarea class="form-control" name="contract-status-recording-notes" rows="3"
                            placeholder="توضیحات خود را اینجا بنویسید..."></textarea>
                    </fieldset>

                    <fieldset>
                        <label>10- آیا در خصوص مراکز طرف قرارداد فاقد عملکرد، مطابق ضوابط گزارش‌دهی و تصمیم‌گیری به عمل
                            می‌آید؟</label><br>
                        <label>
                            <input type="radio" name="non-performing-centers-reporting" value="yes" required>
                            بلی
                        </label>
                        <label>
                            <input type="radio" name="non-performing-centers-reporting" value="no">
                            خیر
                        </label><br>
                        <label>توضیحات:</label><br>
                        <textarea class="form-control" name="non-performing-centers-reporting-notes" rows="3"
                            placeholder="توضیحات خود را اینجا بنویسید..."></textarea>
                    </fieldset>

                    <fieldset>
                        <label>سایر موارد ارزیابی عملکرد اداره رتبه‌بندی فراهم‌کنندگان و خرید راهبردی:</label><br>
                        <textarea class="form-control" name="performance-evaluation-notes" rows="4"
                            placeholder="توضیحات خود را اینجا بنویسید..."></textarea>
                    </fieldset>

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