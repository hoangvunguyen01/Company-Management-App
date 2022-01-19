<?php 
    header('Access-Control-Allow-Origin: *');
    require_once('../api/branch.php');
    require_once('../api/employee.php');
    require_once('../api/account.php');
    require_once('response.php');

    if(!empty($_POST['branch_id']) && !empty($_POST['emp_id'])) {
        $branch = get_info_branch("branch_id",$_POST['branch_id']);
        if(!$branch) {
            error_response(1,"Không tìm thấy phòng ban.");
        } else {
            $emp_id = $_POST['emp_id'];
            $branch_id = $_POST['branch_id'];
            if(strpos($emp_id,$branch_id) === false) {
                error_response(1,"Nhân viên không thuộc phòng ban.");
            } else {
                $emp = get_info_employee("emp_id",$emp_id);
                $manager = get_employee(["account_id"],["branch_id","position"],[$branch_id,2],[],[],0)[0];
                if(update_id("M","E",['branch_id','position'],[$branch_id,2])) {
                    update_employee(['position'],[1],['branch_id','position'],[$branch_id,2]);
                    update_account(['account_type'],[1],'account_id',$manager->account_id);
                }
                
                $manager_id = str_replace("E","M",$emp_id);
                $update1 = update_employee(['emp_id','position'],[$manager_id,2],['emp_id'],[$emp_id]);
                $update2 = update_account(['account_type'],[2],'account_id',$emp['account_id']);
                if($update1 && $update2) {
                    success_response($manager_id,"Thành công!");
                } else {
                    error_response(1,"Bổ nhiệm thất bại.");
                }
            }
        }
    }
    else {
        error_response(1,"Vui lòng chọn nhân viên để có thế bổ nhiệm!");
    }
?>