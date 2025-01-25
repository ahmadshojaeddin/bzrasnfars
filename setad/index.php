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

                    case 'score':
                        include_once('./php/score/score.php');
                        break;

                    case 'schedule':
                        include_once('./php/schedule/schedule.php');
                        break;

                    case 'list':
                        include_once('./php/inspections/list.php');
                        break;

                    case 'addnew':
                        include_once './php/inspections/addnew.php';
                        break;

                    case 'edit':
                        include_once './php/inspections/edit.php';
                        break;

                    case 'editform':

                        if (isset($_GET['form_id']))
                            $insp_id = $_GET['form_id'];

                        switch ($insp_id) {
                            case '1':
                                include_once './php/inspections/forms/f01-rotbeh.php';
                                break;
                            case '2':
                                include_once './php/inspections/forms/f02-komitehfanni.php';
                                break;
                            case '3':
                                include_once './php/inspections/forms/f03-elmitakhassosi.php';
                                break;
                            case '4':
                                include_once './php/inspections/forms/f04-komitehostani.php';
                                break;
                            case '5':
                                include_once './php/inspections/forms/f05-bazrasi.php';
                                break;
                            case '6':
                                include_once './php/inspections/forms/f06-pezeshkan.php';
                                break;
                            case '7':
                                include_once './php/inspections/forms/f07-darukhaneh.php';
                                break;
                            case '8':
                                include_once './php/inspections/forms/f08-paraclinic.php';
                                break;
                            case '9':
                                include_once './php/inspections/forms/f09-nazerbimarestani.php';
                                break;
                            case '10':
                                include_once './php/inspections/forms/f10-bimarestani.php';
                                break;
                            case '11':
                                include_once './php/inspections/forms/f11-khesarat.php';
                                break;
                            case '12':
                                include_once './php/inspections/forms/f12-mali.php';
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

        <div class="p-0 m-0" style="padding:0px; margin:0px;">
            <?php include_once './php/footer.php'; ?>
        </div>

    </div>

</body>

</html>