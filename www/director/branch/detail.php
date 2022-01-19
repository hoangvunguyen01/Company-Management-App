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
    <title>Chi tiết phòng ban</title>
    <link rel="stylesheet" href="/style.css">
</head>

<body>
    <?php 
        require_once('../../components/header.php');
        require_once('../../api/employee.php');
        require_once('../../api/branch.php');
        if(isset($_GET['id'])) {   
            $info = get_info_branch("branch_id",$_GET['id']);
            $manager = get_employee(['emp_id','first_name','last_name','position','avatar'],['branch_id','position'],[$_GET['id'],2],[],[],0);
            if($manager) {
                $manager_name = $manager[0]->last_name." ".$manager[0]->first_name;
                $manager_id = $manager[0]->emp_id;
                $d = "d-none";
            } else {
                $manager_name = "Chưa có";
                $manager_id = "Chưa có";
                $d = "";
            }
            $count = count_employee("branch_id",$_GET['id']);
            $employees = array();
            for($i = 0; $i < $count; $i += 15) {
                $emps = get_employee(['emp_id','first_name','last_name','position','avatar'],['branch_id'],[$_GET['id']],['position'],[2],$i);
                $employees = array_merge($employees,$emps);
            }
        }
    ?>

    <div class="container departments-detail pt-2 pb-5">
        <h2 class="departments-detail__title">Thông tin phòng ban</h2>
        <div class="row px-3">
            <div class="col-12 col-lg-8 offset-lg-2 departments-detail__wrapper mt-3">
                <div class="departments-detail__info">
                    <div class="row">
                        <div class="col-12 col-sm-4 mb-4">
                            <p><strong>Tên phòng ban:</strong></p>
                            <input type="text" value="<?= $info['name'] ?>" disabled>
                        </div>
                        <div class="col-12 col-sm-4 mb-4">
                            <p><strong>Mã phòng ban:</strong></p>
                            <input type="text" value="<?= $info['id'] ?>" disabled>
                        </div>
                        <div class="col-12 col-sm-4 mb-4">
                            <p><strong>Số phòng:</strong></p>
                            <input type="text" value="<?= $info['room'] ?>" disabled>
                        </div>
                        <div class="col-12 col-sm-6 mb-4">
                            <p><strong>Tên trưởng phòng:</strong></p>
                            <input type="text" value="<?= $manager_name ?>" disabled>
                        </div>
                        <div class="col-12 col-sm-6 mb-4">
                            <p><strong>Mã trưởng phòng:</strong></p>
                            <input type="text" value="<?= $manager_id ?>" disabled>
                        </div>
                        <div class="col-12 mb-4">
                            <p><strong>Mô tả (Chức năng):</strong></p>
                            <textarea rows="6" disabled><?= $info['desc'] ?></textarea>
                        </div>
                        <div class="col-12 mb-4">
                            <p><strong>Danh sách nhân sự:</strong></p>
                            <table class="table table-bordered table-hover departments-detail__info-table w-100">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Mã nhân viên</th>
                                        <th>Tên</th>
                                        <th>Chức vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    foreach($employees as $employee) {
                                        $position = convert_position($employee->position);
                                        echo "<tr>
                                                <td>$employee->emp_id</td>
                                                <td>$employee->last_name $employee->first_name</td>
                                                <td>$position</td>
                                            </tr>";
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-11 alert alert-danger ml-3 <?= $d ?>">Phòng ban chưa có trưởng phòng, vui lòng bổ nhiệm trưởng phòng.</div>
                        <div class="col-11 form-message ml-3 alert d-none"></div>
                        <div class="col-12 mb-4 mt-2">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#departmentEditModal">Chỉnh sửa</button>
                                <button type="button" class="btn btn-success ml-2" data-toggle="modal"
                                    data-target="#departmentAppointModal">Bổ nhiệm</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="departmentEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="<?= $info['id'] ?>" class="form-branch">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Sửa thông tin phòng ban</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="department-edit-name" class="col-form-label">Tên phòng ban:</label>
                            <input type="text" class="form-control" id="department-edit-name"
                                name="name" rules="required" autocomplete="off">
                            <div class="error-message pl-1"></div>
                        </div>
                        <div class="form-group">
                            <label for="department-edit-id" class="col-form-label">Mã phòng ban:</label>
                            <input type="text" class="form-control" id="department-edit-name"
                                name="id" rules="required|max:2" autocomplete="off">
                            <div class="error-message pl-1"></div>
                        </div>
                        <div class="form-group">
                            <label for="department-edit-desc" class="col-form-label">Mô tả:</label>
                            <textarea class="form-control w-100 text-left" id="department-edit-desc"
                                rows="6" name="desc" rules="required"></textarea>
                            <div class="error-message pl-1"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button onclick="Validator('.form-branch','updatebranch')" type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Appoint -->
    <div class="modal fade" id="departmentAppointModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="<?= $info['id'] ?>" class="form-branch-c">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Bổ nhiệm trưởng phòng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="department-appoint-id" class="col-form-label">Chọn nhân viên:</label>
                            <select id="department-appoint-id" class="form-control" name="nv" rules="required">
                                <option value="">-Chọn nhân viên-</option>
                            <?php 
                                foreach($employees as $employee) {
                                    echo "<option value='$employee->emp_id'>$employee->last_name $employee->first_name</option>";
                                }
                            ?>
                            </select>
                            <div class="error-message pl-1"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button onclick="Validator('.form-branch-c','appointbranch')" type="submit" class="btn btn-primary">Bổ nhiệm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php require_once('../../components/footer.php') ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    <script src="/main.js"></script>
</body>
</html>