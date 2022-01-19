<?php 
    session_start();
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    require_once('../api/account.php');
    require_once('response.php');

    if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
        error_response(1,'Dữ liệu đầu vào không hợp lệ');
    }

    $account = $_SESSION["account"];
    $id = $account['account_id'];
    $old_password = $_POST['old_password'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if(password_verify($old_password,$account['password']) === false) {
        error_response(1,'Mật khẩu cũ không đúng!');
    }
    
    if(strcmp($password,$confirm_password)) {
        error_response(1,'Vui lòng nhập xác nhận mật khẩu giống với mật khẩu mới.');
    }


    $new_password = password_hash($password,PASSWORD_BCRYPT);

    $columns = array("password");
    $datas = array($new_password);
    $result = update_account($columns,$datas,"account_id",$id);

    if($result) {
        $account['password'] = $new_password;
        $_SESSION['account'] = $account;
        success_response($account['account_type'],'Đổi mật khẩu thành công');
    } else {
        error_response(2,"ERROR");
    }
?>