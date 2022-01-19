<?php 
    session_start();
    header('Access-Control-Allow-Origin: *');
    require_once('../api/file.php');
    require_once('../api/task.php');
    require_once('../api/employee.php');
    require_once('response.php');

    if(!empty($_POST['name']) && !empty($_POST['excutant_id']) &&
        !empty($_POST['end_day']) && !empty($_POST['desc']) &&
        isset($_POST['count_files'])) {
        
        $account_id = $_SESSION['account']['account_id'];
        $manager = get_employee(['emp_id','branch_id'],['account_id'],[$account_id],[],[],0);
        if($manager) {
            $manager_id = $manager[0]->emp_id;
            $branch_id = $manager[0]->branch_id;
        } else {
            error_response(2,"Lỗi ERROR!!!");
        }

        $name = $_POST['name'];
        $desc = $_POST['desc'];
        $end_day = $_POST['end_day'];
        $excutant_id = $_POST['excutant_id'];
        $start_day = date('Y-m-d');
        
        if(substr($excutant_id, 0, 2) !== $branch_id) {
            error_response(1,"Nhân viên này không thuộc phòng ban của bạn.");
        }

        if(strtotime($end_day) < strtotime($start_day)) {
            error_response(1,"Hạn nộp phải được thiết lập trong ngày hoặc sau hôm nay!");
        }

        $count_files = $_POST['count_files'];
            
        if($count_files != 0) {
            for($i = 0; $i < $count_files; $i++) {
                if(isset($_FILES['file'.$i])) {
                    $file_name = $_FILES['file'.$i]['name'];
                    $file_size = $_FILES['file'.$i]['size'];
                    $file_type = $_FILES['file'.$i]['type'];
                    $file_tmp = $_FILES['file'.$i]['tmp_name'];
                    
                    $disallowance = array('sh','exe','msi','msc','jar','pif');
                    $ext = pathinfo($file_name,PATHINFO_EXTENSION);
                    if(in_array($ext,$disallowance)) {
                        error_response(1,"Hệ thống không hỗ trợ loại file thực thi!");
                    }

                    if($file_size > 2 * 1024 * 1024) {
                        error_response(1,"Dung lượng của mỗi file không được vượt quá 2MB.");
                    }
                }
            }
        }

        $task_id = auto_task_id($branch_id);
        if($task_id) {
            if(add_task([$task_id,$name,$desc,$start_day,$end_day,$branch_id,$excutant_id,$manager_id])) {
                if($count_files != 0) {
                    for($i = 0; $i < $count_files; $i++) {
                        if(isset($_FILES['file'.$i])) {
                            $file_name = $_FILES['file'.$i]['name'];
                            $file_size = $_FILES['file'.$i]['size'];
                            $file_type = $_FILES['file'.$i]['type'];
                            $file_tmp = $_FILES['file'.$i]['tmp_name'];
                            $file = file_get_contents($file_tmp);

                            $array_file = array($file_name,$file_type,$file);
                            $array_data = array(2,$start_day,$task_id);
                            if(!add_file($array_file,$array_data)) {
                                error_response(1,"Tạo task thành công nhưng gặp sự cố về file, 
                                        vui lòng thêm lại file ở trang chi tiết task");
                            }
                        }
                    }
                }
                success_response($task_id,"Tạo task thành công");
            } else {
                error_response(1,"Tạo task không thành công. Vui lòng thử lại!");
            }   
        }
    } else {
        error_response(1,"Vui lòng nhập đủ thông tin");
    }

?>