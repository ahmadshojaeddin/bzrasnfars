<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Test</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no">
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

<body>
    <div class="row">
        <input data-jdp placeholder="شروع بازرسی" style="width: 7em; height: 2em; background-color: #ffffee;" />
        &nbsp; &nbsp;
        <input data-jdp placeholder="پایان بازرسی" style="width: 7em; height: 2em; background-color: #ffffee;" />
    </div>
    <script>
        jalaliDatepicker.startWatch({});
    </script>
</body>

</html>