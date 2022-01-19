<?php 
    session_start();
    require_once('response.php');
    require_once('../api/employee.php');
    require_once('../api/branch.php');
    require_once('../api/form.php');
    require_once('../api/task.php');
    header('Content-Type: application/json');

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(!empty($_POST['table']) && isset($_POST['number'])) {
            $table = $_POST['table'];
            $number = $_POST['number'];
            $account = $_SESSION["account"];
            $id = get_employee(['emp_id'],['account_id'],[$account['account_id']],[],[],0)[0]->emp_id;
            switch ($table) {
                case 'employee':
                    if(isset($_POST['column']) && isset($_POST['data'])) 
                        get_more_employee($number,$_POST['column'],$_POST['data']);
                    else 
                        get_more_employee($number,1,1);
                    break;
                case 'form':
                    if(isset($_POST['column']) && isset($_POST['data']) && isset($_POST['conf'])) 
                        get_more_form($number,[$_POST['column'],$_POST['conf']],[(int)$_POST['data'],$id]);
                    else 
                        get_more_form($number,[$_POST['conf']],[$id]);
                    break;
                case 'branch':
                    break;
                case 'task':
                    if(isset($_POST['column']) && isset($_POST['data']) && isset($_POST['conf'])) 
                        get_more_task($number,[$_POST['column'],$_POST['conf']],[(int)$_POST['data'],$id]);
                    else 
                        get_more_task($number,[$_POST['conf']],[$id]);
                    break;
                default:
                    error_response(2,"ERROR!");
            }
        } else {
            error_response(2,"ERROR!");
        }
    } else {
        error_response(2,"Không hỗ trợ phương thức khác ngoài GET");
    }
    
    function get_more_employee($number,$columns_conf,$datas_conf) {
        $employees = get_employee(['emp_id','branch_id','first_name','last_name','position','avatar'],[$columns_conf],[$datas_conf],['position'],[3],$number);
        if($employees) {
            $branchs = get_all_branch();
            foreach($employees as $employee) {
                $employee->branch_id = convert_branch($branchs,$employee->branch_id);
                $employee->avatar = 'data:image/jpg;charset=utf8;base64,'.base64_encode($employee->avatar);
                $employee->position = convert_position($employee->position);
            }
            if(count($employees) < 15) {
                $d = "d-none";
            } else {
                $count = $columns_conf == 1 ? count_employee($columns_conf,$datas_conf)-1 : count_employee($columns_conf,$datas_conf);
                $d = $count > $number ? "d-flex" : "d-none";
            }
            success_response([$employees,$d],"Thành công");
        } else {
            error_response(2,"ERROR");
        }
    }

    function get_more_form($number,$columns_conf,$datas_conf) {
        $forms = get_form(['form_id','submit_day','status','emp_id'],$columns_conf,$datas_conf,[],[],$number);
        if($forms) {
            foreach($forms as $form) {
                $employee = get_employee(['last_name','first_name'],['emp_id'],[$form->emp_id],[],[],0)[0];
                $format = format_form($form->status);
                $form->emp_id = $employee->last_name." ".$employee->first_name;
                $form->status = $format;
            }
            if(count($forms) < 15) {
                $d = "d-none";
            } else {
                $count = count_form($columns_conf[0],$datas_conf[0]);
                $d = $count > $number ? "d-flex" : "d-none";
            }
            success_response([$forms,$d],"Thành công");
        }
        else {
            success_response([$forms,"d-none"],"Thành công");
        }
    }

    function get_more_task($number,$columns_conf,$datas_conf) {
        $tasks = get_task(['task_id','name','start_day','status','executant_id'],$columns_conf,$datas_conf,[],[],$number);
        if($tasks) {
            foreach($tasks as $task) {
                $employee = get_employee(['last_name','first_name'],['emp_id'],[$task->executant_id],[],[],0)[0];
                $format = format_task($task->status);
                $task->executant_id = $employee->last_name." ".$employee->first_name;
                $task->status = $format;
            }
            if(count($tasks) < 15) {
                $d = "d-none";
            } else {
                $count = count_task($columns_conf[0],$datas_conf[0]);
                $d = $count > $number ? "d-flex" : "d-none";
            }
            success_response([$tasks,$d],"Thành công");
        }
        else {
            success_response([$tasks,"d-none"],"Thành công");
        }
    }
?>