<?php 
    ini_set('session.gc_maxlifetime', 3600);
    session_set_cookie_params(3600);
    session_start();
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    require_once('../api/account.php');
    require_once('response.php');

    if(empty($_POST['username']) || empty($_POST['password'])) {
        error_response(1,'Dữ liệu đầu vào không hợp lệ');
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    $account = get_account(['username'],[$username]);

    if($account) {
        if(password_verify($password,$account['password'])) {
            $_SESSION["account"] = $account;
            success_response($account,'Đăng nhập thàng công!');
        }
        else {
            error_response(1,'Mật khẩu không đúng!');
        }
    } else {
        error_response(1,'Tên đăng nhập không đúng.');
    }
?>