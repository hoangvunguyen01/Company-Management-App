<?php
    session_start();
    require_once('response.php');
    header('Content-Type: application/json');

    if(empty($_POST['logout'])) {
        error_response(2,"Lỗi hệ thống!");
    }

    if(!strcmp($_POST['logout'],'LOA')) {
        unset($_SESSION['account']);
        success_response(0,"Đăng xuất thành công");
    } else {
        error_response(2,"ERROR");
    }
?>