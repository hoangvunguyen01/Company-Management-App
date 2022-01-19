<?php 
    session_start();
    if(!isset($_SESSION["account"])) {
        header("Location: /");
        exit();
    } else {
        $account = $_SESSION["account"];
        if($account['first_time'] == 1) {
            header("Location: /reset-password.php");
            exit();
        }
    }
?>