<?php 
    session_start();
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    require_once('../api/account.php');
    require_once('response.php');

    if(empty($_POST['password']) || empty($_POST['confirm_password'])) {
        error_response(1,'Dữ liệu đầu vào không hợp lệ');
    }

    $account = $_SESSION["account"];

    if($account['first_time'] === 0) {
        error_response(1,'Bạn không thể reset mật khẩu tại đây nữa!');
    }

    $id = $account['account_id'];
    $old_password = $account['password'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if(strcmp($password,$confirm_password)) {
        error_response(1,'Vui lòng nhập xác nhận mật khẩu giống với mật khẩu.');
    }

    if(password_verify($password,$old_password) === true) {
        error_response(1,'Vui lòng nhập mật khẩu khác mật khẩu mặc định!');
    }

    $new_password = password_hash($password,PASSWORD_BCRYPT);

    $columns = array("password","first_time");
    $datas = array($new_password,0);
    $result = update_account($columns,$datas,"account_id",$id);

    if($result) {
        $account['password'] = $new_password;
        $account['first_time'] = 0;
        $_SESSION['account'] = $account;
        success_response($account['account_type'],'Đổi mật khẩu thành công');
    } else {
        error_response(2,"ERROR");
        // chuyển tới trang ERROR
    }
?>