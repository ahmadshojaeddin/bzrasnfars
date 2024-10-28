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
        $stmt = $conn->prepare("UPDATE setad.inspections SET f12_mali_json=? WHERE id=?;");
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
    $stmt = $conn->prepare("SELECT f12_mali_json, st.name_ as state_name, date_, insst.desc_ as status_desc 
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

            fetch('/setad/php/inspections/forms/f12-mali.php', {

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

            <div class="section-title mb-0">12) عملکرد اداره امور مالی و ممیزی</div>
            <div class="section-title" style="background-color: gold;">1-12) عملکرد واحد ممیزی و پرداخت اسناد مراکز طرف
                قرارداد</div>

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

                <div>
                    <label>1- در واحد ممیزی و پرداخت اسناد ماهیانه بطور متوسط <input type="text" name="question1_count"
                            placeholder="........."></label>
                    <label>سند ممیزی و لیست‌بندی می‌گردد.</label>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question1_notes"></textarea>
                    <label>سند پزشکان <input type="text" name="question1_doctor"></label>
                    <label>سند بیمارستان <input type="text" name="question1_hospital"></label>
                    <label>سند درمانگاه <input type="text" name="question1_clinic"></label>
                    <label>سند داروخانه <input type="text" name="question1_pharmacy"></label>
                    <label>سند پاراکلینیک <input type="text" name="question1_paraclinic"></label>
                    <label>سند خسارت متفرقه <input type="text" name="question1_misc"></label>
                </div>

                <div>
                    <label>2- تعداد پرسنل واحد ممیزی، حسابداری و پرداخت اسناد <input type="text" name="question2_count"
                            placeholder="......"> نفر کارشناس می‌باشد.</label>
                </div>

                <div>
                    <label>3- آیا نسبت پرسنل شاغل در واحد ممیزی و پرداخت اسناد به حجم کار کافی است؟</label>
                    <label><input type="radio" name="question3" value="yes"> بلی</label>
                    <label><input type="radio" name="question3" value="no"> خیر</label>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question3_notes"></textarea>
                </div>

                <div>
                    <label>4- آیا صحت شماره حساب مؤسسات طرف قرارداد توسط حسابدار امور مالی کنترل می‌گردد؟</label>
                    <label><input type="radio" name="question4" value="yes"> بلی</label>
                    <label><input type="radio" name="question4" value="no"> خیر</label>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question4_notes"></textarea>
                </div>

                <div>
                    <label>5- آیا ممیزی اسناد مطابق ضوابط و دستورالعمل‌های ابلاغی انجام می‌گردد؟</label>
                    <label><input type="radio" name="question5" value="yes"> بلی</label>
                    <label><input type="radio" name="question5" value="no"> خیر</label>
                </div>

                <div>
                    <label>6- مدت زمان کارشناسی ممیزی و تائید مسئول ممیزی در بازه زمانی تعیین شده انجام می‌گردد؟</label>
                    <label><input type="radio" name="question6" value="yes"> بلی</label>
                    <label><input type="radio" name="question6" value="no"> خیر</label>
                </div>

                <div>
                    <label>7- آیا مراکز طرف قرارداد دارای محدودیت سقف تعدادی/ ریالی کنترل می‌گردند؟</label>
                    <label><input type="radio" name="question7" value="yes"> بلی</label>
                    <label><input type="radio" name="question7" value="no"> خیر</label>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question7_notes"></textarea>
                </div>

                <div>
                    <label>8- آیا تصمیمات کمیته فنی در خصوص جریمه‌های مالی مراکز متخلف اعمال می‌گردد؟</label>
                    <label><input type="radio" name="question8" value="yes"> بلی</label>
                    <label><input type="radio" name="question8" value="no"> خیر</label>
                </div>

                <div>
                    <label>9- عملکرد مسئول واحد ممیزی</label>
                </div>

                <div>
                    <label>1-9- آیا آموزش، بازآموزی و اطلاع‌رسانی دستورالعمل‌ها به کارشناسان ممیزی، مناسب
                        می‌باشد؟</label>
                    <label><input type="radio" name="question9_1" value="yes"> بلی</label>
                    <label><input type="radio" name="question9_1" value="no"> خیر</label>
                </div>

                <div>
                    <label>2-9- آیا فرآیندی برای کنترل اسناد ویژه (از نظر مبلغ درخواستی، نوع خدمت و ...) وجود
                        دارد؟</label>
                    <label><input type="radio" name="question9_2" value="yes"> بلی</label>
                    <label><input type="radio" name="question9_2" value="no"> خیر</label>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question9_2_notes"></textarea>
                </div>

                <div>
                    <label>3-9- آیا در صورت مغایرت نتایج رسیدگی و ممیزی؛ گزارش عودت اسناد به اداره رسیدگی مستندسازی
                        می‌گردد؟</label>
                    <label><input type="radio" name="question9_3" value="yes"> بلی</label>
                    <label><input type="radio" name="question9_3" value="no"> خیر</label>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question9_3_notes"></textarea>
                </div>


                <!-- Part 2 -->
                <br>
                <br>
                <div class="section-title mb-0">2-12) عملکرد واحد مالی و صدور چک اسناد مراکز طرف قرارداد</div>
                <br>
                <br>

                <div>
                    <label>1- در واحد مالی و صدور چک اسناد ماهیانه بطور متوسط <input type="text" name="question1_check"
                            placeholder="......"> چک صادر و پرداخت می‌گردد.</label>
                </div>

                <div>
                    <label>2- تعداد پرسنل واحد مالی و صدور چک <input type="text" name="question2_count"
                            placeholder="......"> نفر کارشناس می‌باشد.</label>
                </div>

                <div>
                    <label>3- آیا نسبت پرسنل شاغل در واحد مالی و صدور چک اسناد به حجم کار کافی است؟</label>
                    <label><input type="radio" name="question3" value="yes"> بلی</label>
                    <label><input type="radio" name="question3" value="no"> خیر</label>
                </div>

                <div>
                    <label>4- آیا صحت شماره حساب مؤسسات طرف قرارداد توسط رئیس امور مالی کنترل می‌گردد؟</label>
                    <label><input type="radio" name="question4" value="yes"> بلی</label>
                    <label><input type="radio" name="question4" value="no"> خیر</label>
                </div>

                <div>
                    <label>5- آیا ممیزی اسناد و صورتحساب‌ها مطابق ضوابط و دستورالعمل‌های ابلاغی انجام می‌گردد ؟</label>
                    <label><input type="radio" name="question5" value="yes"> بلی</label>
                    <label><input type="radio" name="question5" value="no"> خیر</label>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question5_notes"></textarea>
                </div>

                <div>
                    <label>6- آیا پرداخت اسناد مطابق دستور تعیین و ابلاغ شده انجام می‌گردد؟</label>
                    <label><input type="radio" name="question6" value="yes"> بلی</label>
                    <label><input type="radio" name="question6" value="no"> خیر</label>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question6_notes"></textarea>
                </div>

                <div>
                    <label>7- آیا مانده مطالبات پرداخت نشده مشخص می‌باشد؟</label>
                    <label><input type="radio" name="question7" value="yes"> بلی</label>
                    <label><input type="radio" name="question7" value="no"> خیر</label>
                </div>

                <div>
                    <label>8- آیا ثبت چک‌های صادر شده در سیستم جامع اسناد پزشکی TMDS صورت می گیرد ؟</label>
                    <label><input type="radio" name="question8" value="yes"> بلی</label>
                    <label><input type="radio" name="question8" value="no"> خیر</label>
                </div>

                <div>
                    <label>9- عملکرد مسئول واحد مالی</label>
                </div>

                <div>
                    <label>1-9- آیا آموزش، بازآموزی و اطلاع‌رسانی دستورالعمل‌ها به کارشناسان مرتبط داده می‌شود؟</label>
                    <label><input type="radio" name="question9_1" value="yes"> بلی</label>
                    <label><input type="radio" name="question9_1" value="no"> خیر</label>
                </div>

                <div>
                    <label>2-9- آیا فرآیندی برای کنترل اسناد ویژه (از نظر مبلغ درخواستی، نوع خدمت و ...) وجود
                        دارد؟</label>
                    <label><input type="radio" name="question9_2" value="yes"> بلی</label>
                    <label><input type="radio" name="question9_2" value="no"> خیر</label>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question9_2_notes"></textarea>
                </div>

                <div>
                    <label>3-9- آیا پایش هزینه های مراکز طرف قرارداد مطابق فرآیند صورت می‌گیرد؟</label>
                    <label><input type="radio" name="question9_3" value="yes"> بلی</label>
                    <label><input type="radio" name="question9_3" value="no"> خیر</label>
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="question9_3_notes"></textarea>
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