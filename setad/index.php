<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <?php require_once("./php/head.php"); ?>
</head>



<body class="rtl" style="background-color: #595959;">

    <div class="container-xxl p-0" dir="rtl">

        <?php include_once './php/navbar.php'; ?>

        <div class="row bg-dark-subtle p-0" style="min-height: 400px; background: url('./img/bk02-0.125.jpg');">

            <div class="p-3">

                <?php

                // phpinfo();
                
                $link = '';

                if (isset($_GET['link']))
                    $link = $_GET['link'];

                switch ($link) {

                    case 'list':
                        include_once('./php/inspections/list.php');
                        break;

                    case 'addnew':
                        include_once('./php/inspections/addnew.php');
                        break;

                    case 'edit':
                        include_once('./php/inspections/edit.php');
                        break;

                    case 'editform':

                        if (isset($_GET['id']))
                            $id = $_GET['id'];

                        switch ($id) {
                            case '5':
                                // echo("<p>in line test</p>");
                                include_once('./php/inspections/edit/e05-bazrasi.php');
                                break;
                        }

                        break;

                    case 'login':
                        include_once './php/login/login.php';
                        break;

                    case 'welcome':
                        include_once './php/login/welcome.php';
                        break;

                    default:
                        include_once './php/home/home.php';
                }

                ?>

            </div>

        </div>

        <div class="p-0" style="padding:0;">
            <?php include_once './php/footer.php'; ?>
        </div>

    </div>

</body>

</html>