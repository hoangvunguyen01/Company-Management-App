<?php 
    header('Access-Control-Allow-Origin: *');
    require_once('../api/branch.php');
    require_once('../api/employee.php');
    require_once('../api/form.php');
    require_once('../api/task.php');
    require_once('response.php');

    if(!empty($_POST['desc']) && !empty($_POST['id']) 
        && !empty($_POST['branch_id']) && !empty($_POST['name'])) {
        $branch = get_info_branch("branch_id",$_POST['branch_id']);
        if(!$branch) {
            error_response(1,"Không tìm thấy phòng ban.");
        } else {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $desc = $_POST['desc'];
            if(strcmp($id,$branch['id']) != 0) {
                $update_emp_id = update_id($branch['id'],$id,['branch_id'],[$branch['id']]);
                $update_form_id = update_form_id($branch['id'],$id,['branch_id'],[$branch['id']]);
                $update_task_id = update_task_id($branch['id'],$id,['branch_id'],[$branch['id']]);
            }

            if(update_branch(['branch_id','name','description'],[$id,$name,$desc],'branch_id',$branch['id'])) {
                success_response($id,"Cập nhật thành công!");
            } else {
                error_response(2,"Cập nhật thất bại!");
            }
        }
    }
    else {
        error_response(1,"Vui lòng nhập đủ thông tin để có thế chỉnh sửa!");
    }
?>