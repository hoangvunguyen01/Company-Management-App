<?php 
    session_start();
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    require_once('../api/account.php');
    require_once('response.php');

    if(empty($_POST['account_id'])) {
        error_response(1,'Dữ liệu đầu vào không hợp lệ');
    }

    $account = get_account(['account_id'],[$_POST['account_id']]);

    if($account) {
        $new_password = password_hash($account['username'],PASSWORD_BCRYPT);
        $columns = array("password","first_time");
        $datas = array($new_password,1);
        if(update_account($columns,$datas,"account_id",$_POST['account_id'])) {
            success_response(1,'Reset mật khẩu thành công');
        } else {
            error_response(1,"Reset mật khẩu không thành công");
        }
    } else {
        error_response(1,"Account không tồn tại");
    }
?>