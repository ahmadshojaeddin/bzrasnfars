<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <?php require_once ("./php/head.php"); ?>
</head>



<body class="rtl" style="background-color: #595959;">

    <div class="container-xxl px-0" dir="rtl">

        <?php include_once ('./php/navbar.php'); ?>

        <div class="row bg-dark-subtle p-3" style="min-height: 400px; background: url('./img/bk02-0.125.jpg');">

            <?php

            // phpinfo();
            
            $link = '';

            if (isset($_GET['link'])) {

                $link = $_GET['link'];

            }

            switch ($link) {

                case 'inspections':
                    include_once ('./php/inspections/inspections.php');
                    break;

                case 'addnew':
                    include_once ('./php/inspections/addnew.php');
                    break;

                default:
                    include_once ('./php/home/home.php');
            }

            ?>

        </div>

        <?php include_once ('./php/footer.php'); ?>

    </div>

</body>

</html>