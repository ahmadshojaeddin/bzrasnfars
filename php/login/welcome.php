<!DOCTYPE html>
<body>
<?php
if (isset($_SESSION['login_user'])) {
    $code = $_SESSION['login_user'];
    $fullname = $_SESSION['fullname'];
    // echo "<div><div class='alert alert-success' role='alert' style='border-radius: 10px !important;'>کاربر گرامی $fullname ، خوش آمدید. کد کاربری: $code</div><br/></div>";
    echo "<div><div class='alert alert-success' role='alert' style='border-radius: 10px !important;'>کاربر گرامی $fullname ، خوش آمدید.</div><br/></div>";
} else {
    echo "error. session not set!";
}
?>
</body>
</html>