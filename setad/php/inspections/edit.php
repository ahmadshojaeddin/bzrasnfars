<head>

    <style>
        /* CSS styles */
        .form-container {
            background-color: transparent;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        label {
            margin-right: 10px;
            font-weight: bold;
        }

        select,
        input[type="text"] {
            padding: 5px;
            margin-right: 20px;
            border-radius: 0;
            /*4px;*/
            border: 1px solid #ccc;
        }

        button {
            padding: 8px 15px;
            background-color: #5cb85c;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
            color: white;
            flex-grow: 0;
        }

        .table-container {
            margin-top: 20px;
            position: relative;
            /* برای نگه داشتن دکمه در محدوده جدول */
            padding-bottom: 50px;
            /* ایجاد فضای کافی زیر جدول برای دکمه */
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        thead {
            background-color: #ddd;
        }

        a {
            color: blue;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .back-button {

            display: inline-block;
            /* از حالت block به inline-block تغییر دهید */
            margin-top: 20px;
            background-color: #5cb85c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            text-align: center;
            width: 100px;
            clear: both;
            /* اطمینان از اینکه دکمه بعد از جدول قرار بگیرد */

        }
    </style>


    <!-- Province Dropdown -->
    <script src="lib/provincedropdown/provincedropdown.js"></script>
    <link rel="stylesheet" href="lib/provincedropdown/provincedropdown.css" />




    <!-- Jalali Calendar -->

    <script src="lib/jalalidatepicker/jalalidatepicker.min.js"></script>
    <link rel="stylesheet" href="lib/jalalidatepicker/jalalidatepicker.min.css" />

    <style>
        select,
        input[type="date"] {
            width: 15%;
            padding: 10px;
            /* margin-bottom: 15px; */
            border: 1px solid #ccc;
            /* border-radius: 5px; */
            font-size: 14px;
        }

        #date {
            width: 15%;
            padding: 10px;
            /* margin-bottom: 15px; */
            border: 1px solid #ccc;
            /* border-radius: 5px; */
            font-size: 14px;
        }

        .modal {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
            background: #FFF;
            box-shadow: 0 0 8px rgba(0, 0, 0, .3);
            transition: margin-top 0.3s ease, height 0.3s ease;
            transform: translateZ(0);
            box-sizing: border-box;
            z-index: 999;
            border-radius: 3px;
            max-width: 600px;
            display: block;
            height: 400px;
            overflow: scroll;
        }
    </style>

</head>

<div class="form-container">

    <form autocomplete="off">

        <div class="form-row">
            <!-- <label for="province">استان:</label>
            <select id="province" name="province">
                <option value="">انتخاب کنید</option>
                <option value="1">استان ۱</option>
                <option value="2">استان ۲</option>
            </select> -->
            <label for="province">انتخاب استان:</label>
            <select class="province-dropdown" id="province" name="province"></select>


            <label for="status">وضعیت:</label>
            <select id="status" name="status">
                <option value="pending">در دست اقدام</option>
                <option value="completed">تکمیل شده</option>
            </select>

            <!-- <label for="date">تاریخ:</label>
            <input type="text" id="date" name="date" style="border-radius: 0px !important;"> -->
            <label for="date">انتخاب تاریخ:</label>
            <input data-jdp id="date" name="date" placeholder="تاریخ بازرسی" />
            <script>
                jalaliDatepicker.startWatch({});
            </script>

            <button type="submit" style="border-radius: 0px !important;">ثبت</button>

        </div>

    </form>

</div>

<div class="table-container">

    <table>
        <thead>
            <tr>
                <th>ردیف</th>
                <th>عنوان</th>
                <th>ویرایش</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>۱</td>
                <td>ارزیابی عملکرد اداره رتبه‌بندی فراهم‌کنندگان خدمات و خرید راهبردی</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
            <tr>
                <td>۲</td>
                <td>ارزیابی عملکرد کمیته فنی</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
            <tr>
                <td>۳</td>
                <td>ارزیابی عملکرد شورای علمی تخصصی</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
            <tr>
                <td>۴</td>
                <td>ارزیابی عملکرد شوراها و کمیته‌های مشترک استانی با سایر سازمان‌ها</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
            <tr>
                <td>۵</td>
                <td>ارزیابی عملکرد اداره نظارت و بازرسی</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
            <tr>
                <td>۶</td>
                <td>ارزیابی عملکرد واحد رسیدگی صورت‌حساب‌ها</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
            <tr>
                <td>۷</td>
                <td>ارزیابی عملکرد واحد رسیدگی صورت‌حساب‌های پزشکان</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
            <tr>
                <td>۸</td>
                <td>ارزیابی عملکرد واحد رسیدگی صورت‌حساب‌های دندانپزشکان و درمانگاه‌ها</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
            <tr>
                <td>۹</td>
                <td>ارزیابی عملکرد واحد رسیدگی صورت‌حساب‌های داروخانه‌ها</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
            <tr>
                <td>۱۰</td>
                <td>ارزیابی عملکرد واحد رسیدگی صورت‌حساب‌های مراکز جراحی محدود</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
            <tr>
                <td>۱۱</td>
                <td>ارزیابی عملکرد واحد رسیدگی صورت‌حساب‌های خسارت متفرقه</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
            <tr>
                <td>۱۲</td>
                <td>ارزیابی عملکرد اداره امور مالی و ممیزی</td>
                <td><a href="#">ویرایش</a></td>
            </tr>
        </tbody>
    </table>


    <!-- دکمه بازگشت در پایین صفحه -->
    <script>
        function returnToInspectionsList() {
            window.location.href = "setad/index.php?link=list";
        }
    </script>
    <a href="index.php" class="back-button" onclick="returnToInspectionsList()">بازگشت</a>

</div>