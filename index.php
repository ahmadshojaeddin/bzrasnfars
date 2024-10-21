<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <?php require_once("php/head.php"); ?>
</head>



<body class="rtl" style="background-color: #595959;">

    <div class="container-xxl px-0" dir="rtl">

        <?php include_once('php/navbar.php'); ?>

        <div class="row bg-dark-subtle p-3" style="min-height: 400px; background: url('img/bk02-0.125.jpg');">

            <?php

            // phpinfo();
            
            $link = '';

            if (isset($_GET['link'])) {

                $link = $_GET['link'];
            }

            switch ($link) {

                case 'geoloc':
                    include_once('php/geoloc/geoloc.php');
                    break;

                case 'regions':
                    include_once('php/regions/regions.php');
                    break;

                case 'units':
                    include_once('php/units/units.php');
                    break;

                case 'timex':
                    include_once('php/timex/timex.php');
                    break;

                case 'login':
                    include_once('php/login/login.php');
                    break;

                case 'logout':
                    include_once('php/login/logout.php');
                    break;

                case 'welcome':
                    include_once('php/login/welcome.php');
                    break;

                default:
                    include_once('php/home/home.php');
            }

            ?>

        </div>

        <div>
            <?php include_once('php/footer.php'); ?>
        </div>

    </div>

</body>

</html>