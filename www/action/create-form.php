<?php 
    session_start();
    header('Access-Control-Allow-Origin: *');
    require_once('../api/form.php');
    require_once('../api/branch.php');
    require_once('../api/employee.php');
    require_once('response.php');

    if(empty($_POST['start_day']) || empty($_POST['number_day']) || empty($_POST['reason'])) {
        error_response(1,"Vui lòng nhập đủ thông tin");
    } else {
        $account =  $_SESSION['account'];
        $employee = get_employee(['emp_id','branch_id','position','days_off'],['account_id'],[$account['account_id']],[],[],0)[0];
        
        if($account['account_type'] == 2) {
            if(15 - $employee->days_off <= 0) {
                error_response(1,"Bạn không thể tạo đơn vì bạn đã nghỉ đủ ngày phép bạn có.");
            } else {
                if($employee->days_off + $_POST['number_day'] > 15) {
                    error_response(1,"Số ngày xin nghỉ của bạn vượt quá số ngày nghỉ còn lại.");
                }
            }
        } elseif($account['account_type'] == 1) {
            if(12 - $employee->days_off <= 0) {
                error_response(1,"Bạn không thể tạo đơn vì bạn đã nghỉ đủ ngày phép bạn có.");
            } else {
                if($employee->days_off + $_POST['number_day'] > 12) {
                    error_response(1,"Số ngày xin nghỉ của bạn vượt quá số ngày nghỉ còn lại.");
                }
            }
        }

        $emp_id = $employee->emp_id;
        $forms = get_form(['form_id','status','result_day'],['emp_id'],[$emp_id],[],[],0);

        if($forms) {
            $form_nearest = $forms[count($forms) - 1];
            $result_day = $form_nearest->result_day;
            if($form_nearest->status == 1) {
                error_response(1,"Bạn không thể tạo đơn mới khi đơn cũ đang chờ duyệt!");
            }

            if(abs(strtotime(date('Y-m-d')) - strtotime($result_day))/(60*60*24) <= 7) {
                error_response(1,"Bạn chỉ tạo được đơn mới sau ít nhất 7 ngày kể từ lần tạo gần nhất!");
            }
        }

        $file = array();
        if(isset($_FILES['file'])) {
            $file_name = $_FILES['file']['name'];
            $file_size = $_FILES['file']['size'];
            $file_type = $_FILES['file']['type'];
            $file_tmp = $_FILES['file']['tmp_name'];
            
            $disallowance = array('sh','exe','msi','msc','jar','pif');
            $ext = pathinfo($file_name,PATHINFO_EXTENSION);
            if(in_array($ext,$disallowance)) {
                error_response(1,"Hệ thống không hỗ trợ loại file thực thi!");
            }

            if($file_size > 2 * 1024 * 1024) {
                error_response(1,"Dung lượng của file không được vượt quá 2MB.");
            }

            $file = array($file_name,$file_type,$file_tmp);
        }

        $branch_id = $employee->branch_id;
        if($employee->position == 1) {
            $manager_id = get_employee(['emp_id'],['branch_id','position'],[$branch_id,2],[],[],0)[0]->emp_id;
        }
        else {
            $manager_id = get_employee(['emp_id'],['position'],[3],[],[],0)[0]->emp_id;
        }

        $submit_day = date('Y-m-d');
        $start_day = $_POST['start_day'];
        $number_day = $_POST['number_day'];
        $reason = $_POST['reason'];
        $data = array($submit_day,$start_day,$number_day,$reason,$branch_id,$emp_id,$manager_id);

        if(add_form($data,$file)) {
            success_response("OK","Tạo đơn thành công!");
        } else {
            error_response(1,"Tạo đơn không thành công");
        }
    }
?>