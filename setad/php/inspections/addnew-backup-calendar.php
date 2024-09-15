<head>

    <!-- GPT styles -->
    <style>

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .button-container {
            margin-bottom: 10px;
        }

        .button {
            padding: 10px 15px;
            margin-right: 5px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 14px;
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


<div class="button-container">
    <button class="button">دکمه 1</button>
    <button class="button">دکمه 2</button>
</div>

<div class="button-container">

    <div class="row">
        <input data-jdp placeholder="تاریخ بازرسی" style="width: 7em; height: 2em; background-color: #ffffee;" />
        &nbsp; &nbsp;
        <input placeholder="پایان بازرسی" style="width: 7em; height: 2em; background-color: #ffffee;" />
    </div>
    <script>
        jalaliDatepicker.startWatch({});
    </script>

</div>

<div class="button-container mt-2">
    <button class="button">دکمه 1</button>
    <button class="button">دکمه 2</button>
</div>

<div class="row"></div>
<div class="row"></div>