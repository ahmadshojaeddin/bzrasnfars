<?php
    $cookie_name = "user";
    $cookie_value = "John Doe";
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
?>

<?php

    require_once("./php/config.php");
    session_start();

    if($_SERVER["REQUEST_METHOD"] == "POST") {

        // username and password sent from form 
        
        $myusername = mysqli_real_escape_string($db, $_POST['username']);
        $mypassword = mysqli_real_escape_string($db, $_POST['password']);
        
        $sql = "SELECT id FROM users WHERE username = '$myusername' and passcode = '$mypassword'";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $active = $row['active'];
        
        $count = mysqli_num_rows($result);
        
        // If result matched $myusername and $mypassword, table row must be 1 row
        
        if($count == 1) {

            // session_register("myusername");
            $_SESSION['myusername'] = "shoja: set myusername value";
            $_SESSION['login_user'] = $myusername;
            
            header("location: welcome.php");

        } else {
            $error = "Your Login Name or Password is invalid";
        }

    }

?>

<div class="row" style="min-height: 20px;"></div>
<div class="row">
    <div class="col-md-3 col-sm-2 col-1"></div>
    <div class="col-md-6 col-sm-8 p-5 col-10 border bg-light">
        <main class="form-signin w-100 m-auto">
            <form>
                <h1 class="h3 mb-3 fw-normal">ورود</h1>

                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInput" placeholder="کد ملی / کد واحد">
                    <label for="floatingInput">نام کاربری</label>
                </div>
                <div class="form-floating mt-2">
                    <input type="password" class="form-control" id="floatingPassword" placeholder="رمز عبور">
                    <label for="floatingPassword">رمز عبور</label>
                </div>

                <div class="form-check text-start my-3">
                    <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        اطلاعات ورورد مرا به یاد داشته باش
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
