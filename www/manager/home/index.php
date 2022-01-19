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
    <title>Trang chủ</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <?php 
        require_once('../../components/header.php'); 
        require_once('../../api/task.php');
        require_once('../../api/employee.php');
        $manager = get_employee(['emp_id','branch_id'],['account_id'],[$account['account_id']],[],[],0)[0];
        $tasks = get_task(['task_id','name','start_day','status','executant_id'],['creator_id'],[$manager->emp_id],[],[],0);
    ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                <h2 class="mb-3 heading-tasks" onclick="handleReload()">Tất cả</h2>
                <div class="list-group list-group-task d-none d-lg-block">
                    <div id='01 creator_id' class="list-group-item list-group-item-action" onclick='handleRender(this,"task")'>New</div>
                    <div id='02 creator_id' class="list-group-item list-group-item-action" onclick='handleRender(this,"task")'>In progress</div>
                    <div id='03 creator_id' class="list-group-item list-group-item-action" onclick='handleRender(this,"task")'>Waiting</div>
                    <div id='04 creator_id' class="list-group-item list-group-item-action" onclick='handleRender(this,"task")'>Canceled</div>
                    <div id='05 creator_id' class="list-group-item list-group-item-action" onclick='handleRender(this,"task")'>Rejected</div>
                    <div id='06 creator_id' class="list-group-item list-group-item-action" onclick='handleRender(this,"task")'>Completed</div>
                </div>

                <div class="dropdown d-lg-none">
                    <button class="btn btn-secondary btn-block d-flex justify-content-between"
                            type="button" id="dropdownMenu1" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        Tất cả
                        <i class="fas fa-chevron-down icon-dropdown mt-2"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <div id='01 creator_id' class="dropdown-item dropdown-item-subnav" onclick='handleRender(this,"task")'>New</div>
                        <div id='02 creator_id' class="dropdown-item dropdown-item-subnav" onclick='handleRender(this,"task")'>In progress</div>
                        <div id='03 creator_id' class="dropdown-item dropdown-item-subnav" onclick='handleRender(this,"task")'>Waiting</div>
                        <div id='04 creator_id' class="dropdown-item dropdown-item-subnav" onclick='handleRender(this,"task")'>Canceled</div>
                        <div id='05 creator_id' class="dropdown-item dropdown-item-subnav" onclick='handleRender(this,"task")'>Rejected</div>
                        <div id='06 creator_id' class="dropdown-item dropdown-item-subnav" onclick='handleRender(this,"task")'>Completed</div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
                <div class="row mt-4 row-task">
                    <?php 
                        if($tasks) {
                            foreach($tasks as $task) {
                                $employee = get_employee(['last_name','first_name'],['emp_id'],[$task->executant_id],[],[],0)[0];
                                $format = format_task($task->status);
                                echo "<div class='col-12 col-sm-6 col-md-4 col-lg-4 mb-4'>\n";
                                echo "<div id='$task->task_id' class='card $format[1]' onclick='handleDetail(this)'>\n";
                                echo "<div class='card-body'>\n";
                                echo "<h4 class='card-title $format[0] card-title-nowrap'>$task->name</h4>\n";
                                echo "<h6 class='card-subtitle mb-2 $format[0]'>Ngày giao: $task->start_day</h6>\n";
                                echo "<p class='card-text $format[0] card-text-nowrap'>Giao cho: $employee->last_name $employee->first_name</p>\n";
                                echo "<p class='card-text $format[0]'>Trạng thái: <strong>$format[2]</strong></p>\n";
                                echo "</div>\n";
                                echo "</div>\n";
                                echo "</div>\n";
                            }
                        }
                    ?>
                </div>
                <?php 
                    if($tasks) {
                        if(count($tasks) == 15) {
                            $table = 'task';
                            $selector = '.card';
                            $column = 'creator_id';
                            require_once('../../components/more-btn.php');
                        }
                    }
                ?>
            </div>
        </div>
    </div>

    <?php 
        require_once('../../components/plus-btn.php');
        require_once('../../components/footer.php');
    ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    <script src="/main.js"></script>
</body>
</html>