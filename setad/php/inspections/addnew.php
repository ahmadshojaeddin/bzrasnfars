<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فرم انتخاب استان و تاریخ</title>
    <style>
        
        .form-container {

            background-color: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;

        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
        }

        select,
        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        #date {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

    </style>



    <!-- Jalali Calendar -->

    <script src="lib/jalalidatepicker/jalalidatepicker.min.js"></script>

    <link rel="stylesheet" href="lib/jalalidatepicker/jalalidatepicker.min.css" />

    <style>
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


<div class="row">

    <div class="col-4">
    </div>

    <div class="col-4">
        <div class="form-container">
            <h2>بازرسی جدید</h2>
            <form>

                <label for="province">انتخاب استان:</label>
                <select id="province" name="province">
                    <option value="تهران">تهران</option>
                    <option value="اصفهان">اصفهان</option>
                    <option value="فارس">فارس</option>
                    <option value="خراسان">خراسان</option>
                    <option value="گیلان">گیلان</option>
                </select>

                <label for="date">انتخاب تاریخ:</label>
                <!-- <input type="date" id="date" name="date"> -->
                <input data-jdp id="date" name="date" placeholder="تاریخ بازرسی" />
                <script>
                    jalaliDatepicker.startWatch({});
                </script>

                <button type="submit">ارسال</button>
            </form>
        </div>
    </div>

    <div class="col-4">
    </div>

</div>