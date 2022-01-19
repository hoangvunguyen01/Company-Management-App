<?php require_once("../../checked.php") ?>
<?php require_once("../check.php") ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <title>Chi tiết task</title>
    <link rel="stylesheet" href="/style.css">
</head>

<body>
    <?php 
        require_once('../../components/header.php'); 
        require_once('../../api/task.php');
        require_once('../../api/employee.php');
        require_once('../../api/file.php');
        require_once('../../api/message.php');
        if(isset($_GET['id'])) {   
            $task = get_info_task("task_id",$_GET['id']);
            $files_task = get_files(['task_id','date','submit'],[$_GET['id'],$task['start_day'],2]);
            $employee = get_employee(['last_name','first_name'],['emp_id'],[$task['executant_id']],[],[],0)[0];
            $name = $employee->last_name." ".$employee->first_name;
            $format = format_task($task['status']);
            $responsive = get_message(['task_id'],[$_GET['id']]);
            if($responsive) {
                foreach($responsive as $message) {
                    if($message->name === "Submit task" && $message->executant == 1) {
                        $submit = $message;
                    }
                }
            }
        }    
    ?>

    <div class="container tasks-detail pt-2 px-4 pb-5">
        <div class="row">
            <!-- Phần nội dung chính -->
            <div class="col-12 col-sm-12 col-lg-8 col-xl-9">
                <div class="tasks-detail__main">
                    <i class="d-none d-sm-none d-lg-flex tasks-detail__icon far fa-file-alt"></i>
                    <div class="tasks-detail__wrapper w-100">
                        <h2 class=""><?= $task['name'] ?></h2>
                        <div class="d-flex flex-column flex-sm-row justify-content-between text-secondary">
                            <span>Ngày giao: <strong class="text-success"><?= date("d-m-Y", strtotime($task['start_day'])) ?></strong></span>
                            <span>Hạn nộp: <strong class="text-danger"><?= date("d-m-Y H:i:s", strtotime($task['end_day'])) ?></strong></span>
                        </div>
                        <div class="d-flex flex-column flex-sm-row justify-content-between pb-1 py-sm-2 text-secondary border-bottom border-primary">
                            <span>Người thực hiện: <strong class="text-success"><?php echo $name."-".$task['executant_id'] ?></strong></span>
                            <span>Trạng thái: <strong class="text-success"><?= $format[2] ?></strong></span>
                        </div>
                        <div class="<?= $format[3] ?> flex-column py-2 text-secondary border-bottom border-primary">
                            <span>Mức đánh giá: <strong class="text-success">
                                <?php 
                                    if($task['rate'] == 3) {
                                        echo "Good";
                                    } elseif($task['rate'] == 2) {
                                        echo "OK";
                                    } elseif($task['rate'] == 1) {
                                        echo "Bad";
                                    } else {
                                        echo "";
                                    }
                                ?>
                            </strong></span>
                            <span>Trạng thái hoàn thành: <strong class="text-primary">
                            <?php
                                if(strtotime($submit->datetime) <= strtotime($task['end_day'])) {
                                    echo "Đúng tiến độ";
                                } else {
                                    echo "Trễ tiến độ";
                                }
                            ?>
                            </strong></span>
                        </div>
                        <div class="p-2 border-bottom border-primary">
                            <p><?= $task['description'] ?></p>
                            <ul class="tasks-detail__file-list">
                            <?php 
                                if($files_task) {
                                    echo "<li class='tasks-detail__file-item'>File được gửi kèm:</li>\n";
                                    foreach($files_task as $file) {
                                        $href = "data:".$file->type.";base64,".base64_encode($file->file);
                                        echo "<li class='tasks-detail__file-item'>\n";
                                        echo "<a download='$file->name' href='$href'>$file->name</a>\n";
                                        echo "</li>\n";
                                    }
                                }    
                            ?>
                            </ul>
                        </div>

                        <!-- Các response cho task -->
                        <div class="tasks-detail__responsive">
                            <h4 class="mt-3 font-weight-bold">Phản hồi <span>(<?php if($responsive) echo count($responsive); else echo '0' ?>)</span>:</h4>
                            <ul class="tasks-detail__responsive-list">
                            <?php 
                                if($responsive) {
                                    foreach($responsive as $message) {
                                        $datetime = date("d-m-Y H:i:s", strtotime($message->datetime));
                                        $files = get_files(['task_id','date'],[$task['task_id'],$message->datetime]);
                                        echo "<li class='tasks-detail__responsive-item'>\n";
                                        echo "<div data-toggle='collapse' data-target='#collapseExample$message->id' 
                                            aria-expanded='false' aria-controls='collapseExample$message->id'>\n";
                                        echo "<div class='row'>\n";
                                        echo "<div class='col-12 col-sm-6'>\n";
                                        echo "<span class='font-weight-bold'>$message->name</span>\n";
                                        echo "</div>\n";
                                        echo "<div class='col-12 col-sm-6 d-flex justify-content-sm-end'>\n";
                                        echo "<span>Ngày giờ: <strong class='text-success'>$datetime</strong></span>\n";
                                        echo "</div>\n";
                                        echo "</div>\n";
                                        echo "</div>\n";
                                        echo "<div class='collapse multi-collapse border border-info rounded pl-2 py-2 mt-2'
                                        id='collapseExample$message->id'>\n";
                                        echo "<p>$message->message</p>\n";
                                        if($files) {
                                            echo "<ul class='tasks-detail__file-list'>\n";
                                            echo "<li class='tasks-detail__file-item'>File được gửi kèm:</li>\n";
                                            foreach($files as $file) {
                                                $href = "data:".$file->type.";base64,".base64_encode($file->file);
                                                echo "<li class='tasks-detail__file-item'>\n";
                                                echo "<a download='$file->name' href='$href'>$file->name</a>\n";
                                                echo "</li>\n";
                                            }
                                            echo "</ul>\n";
                                        }
                                        echo "</div>\n";
                                        echo "</li>\n";
                                        
                                    }
                                }
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phần duyệt task -->
            <div class="col-12 col-sm-6 offset-sm-3 offset-lg-0 col-lg-4 col-xl-3">
                <div class="tasks-detail__submit">
                    <h4 class="text-primary mb-3">Review</h4>
                    <div class="mt-2 <?php if($task['status'] != 1) echo 'd-none'?>">
                        <button type="button" class="w-100 btn btn-danger mr-2" data-toggle="modal"
                            data-target="#canceled-dialog">Hủy task</button>
                    </div>
                    <div class="mt-2 justify-content-around
                    <?php if($task['status'] == 3) echo 'd-flex'; else echo 'd-none';?> 
                    ">
                        <button type="button" class="w-50 btn btn-danger mr-2" data-toggle="modal"
                            data-target="#reject-dialog">Từ chối</button>
                        <button type="button" class="w-50 btn btn-success" data-toggle="modal"
                            data-target="#approve-dialog">Đồng ý</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject dialog -->
    <div class="modal fade" id="reject-dialog">
        <form id="<?= $task['task_id'] ?>" class="reject-task">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Từ chối báo cáo</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="reject-content">Lý do:</label>
                            <textarea name="reason" id="reject-content" rows="5" class="form-control" placeholder="Nội dung" rules="required"></textarea>
                            <div class="error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="reject-day" class="col-form-label">Gia hạn ngày nộp:</label>
                            <input type="datetime-local" class="form-control" id="reject-day">
                        </div>
                        <div class="form-group">
                            <label for="reject-file">File đính kèm:</label>
                            <div class="custom-file">
                                <input onchange="handleChangeFile2(this,'.reject-file-list')" type="file" class="custom-file-input" id="reject-file" multiple>
                                <label class="custom-file-label" for="reject-file">Choose files</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <ul class="tasks-detail__file-list reject-file-list">
                                
                            </ul>
                        </div>
                        <div class="form-message-reject alert d-none"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Hủy bỏ</button>
                        <button onclick="Validator('.reject-task','rejecttask')" type="submit" class="btn btn-success">Xác nhận</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Approve dialog -->
    <div class="modal fade" id="approve-dialog">
        <form id="<?= $task['task_id'] ?>" class="approve-task">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Review báo cáo</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="approve-judge" class="col-form-label">Đánh giá mức độ hoàn thành:</label>
                            <select id="approve-judge" class="form-control" name="rate" rules="required">
                                <option value="">--Chọn mức độ--</option>
                                <?php
                                    if(strtotime($submit->datetime) <= strtotime($task['end_day'])) {
                                        echo "<option value='3'>Good</option>";
                                    }
                                ?>
                                <option value="2">OK</option>
                                <option value="1">Bad</option>
                            </select>
                            <div class="error-message pl-1"></div>
                        </div>
                        <div class="form-message-approve alert d-none"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Hủy bỏ</button>
                        <button onclick="Validator('.approve-task','approvetask')" type="submit" class="btn btn-success">Xác nhận</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Canceled dialog -->
    <div class="modal fade" id="canceled-dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hủy task</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="canceled-judge" class="col-form-label">Bạn có chắc chắn muốn hủy task ?</label>
                    </div>
                    <div class="form-message-cancel alert d-none"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Hủy bỏ</button>
                    <button onclick="handleCancelTask('<?= $_GET['id'] ?>')" type="submit" class="btn btn-success">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>

    <?php require_once('../../components/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    <script src="/main.js"></script>
</body>

</html>