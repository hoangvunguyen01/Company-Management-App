<?php require_once("../../checked.php") ?>
<?php require_once("../check.php") ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo Task mới</title>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="/style.css">
</head>

<body>
    <?php 
        require_once('../../components/header.php'); 
        require_once('../../api/employee.php');
        $manager = get_info_employee('account_id',$account['account_id']);
        $employees = get_employee(['emp_id','first_name','last_name'],['branch_id','position'],[$manager['branch_id'],1],[],[],0)
    ?>

    <div class="container tasks-create pt-2 px-4 pb-5">
        <h2 class="tasks-create__title">Tạo task mới</h2>
        <div class="row px-3">
            <div class="col-12 col-lg-8 offset-lg-2 mt-3 tasks-create__wrapper">
                <form id="create-task">
                    <div class="form-group">
                        <label class="font-weight-bold" for="tasks-create-title">Tiêu đề:</label>
                        <input type="text" class="form-control" id="tasks-create-title" placeholder="Tiêu đề"
                            autocomplete="off" rules="required" name="name">
                        <div class="error-message pl-1"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold" for="tasks-create-employee">Nhân viên:</label>
                            <select id="tasks-create-employee" class="form-control" rules="required" name="executant_id">
                                <option value="">--Chọn nhân viên--</option>
                            <?php 
                                foreach($employees as $employee) {
                                    echo "<option value='$employee->emp_id'>\n";
                                    echo "$employee->emp_id - $employee->last_name $employee->first_name\n";
                                    echo "</option>\n";
                                }
                            ?>
                            </select>
                            <div class="error-message pl-1"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold" for="tasks-create-deadline">Hạn nộp:</label>
                            <input type="datetime-local" class="form-control" id="tasks-create-deadline" rules="required|afterDate" name="end_day">
                            <div class="error-message pl-1"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="tasks-create-guide">Mô tả chi tiết:</label>
                        <textarea type="text" class="form-control" rows="6" id="tasks-create-guide"
                            placeholder="Nhập hướng dẫn..." rules="required" name="desc"></textarea>
                        <div class="error-message pl-1"></div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" for="tasks-create-file">Files đính kèm (nếu có):</label>
                        <div class="custom-file">
                            <input onchange="handleChangeFile2(this,'.tasks-create__file-list')"type="file" class="custom-file-input" id="tasks-create-file" multiple>
                            <label class="custom-file-label" for="tasks-create-file">Choose files...</label>
                        </div>
                        <ul class="tasks-create__file-list">
                            
                        </ul>
                    </div>
                    <div class="form-message alert d-none"></div>
                    <div class="d-flex justify-content-end mt-3">
                        <button onclick="Validator('#create-task','createtask')" type="submit" class="btn btn-success">Tạo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require_once('../../components/footer.php') ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    <script src="/main.js"></script>
</body>

</html>