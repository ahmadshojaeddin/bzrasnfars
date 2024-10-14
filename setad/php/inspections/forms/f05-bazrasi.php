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
    </style>
</head>

<div class="row">

    <div class="col-2"> </div>
    <div class="col-8">
        <div class="container mt-5">

            <h2 class="mb-4">عملکرد اداره نظارت و بازرسی</h2>
            <!-- <hr style="border: 1px solid black; width: 45%;"> -->
            <br>
            <br>

            <form id="myForm" action="submit_form.php" method="post">
                <div class="form-group">
                    <label>1- عملکرد اداره نظارت و بازرسی در سال 1402 در خصوص نظارت و بازرسی مراکز طرف قرارداد به شرح
                        زیر می‌باشد:</label>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>نوع مرکز</th>
                                <th>پزشک عمومی</th>
                                <th>پزشک متخصص</th>
                                <th>داروخانه</th>
                                <th>دندانپزشک</th>
                                <th>پاراکلینیک</th>
                                <th>درمانگاه</th>
                                <th>مرکز جراحی محدود و بیمارستان</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>تعداد مراکز</td>
                                <td><input type="text" name="general_practitioner_centers" class="form-control"></td>
                                <td><input type="text" name="specialist_centers" class="form-control"></td>
                                <td><input type="text" name="pharmacy_centers" class="form-control"></td>
                                <td><input type="text" name="dentist_centers" class="form-control"></td>
                                <td><input type="text" name="paraclinic_centers" class="form-control"></td>
                                <td><input type="text" name="clinic_centers" class="form-control"></td>
                                <td><input type="text" name="surgery_centers" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>آمار بازرسی حضوری</td>
                                <td><input type="text" name="general_practitioner_visits" class="form-control"></td>
                                <td><input type="text" name="specialist_visits" class="form-control"></td>
                                <td><input type="text" name="pharmacy_visits" class="form-control"></td>
                                <td><input type="text" name="dentist_visits" class="form-control"></td>
                                <td><input type="text" name="paraclinic_visits" class="form-control"></td>
                                <td><input type="text" name="clinic_visits" class="form-control"></td>
                                <td><input type="text" name="surgery_visits" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>آمار بازرسی غیر حضوری</td>
                                <td><input type="text" name="general_practitioner_non_visits" class="form-control"></td>
                                <td><input type="text" name="specialist_non_visits" class="form-control"></td>
                                <td><input type="text" name="pharmacy_non_visits" class="form-control"></td>
                                <td><input type="text" name="dentist_non_visits" class="form-control"></td>
                                <td><input type="text" name="paraclinic_non_visits" class="form-control"></td>
                                <td><input type="text" name="clinic_non_visits" class="form-control"></td>
                                <td><input type="text" name="surgery_non_visits" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>جمع بازرسی انجام شده</td>
                                <td><input type="text" name="general_practitioner_total_visits" class="form-control">
                                </td>
                                <td><input type="text" name="specialist_total_visits" class="form-control"></td>
                                <td><input type="text" name="pharmacy_total_visits" class="form-control"></td>
                                <td><input type="text" name="dentist_total_visits" class="form-control"></td>
                                <td><input type="text" name="paraclinic_total_visits" class="form-control"></td>
                                <td><input type="text" name="clinic_total_visits" class="form-control"></td>
                                <td><input type="text" name="surgery_total_visits" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>میانگین هر بازرسی</td>
                                <td><input type="text" name="general_practitioner_avg_visits" class="form-control"></td>
                                <td><input type="text" name="specialist_avg_visits" class="form-control"></td>
                                <td><input type="text" name="pharmacy_avg_visits" class="form-control"></td>
                                <td><input type="text" name="dentist_avg_visits" class="form-control"></td>
                                <td><input type="text" name="paraclinic_avg_visits" class="form-control"></td>
                                <td><input type="text" name="clinic_avg_visits" class="form-control"></td>
                                <td><input type="text" name="surgery_avg_visits" class="form-control"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="form-group">
                    <label>2- تعداد پرسنل اداره نظارت و بازرسی سه نفر می‌باشند. (با احتساب رییس اداره نظارت و
                        بازرسی)</label>
                </div>

                <div class="form-group">
                    <label>3- آیا کارشناسان سایر واحدها در امر بازرسی مشارکت دارند؟</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="participation" id="participation_yes"
                            value="yes">
                        <label class="form-check-label" for="participation_yes">
                            بلی
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="participation" id="participation_no"
                            value="no">
                        <label class="form-check-label" for="participation_no">
                            خیر
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>4- آیا نسبت پرسنل شاغل در اداره به حجم کار کافی است؟</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="sufficient_staff" id="sufficient_staff_yes"
                            value="yes">
                        <label class="form-check-label" for="sufficient_staff_yes">
                            بلی
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="sufficient_staff" id="sufficient_staff_no"
                            value="no">
                        <label class="form-check-label" for="sufficient_staff_no">
                            خیر
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>5- آیا برنامه‌ریزی بر حسب تعداد مراکز طرف قرارداد به صورت سالانه، ماهانه، هفتگی و روزانه
                        انجام می‌گردد؟</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="planning" id="planning_yes" value="yes">
                        <label class="form-check-label" for="planning_yes">
                            بلی
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="planning" id="planning_no" value="no">
                        <label class="form-check-label" for="planning_no">
                            خیر
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>الف) نظارت و بازرسی‌های حضوری:</label>
                    <textarea class="form-control" name="in_person_supervision" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>ب) نظارت‌های غیر حضوری (سیستمی):</label>
                    <textarea class="form-control" name="systemic_supervision" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>6- آیا بازرسی‌های حضوری و غیرحضوری (سیستمی) مطابق فرآیند انجام می‌گردد؟</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="supervision_compliance"
                            id="supervision_compliance_yes" value="yes">
                        <label class="form-check-label" for="supervision_compliance_yes">
                            بلی
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="supervision_compliance"
                            id="supervision_compliance_no" value="no">
                        <label class="form-check-label" for="supervision_compliance_no">
                            خیر
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="supervision_compliance_comments" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>7- آیا ایام تعطیل نظارت و بازرسی در مراکز طرف قرارداد انجام می‌گردد؟</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="holiday_supervision"
                            id="holiday_supervision_yes" value="yes">
                        <label class="form-check-label" for="holiday_supervision_yes">
                            بلی
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="holiday_supervision"
                            id="holiday_supervision_no" value="no">
                        <label class="form-check-label" for="holiday_supervision_no">
                            خیر
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>8- آیا فرآیند بررسی گزارشات درج شده عملکرد مراکز طرف قرارداد با مستندات مطابقت دارد؟</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="report_review" id="report_review_yes"
                            value="yes">
                        <label class="form-check-label" for="report_review_yes">
                            بلی
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="report_review" id="report_review_no"
                            value="no">
                        <label class="form-check-label" for="report_review_no">
                            خیر
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>9- آیا نتایج بررسی عملکرد پزشکان در سیستم ثبت می‌گردد؟</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="performance_recording"
                            id="performance_recording_yes" value="yes">
                        <label class="form-check-label" for="performance_recording_yes">
                            بلی
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="performance_recording"
                            id="performance_recording_no" value="no">
                        <label class="form-check-label" for="performance_recording_no">
                            خیر
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>10- آیا بازرسی‌های ادواری و نتایج آن در سیستم جامع اسناد پزشکی ثبت می‌گردد؟</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="periodic_inspection"
                            id="periodic_inspection_yes" value="yes">
                        <label class="form-check-label" for="periodic_inspection_yes">
                            بلی
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="periodic_inspection"
                            id="periodic_inspection_no" value="no">
                        <label class="form-check-label" for="periodic_inspection_no">
                            خیر
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>11- آیا کارشناسان بازرسی آموزش‌های لازم در خصوص آگاهی از مفاد قرارداد با مراکز، بخشنامه‌های
                        ابلاغی، تعرفه‌های تشخیصی درمانی، دستورالعمل‌ها و بخشنامه‌های داخلی را کسب کرده‌اند؟</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="training" id="training_yes" value="yes">
                        <label class="form-check-label" for="training_yes">
                            بلی
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="training" id="training_no" value="no">
                        <label class="form-check-label" for="training_no">
                            خیر
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>12- آیا ارزیابی اولیه و سیستمی از مراکز قبل از انجام برنامه بازرسی دوره‌ای انجام
                        می‌شود؟</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="initial_evaluation"
                            id="initial_evaluation_yes" value="yes">
                        <label class="form-check-label" for="initial_evaluation_yes">
                            بلی
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="initial_evaluation"
                            id="initial_evaluation_no" value="no">
                        <label class="form-check-label" for="initial_evaluation_no">
                            خیر
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>13- آیا بازرسی از واحدهای ارائه دهنده خدمات مانند دیالیز، فیزیوتراپی و... به صورت هدفمند
                        انجام می‌شود؟</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="targeted_inspection"
                            id="targeted_inspection_yes" value="yes">
                        <label class="form-check-label" for="targeted_inspection_yes">
                            بلی
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="targeted_inspection"
                            id="targeted_inspection_no" value="no">
                        <label class="form-check-label" for="targeted_inspection_no">
                            خیر
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>14- آیا نظارت و بازرسی در مراکز دارای خدمات مشمول آیین نامه اعضای هیات علمی و پزشکان تمام وقت
                        و مناطق محروم انجام می‌شود؟</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="supervision_deprived_areas"
                            id="supervision_deprived_areas_yes" value="yes">
                        <label class="form-check-label" for="supervision_deprived_areas_yes">
                            بلی
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="supervision_deprived_areas"
                            id="supervision_deprived_areas_no" value="no">
                        <label class="form-check-label" for="supervision_deprived_areas_no">
                            خیر
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>15- کیفیت بازرسی‌های انجام شده مناسب است؟ (از نظر گزارش‌نویسی و مستندسازی موارد مغایر با
                        ضوابط)</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="inspection_quality"
                            id="inspection_quality_yes" value="yes">
                        <label class="form-check-label" for="inspection_quality_yes">
                            بلی
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="inspection_quality"
                            id="inspection_quality_no" value="no">
                        <label class="form-check-label" for="inspection_quality_no">
                            خیر
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>الف) نظارت و بازرسی‌های حضوری:</label>
                    <textarea class="form-control" name="in_person_inspection_quality" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>ب) نظارت‌های غیرحضوری (سیستمی):</label>
                    <textarea class="form-control" name="systemic_inspection_quality" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>16- توضیحات و بازخوردهای حاصل از بازرسی‌های انجام شده مناسب است؟</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="feedback_quality" id="feedback_quality_yes"
                            value="yes">
                        <label class="form-check-label" for="feedback_quality_yes">
                            بلی
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="feedback_quality" id="feedback_quality_no"
                            value="no">
                        <label class="form-check-label" for="feedback_quality_no">
                            خیر
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>17- نظارت و کنترل بیماران پرونده انجام می‌گردد؟</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="patient_supervision"
                            id="patient_supervision_yes" value="yes">
                        <label class="form-check-label" for="patient_supervision_yes">
                            بلی
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="patient_supervision"
                            id="patient_supervision_no" value="no">
                        <label class="form-check-label" for="patient_supervision_no">
                            خیر
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>الف) آیا بازخوردهای حاصل از نظارت و کنترل بیماران پرونده مناسب است؟</label>
                    <textarea class="form-control" name="patient_supervision_feedback" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>18- آیا ارزیابی مستمر از عملکرد کارشناسان بازرسی انجام می‌شود؟</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="continuous_evaluation"
                            id="continuous_evaluation_yes" value="yes">
                        <label class="form-check-label" for="continuous_evaluation_yes">
                            بلی
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="continuous_evaluation"
                            id="continuous_evaluation_no" value="no">
                        <label class="form-check-label" for="continuous_evaluation_no">
                            خیر
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>توضیحات:</label>
                    <textarea class="form-control" name="continuous_evaluation_comments" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>سایر موارد ارزیابی عملکرد اداره نظارت و بازرسی:</label>
                    <textarea class="form-control" name="additional_comments" rows="3"></textarea>
                </div>
                <br />
                <br />
                <button type="submit" class="btn btn-primary" onclick="packFormDataToJson()">ارسال</button>
            </form>

        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </div> <!-- end of col-8 -->
    <div class="col-2"> </div>
