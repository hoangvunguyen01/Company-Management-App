<?php 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    require_once('../api/branch.php');
    require_once('response.php');

    if(!empty($_POST['name']) && !empty($_POST['branch_id']) && !empty($_POST['desc']) && !empty($_POST['room'])) {
        $name = $_POST['name'];
        $branch_id = $_POST['branch_id'];
        $desc = $_POST['desc'];
        $room = $_POST['room'];
        $date = date("Y-m-d");
        if(strlen($branch_id) == 2) {
            if(!get_branch(['name'],['branch_id'],[$branch_id],[],[],0)) {
                if(add_branch($branch_id,$name,$desc,$room,$date)) {
                    success_response($branch_id,"Tạo phòng ban thành công");
                } else {
                    error_response(1,"Tạo phòng ban không thành công!");
                }
            } else {
                error_response(1,"Mã phòng ban đã tồn tại vui lòng nhập mã phòng ban khác!");
            }
        } else {
            error_response(1,"Vui lòng nhập mã phòng ban tối đa 2 ký tự");
        }
    } else {
        error_response(1,"Vui lòng xem lại thông tin!");
    }

?>