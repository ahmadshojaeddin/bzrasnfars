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
        $stmt = $conn->prepare("UPDATE setad.inspections SET f11_khesarat_json=? WHERE id=?;");
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
    $stmt = $conn->prepare("SELECT f11_khesarat_json, st.name_ as state_name, date_, insst.desc_ as status_desc 
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

            fetch('/setad/php/inspections/forms/f11-khesarat.php', {

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

            <div class="section-title">11) عملکرد واحد رسیدگی صورتحساب‌های خسارت متفرقه</div>

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
                    <label>1- عملکرد واحد رسیدگی صورتحساب‌های خسارت متفرقه در سال 1402 به شرح زیر می‌باشد.</label>
                    <br>
                    <br>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>نوع پرونده</th>
                                <th>بستری</th>
                                <th>گلوبال</th>
                                <th>سرپایی</th>
                                <th>درمان ناباروری</th>
                                <th>جمع کل</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>تعداد پرونده یک ساله</td>
                                <td><input type="text" name="row1col1" class="form-control"></td>
                                <td><input type="text" name="row1col2" class="form-control"></td>
                                <td><input type="text" name="row1col3" class="form-control"></td>
                                <td><input type="text" name="row1col4" class="form-control"></td>
                                <td><input type="text" name="row1col5" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>متوسط ماهیانه</td>
                                <td><input type="text" name="row2col1" class="form-control"></td>
                                <td><input type="text" name="row2col2" class="form-control"></td>
                                <td><input type="text" name="row2col3" class="form-control"></td>
                                <td><input type="text" name="row2col4" class="form-control"></td>
                                <td><input type="text" name="row2col5" class="form-control"></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <br>
                </div>


                <div>
                    <label>2- تعداد پرسنل واحد رسیدگی صورتحساب‌های خسارت متفرقه</label>
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
                    <label>4- آیا نحوه ارائه خدمات کارگزاری‌ها در امر پذیرش و ثبت اطلاعات بیماران در حد مطلوب
                        می‌باشد؟</label>
                    <label><input type="radio" name="question4" value="yes"> بلی</label>
                    <label><input type="radio" name="question4" value="no"> خیر</label>
                </div>

                <div>
                    <label>5- آیا در کارگزاری‌ها؛ عملیات پذیرش مدارک و ثبت اطلاعات بیمه ای، هویتی و حساب بانکی بیمه
                        شدگان و مشخصات پرونده بیماردر سامانه بازپرداخت هزینه‌های درمانی خسارت متفرقه (TCR) به درستی صورت
                        گرفته است؟</label>
                    <label><input type="radio" name="question5" value="yes"> بلی</label>
                    <label><input type="radio" name="question5" value="no"> خیر</label>
                </div>

                <div>
                    <label>6- آیا در واحد خسارت متفرقه و ممیزی؛ اطلاعات بیمه‌ای، هویتی و حساب بانکی بیمه‌شدگان و اطلاعات
                        پرونده بیماردر سامانه بازپرداخت هزینه های درمانی خسارت متفرقه (TCR) به درستی صورت ثبت شده
                        است؟</label>
                    <label><input type="radio" name="question6" value="yes"> بلی</label>
                    <label><input type="radio" name="question6" value="no"> خیر</label>
                </div>

                <div>
                    <label>7- دستور العمل بیمه مکمل ( ضریب 1.2) رعایت شده است؟</label>
                    <label><input type="radio" name="question7" value="yes"> بلی</label>
                    <label><input type="radio" name="question7" value="no"> خیر</label>
                </div>

                <div>
                    <label>8- هزینه های بیمه‌شدگان اعم از ویزیت، مشاوره، کدهای جراحی، کمک جراح، بیهوشی، کدهای تعدیلی،
                        هزینه اتاق عمل، خدمات پاراکلینیک و دارو بر اساس ضوابط و مقررات پرداخت شده است؟</label>
                    <label><input type="radio" name="question8" value="yes"> بلی</label>
                    <label><input type="radio" name="question8" value="no"> خیر</label>
                </div>

                <div>
                    <label>9- در صورتحساب‌های پرداختی، لوازم مصرفی و تجهیزات پزشکی، بر اساس آخرین بخشنامه ( بخشنامه
                        شماره 1302/1400/4020 مورخ 12/07/1400) و سایر بخشنامه‌های مرتبط اقدام شده است؟</label>
                    <label><input type="radio" name="question9" value="yes"> بلی</label>
                    <label><input type="radio" name="question9" value="no"> خیر</label>
                </div>

                <div>
                    <label>10- در پرداخت اعمال گلوبال، در مواردی که درمان بیمار در بیمارستان نیازمند مصرف پروتز مانند
                        پیچ، پلاک، لنز و مش و ... باشد هزینه آن به قیمت سرجمع اضافه گردیده است؟</label>
                    <label><input type="radio" name="question10" value="yes"> بلی</label>
                    <label><input type="radio" name="question10" value="no"> خیر</label>
                </div>

                <div>
                    <label>11- آیا در پرداخت اعمال گلوبال، آئین نامه اجرایی گلوبال رعایت شده است؟</label>
                    <label><input type="radio" name="question11" value="yes"> بلی</label>
                    <label><input type="radio" name="question11" value="no"> خیر</label>
                </div>

                <div>
                    <label>12- در پرداخت اعمال گلوبال، کدهای تعدیلی (39)، (63)، (85)، (90) و (95) در صورت احراز به سرجمع
                        تعرفه خدمت اضافه گردیده است؟</label>
                    <label><input type="radio" name="question12" value="yes"> بلی</label>
                    <label><input type="radio" name="question12" value="no"> خیر</label>
                </div>

                <div>
                    <label>13- آیا نظر پزشک مشاور معاونت خرید راهبردی اعمال گردیده است ؟</label>
                    <label><input type="radio" name="question13" value="yes"> بلی</label>
                    <label><input type="radio" name="question13" value="no"> خیر</label>
                </div>

                <div>
                    <label>14- آیا بخشنامه نحوه ارائه هزینه درمان بیماران فوتی (شماره 5404/98/1000 مورخ 22/04/1398) با
                        لحاظ کسر سهم بیمه تکمیلی اجرا می‌گردد؟</label>
                    <label><input type="radio" name="question14" value="yes"> بلی</label>
                    <label><input type="radio" name="question14" value="no"> خیر</label>
                </div>

                <div>
                    <label>15- آیا دستورالعمل هزینه درمان بیماران بستری بخش‌های ویژه که به دستگاه تنفس دهنده مکانیکی
                        (ونتیلاتور) وصل می‌شوند (شماره 7906/98/1000 مورخ 20/06/1398) با لحاظ کسر سهم بیمه تکمیلی اجرا
                        می‌گردد؟</label>
                    <label><input type="radio" name="question15" value="yes"> بلی</label>
                    <label><input type="radio" name="question15" value="no"> خیر</label>
                </div>

                <div>
                    <label>16- آیا دستورالعمل 1770/96/4020 مورخ 15/08/1396 در خصوص پرداخت هزینه ملزومات مصرفی شیمی
                        درمانی تا سقف K 1/5 با فرانشیز صفر اجرا می‌گردد؟</label>
                    <label><input type="radio" name="question16" value="yes"> بلی</label>
                    <label><input type="radio" name="question16" value="no"> خیر</label>
                </div>

                <div>
                    <label>17- پرداخت لوازم و پروتزهای مصرفی بر اساس فاکتورهای رسمی (دارای کد اقتصادی، آدرس و ...) می
                        باشد. دستورالعمل 392/94/4020 مورخ 05/03/1394 رعایت شده است؟</label>
                    <label><input type="radio" name="question17" value="yes"> بلی</label>
                    <label><input type="radio" name="question17" value="no"> خیر</label>
                </div>

                <div>
                    <label>18- آیا بازپرداخت هزینه‌های درمانی در بخش خدمات سرپائی از طریق واحد خسارت متفرقه بر اساس
                        بخشنامه شماره 2349/97/4020 مورخ 23/11/1397 انجام شده است؟</label>
                    <label><input type="radio" name="question18" value="yes"> بلی</label>
                    <label><input type="radio" name="question18" value="no"> خیر</label>
                </div>

                <div>
                    <label>19- آیا جهت بیمارانی که برای دریافت هزینه کیسه‌های کلستومی و یوروستومی مراجعه می‌نمایند بر
                        اساس بخشنامه شماره 345/96/4020 مورخ 26/02/1396 اقدام شده است؟</label>
                    <label><input type="radio" name="question19" value="yes"> بلی</label>
                    <label><input type="radio" name="question19" value="no"> خیر</label>
                </div>

                <div>
                    <label>20- آیا ضوابط و دستورالعمل های مربوط به پرداخت هزینه های درمان ناباروری رعایت شده
                        است؟</label>
                    <label><input type="radio" name="question20" value="yes"> بلی</label>
                    <label><input type="radio" name="question20" value="no"> خیر</label>
                </div>

                <div>
                    <label>21- آیا ضوابط و دستورالعمل های رسیدگی به اسناد تعرفه های بسته خدمات و مراقبت های پرستاری
                        رعایت شده است؟</label>
                    <label><input type="radio" name="question21" value="yes"> بلی</label>
                    <label><input type="radio" name="question21" value="no"> خیر</label>
                </div>

                <div>
                    <label>22- آیا ضوابط و دستورالعمل های مربوط به پرداخت هزینه های پرتودرمانی ( رادیوتراپی ،IORT ، IMRT
                        و ...) رعایت شده است؟</label>
                    <label><input type="radio" name="question22" value="yes"> بلی</label>
                    <label><input type="radio" name="question22" value="no"> خیر</label>
                </div>


                <!-- Random Patient Samples -->
                <br>
                <br>
                <label for=""><b>سایر موارد عملکرد واحد رسیدگی صورتحساب‌های خسارت متفرقه:</b></label>
                <br>
                <label for="">در نمونه‌های راندوم بررسی شده در برخی پرونده‌ها موارد زیر مشاهده گردید:</label>

                <!-- Patient #1 -->
                <br>
                <br>
                <label for="">بیمار نمونه 1: </label>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>نام بیمار</th>
                            <th>کد ملی</th>
                            <th>شماره پذیرش</th>
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
                            <th>شماره پذیرش</th>
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
                            <th>شماره پذیرش</th>
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
                            <th>شماره پذیرش</th>
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
                            <th>شماره پذیرش</th>
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