<?php 
    header('Access-Control-Allow-Origin: *');
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    require_once('../api/task.php');
    require_once('../api/file.php');
    require_once('../api/message.php');
    require_once('response.php');

    if(!empty($_POST['status']) && !empty($_POST['task_id'])) {
        $task = get_info_task('task_id',$_POST['task_id']);
        if(!$task) {
            error_response(1,"Không tìm thấy task.");
        } else {
            if($task['status'] == 6) {
                error_response(1,"Task đã hoàn thành không thể chỉnh sửa gì được nữa.");
            }
            if($task['status'] != 4 ) {
                $status = $_POST['status'];
                switch ($status) {
                    case 6:
                        approve_task($task);
                        break;
                    case 5:
                        reject_task($task);
                        break;
                    case 4:
                        cancel_task($task);
                        break;
                    case 3:
                        submit_task($task);
                        break;
                    case 2:
                        start_task($task);
                        break;
                    default:
                        error_response(2,"Chuyển đến trang ERROR");
                        break;
                }
            } else {
                error_response(1,"Task đã hủy bạn không thể làm gì với task này nữa.");
            }
        }
    } else {
        error_response(1,"Yêu cầu của bạn không được chấp nhận!");
    }

    function cancel_task($task) {
        if($task['status'] == 1) {
            if(update_task(['status'],[4],'task_id',$task['task_id'])) {
                success_response($task,"Hủy task thành công!");
            } else {
                error_response(1,"Hủy task thất bại!");
            }
        } else {
            error_response(1,"Task không còn trong trạng thái mới. Vì thế bạn không thể hủy được.");
        }
    }

    function start_task($task) {
        if($task['status'] == 1) {
            if(update_task(['status'],[2],'task_id',$task['task_id'])) {
                success_response($task,"Bắt đầu task thành công!");
            } else {
                error_response(1,"Bắt đầu task thất bại!");
            }
        } else {
            error_response(1,"Task không còn trong trạng thái mới. Bạn không thể bắt đầu task được.");
        }
    }

    function submit_task($task) {
        if($task['status'] == 2 || $task['status'] == 5) {
            if(!empty($_POST['message']) && !empty($_POST['count_files'])) {
                $date = date('Y-m-d H:i:s');
                $message = array('Submit task',$_POST['message'],1,$date,$task['task_id']);
                if(!add_message($message)) {
                    error_response(1,"Submit task không thành công");
                } else {
                    for($i = 0; $i < $_POST['count_files']; $i++) {
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
                            $file = file_get_contents($file_tmp);

                            $array_file = array($file_name,$file_type,$file);
                            $array_data = array(1,$date,$task['task_id']);
                            if(!add_file($array_file,$array_data)) {
                                error_response(1,"Thêm file bị lỗi, vui lòng yêu cầu trưởng phòng từ chối và submit lại.");
                            }
                        }
                    }

                    if(update_task(['status'],[3],'task_id',$task['task_id'])) {
                        success_response($message,"Submit task thành công!");
                    }
                    else {
                        error_response(1,"Submit không thành công.");
                    }
                }
            } else {
                error_response(1,"Vui lòng nhập nội dung hoặc thêm file để submit task.");
            }
        } else {
            error_response(1,"Task không còn trong trạng thái đang thực hiện. Bạn không thể submit task được.");
        }
    }

    function reject_task($task) {
        if($task['status'] == 3) {
            if(!empty($_POST['reason']) && isset($_POST['count_files'])) {
                $date = date('Y-m-d H:i:s');
                $message = array('Từ chối',$_POST['reason'],2,$date,$task['task_id']);
                if(!add_message($message)) {
                    error_response(1,"Từ chối không thành công");
                } else {
                    if($_POST['count_files'] > 0) {
                        for($i = 0; $i < $_POST['count_files']; $i++) {
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
                                $file = file_get_contents($file_tmp);
    
                                $array_file = array($file_name,$file_type,$file);
                                $array_data = array(2,$date,$task['task_id']);
                                if(!add_file($array_file,$array_data)) {
                                    error_response(1,"Thêm file bị lỗi, vui lòng yêu cầu nhân viên submit và từ chối lại.");
                                }
                            }
                        }
                    }

                    if(isset($_POST['new_deadline']) && !empty($_POST['new_deadline'])) {
                        $new_dealine = str_replace("T"," ",$_POST['new_deadline']);
                        $update = update_task(['status','end_day'],[5,$new_dealine],'task_id',$task['task_id']);
                    } else {
                        $update = update_task(['status'],[5],'task_id',$task['task_id']);
                    }

                    if($update) {
                        success_response($message,"Từ chối thành công!");
                    }
                    else {
                        error_response(1,"Từ chối không thành công.");
                    }
                }
            } else {
                error_response(1,"Vui lòng nhập lý do nếu muốn từ chối submit.");
            }
        } else {
            error_response(1,"Task không còn trong trạng thái đang chờ duyệt.");
        }
    }

    function approve_task($task) {
        if($task['status'] == 3) {
            if(!empty($_POST['rate'])) {
                if(update_task(['status','rate'],[6,$_POST['rate']],'task_id',$task['task_id'])) {
                    success_response(6,"Đồng ý task thành công!");
                }
                else {
                    error_response(1,"Đồng ý task không thành công.");
                }
            } else {
                error_response(1,"Vui lòng nhập đánh giá task.");
            }
        } else {
            error_response(1,"Task không còn trong trạng thái đang chờ duyệt.");
        }
    }
?>