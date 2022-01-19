<?php 
    session_start();
    header('Access-Control-Allow-Origin: *');
    require_once('../api/employee.php');
    require_once('response.php');

    if(isset($_FILES['avatar'])) {
        $account = $_SESSION['account'];
        $id = $account['account_id'];
        $avatar = file_get_contents($_FILES['avatar']['tmp_name']);
        if(update_avatar($avatar,$id)) {
            success_response(0,"Cập nhật thành công!");
        } else {
            error_response(1,"Cập nhật không thành công!");
        }
    }
    else {
        error_response(1,"Không có file hình gửi.");
    }
?>