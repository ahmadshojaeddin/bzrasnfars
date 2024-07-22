<?php
// echo ('navbar-setad');
?>

<div class="row mt-1 rtl">

    <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">

        <div class="container-fluid">


        
            <!-- brand -->
            <a class="navbar-brand" href="#">
                <!-- Navbar -->
                <img src="./img/brand-gray.png" alt="بازرسی" width="120" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>



            <!-- menus -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/setad/index.php">خانه</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            برنامه ها
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/setad/index.php?link=inspections">بازرسی استان ها</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">دانلود ها</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">ارتباط با ما</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">لینک ها</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link disabled" aria-disabled="true">مدیریت سایت</a>
                    </li>

                </ul>



                <!-- search -->
                <form class="d-flex" role="search">
                    <input class="form-control me-2 h-25 mt-1" style="border-radius: 100px;" type="search" placeholder="کلمه کلیدی" aria-label="Search">
                    <button class="btn btn-sm btn-outline-secondary h-50 mt-1" style="border-radius: 100px;" type="submit"><i class="fa fa-search pt-1" aria-hidden="true"></i></button>
                </form>



                <!-- user profile menu -->
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <!-- put login text here if you want -->
                            <i class="fa fa-user fa-2x m-1" aria-hidden="true"></i>
                            <!-- <i class="bi bi-person-circle m-1" aria-hidden="true"></i> -->
                            <?php
                            if (isset($_SESSION['login_user'])) {
                                // $code = $_SESSION['login_user'];
                                $fullname = $_SESSION['fullname'];
                                echo "<span>$fullname</span>";
                            }
                            ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php
                            if (!isset($_SESSION['login_user'])){
                                echo "<li><a class='dropdown-item text-end' href='/index.php?link=login'>ورود &nbsp;<i class='fas fa-sign-in-alt fa-1x'></i></a></li>";
                            } else {
                                echo "<li><a class='dropdown-item text-end' href='/index.php?link=logout'>خروج &nbsp;<i class='fas fa-sign-in-alt fa-1x'></i></a></li>";
                            }
                            ?>
                            <!-- <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-end" href="#">ثبت نام &nbsp;<i class='fa-solid fa-user-pen fa-1x'></i></a></li> -->
                            <!-- <li><a class="dropdown-item text-end" href="#">پروفایل &nbsp;<i class='fas fa-sliders fa-1x'></i></a></li> -->
                            <!-- <li><a class="dropdown-item text-end" href="#">خروج &nbsp;<i class='fas fa-sign-out-alt fa-1x'></i></a></li> -->
                        </ul>
                    </li>
                </ul>



            </div>

        </div>

    </nav>

</div>