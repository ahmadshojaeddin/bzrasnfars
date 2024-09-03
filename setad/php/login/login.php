<?php
// session_start(); // commented, because session has been started in index.php
// require_once('php/db/config.php');
include_once('php/db/config.php');

// echo "<br/>juest befor post";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // echo "<br/>in post if";

    // username and password sent from form 
    // echo 'db: ' , $db ;
    // echo 'user: ' , $_POST['username'] ;
    // echo ' pass: ' , $_POST['password'] ;

    $myusername = mysqli_real_escape_string($db, $_POST['username']);
    $mypassword = mysqli_real_escape_string($db, $_POST['password']);
    // $mypassword = md5($mypassword);
    // echo "<br/> user/pass: " . $myusername . " / " . ($mypassword);


    $sql = "SELECT id, active FROM users WHERE username = '$myusername'"; // and password = '$mypassword'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if ($row != null) {
        $active = $row['active'];
        $count = mysqli_num_rows($result);
    } else {
        $count = 0;
    }

    // // If result matched $myusername and $mypassword, table row must be 1 row

    if ($count == 1 && $active == 1) {

        // session_register("myusername");
        $_SESSION['login_user'] = $myusername;
        $test = $_SESSION['login_user'];
        echo "<script type='text/javascript'>location.href='index.php?link=welcome';</script>";
        exit();
        // echo "<br/> session has been set to '" . $_SESSION['login_user'] . "'";
        // header("location:index.php?link=welcome");
        // header("location:welcome.php");
        // die();
        // location.replace('/index.php?link=we.are');
        // echo "<br/> redirected...";

    } else {
        $error = "Your Login Name or Password is invalid";
    }
}

?>

<div class="row" style="min-height: 20px;"></div>
<div class="row">
    <div class="col-md-3 col-sm-2 col-1"></div>
    <div class="col-md-6 col-sm-8 p-5 col-10 border bg-light">
        <!-- <p>ver 21</p> -->
        <main class="form-signin w-100 m-auto">
            <form action="" method="post">
                <h1 class="h3 mb-3 fw-normal">ورود</h1>

                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInput" placeholder="کد ملی / کد واحد" name="username">
                    <label for="floatingInput">نام کاربری</label>
                </div>
                <div class="form-floating mt-2">
                    <input type="password" class="form-control" id="floatingPassword" placeholder="رمز عبور" name="password">
                    <label for="floatingPassword">رمز عبور</label>
                </div>

                <div class="form-check text-start my-3">
                    <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        اطلاعات ورود مرا به یاد داشته باش
                    </label>
                </div>
                <button class="btn btn-primary w-100 py-2" type="submit">ورود</button>
                <p class="mt-5 mb-3 text-body-secondary">© 1402</p>
            </form>
        </main>
    </div>
    <div class="col-md-3 col-sm-2 col-1"></div>
</div>
<div class="row" style="min-height: 80px;"></div>