<?php 
    header('Access-Control-Allow-Origin: *');
    require_once('../api/form.php');
    require_once('../api/employee.php');
    require_once('response.php');

    if(!empty($_POST['status']) && !empty($_POST['form_id'])) {
        $form = get_form(['status','number_day','emp_id'],['form_id'],[$_POST['form_id']],[],[],0);
        if(!$form) {
            error_response(1,"Không tìm thấy đơn.");
        }

        if($form[0]->status === 1) {
            $status = $_POST['status'];
            $form_id = $_POST['form_id'];
            $result_day = date('Y-m-d');
            if(update_form(['status','result_day'],[$status,$result_day],'form_id',$form_id)) {
                if($status == 2) {
                    update_dayoff($form[0]->number_day,['emp_id'],[$form[0]->emp_id]);
                }
                success_response($status,"Duyệt đơn thành công!");
            } else {
                error_response(1,"Duyệt đơn không thành công!");
            }
        } else {
            error_response(1,"Đơn đã được duyệt bạn không thể chỉnh sửa.");
        }
    } else {
        error_response(1,"Yêu cầu của bạn không được chấp nhận!");
    }
?>