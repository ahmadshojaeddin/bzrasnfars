<?php
    session_start();
    if (session_destroy()) {
        echo "<script type='text/javascript'>location.href='index.php?link=login&msg=شما با موفقیت خارج شدید!&msgtype=success'</script>";
    } else {
        echo "session_destroy() error!";
    }
?>