</div> <!-- end of row -->



<!-- جمع آوری داده ها از فرم و تبدیل به جیسون -->
<script>

    function packFormDataToJson() {

        // Prevent the form from submitting
        event.preventDefault();

        const form = document.getElementById('myForm');
        const formData = new FormData(form);
        const formObject = {};
        // alert('form: ' + form);
        // alert('formData: ' + formData);

        // Loop through all form data and build an object
        formData.forEach((value, key) => {
            // alert('key: ' + key + 'value: ' + value);
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
        // console.log(jsonString);  // For debugging; remove this in production
        alert('packFormDataToJson() called.\njson: ' + jsonString);

        // // You can store the JSON string in a hidden input to be sent with the form
        // const hiddenInput = document.createElement('input');
        // hiddenInput.type = 'hidden';
        // hiddenInput.name = 'json_data'; // Change the name as needed
        // hiddenInput.value = jsonString;
        // form.appendChild(hiddenInput);

    }
</script>



<!-- توزیع داده ها از جیسون به المنت های روی فرم -->
<script>
    // Function to populate the form with values from JSON string
    function populateForm(jsonString) {

        alert('populateForm(jsonString) called!\njson: ' + jsonString);
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

    // Example of how to use the populateForm function
    // let jsonString = '{"general_practitioner_centers":"5","specialist_centers":"10","participation":"yes"}';
    // populateForm(jsonString); // Pass the JSON string here

</script>






<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<!-- GPT Sample Code for using js-fetch/PHP to send retrive json to a php -->
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->


<!-- js-fetch -->
<!-- <form id="myForm">
    <input type="text" name="general_practitioner_centers" value="5">
    <input type="text" name="hospital_centers" value="10">
    <button type="submit">Submit</button>
</form> -->

<script>
    // // This function collects form data and converts it to JSON
    // function packFormDataToJson(form) {
    //     const formData = new FormData(form);
    //     const formObject = {};

    //     // Create JSON object from form data
    //     formData.forEach((value, key) => {
    //         formObject[key] = value;
    //     });

    //     return JSON.stringify(formObject); // Convert object to JSON string
    // }

    // document.getElementById('myForm').addEventListener('submit', function (event) {
    //     event.preventDefault(); // Prevent the form from submitting normally

    //     const form = event.target; // Get the form element
    //     const jsonString = packFormDataToJson(form); // Convert form data to JSON

    //     // Send the JSON data to a PHP file using fetch
    //     fetch('your_php_file.php', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json', // Set content type to JSON
    //         },
    //         body: jsonString // Send JSON data in the request body
    //     })
    //         .then(response => response.json()) // Assuming the PHP file returns JSON
    //         .then(data => {
    //             console.log('Success:', data);
    //             // Handle success, e.g., update UI or show a success message
    //         })
    //         .catch(error => {
    //             console.error('Error:', error);
    //             // Handle error, e.g., show error message to user
    //         });
    // });
</script>



<!-- php -->
<?php
// // Read the raw POST data
// $json_data = file_get_contents('php://input');

// // Decode the JSON to an associative array
// $data = json_decode($json_data, true);

// // Now you can access the form data like this:
// $general_practitioner_centers = $data['general_practitioner_centers'];
// $hospital_centers = $data['hospital_centers'];

// // Process the data as needed, e.g., store it in a database

// // Send a JSON response back to JavaScript
// echo json_encode([
//     'status' => 'success',
//     'message' => 'Data processed successfully'
// ]);
?>