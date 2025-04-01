<?php
    session_start();
    if(isset($_SESSION['user'])) {
        echo "<script>window.location.href='Admin_home_hris.php';</script>";
    } else {
        echo "<script>window.location.href='login_hris.php';</script>";
    }
?>
