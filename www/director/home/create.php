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
    <title>Create Account</title>
    <link rel="stylesheet" href="/style.css">
</head>

<body>
    <?php require_once('../../components/header.php'); ?>

    <div class="container accounts-create pt-2 pb-5">
        <h2 class="accounts-create__title">Tạo tài khoản nhân viên</h2>
        <div class="row px-3">
            <div class="col-12 col-lg-8 offset-lg-2 mt-3 accounts-create__wrapper">
                <form id="create-employee" novalidate>
                    <div class="form-group">
                        <label class="font-weight-bold" for="username">Tên tài khoản:</label>
                        <input name="username" type="text" class="form-control" id="username" placeholder="Tên tài khoản" autocomplete="off" rules="required|min:6">
                        <div class="error-message pl-2"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold" for="firstName">Họ lót:</label>
                            <input name="firstname" type="text" class="form-control" id="firstName" placeholder="Họ lót" rules="required">
                            <div class="error-message pl-2"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold" for="lastName">Tên:</label>
                            <input name="lastname" type="text" class="form-control" id="lastName" placeholder="Tên" rules="required">
                            <div class="error-message pl-2"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="font-weight-bold" for="identity ">CMND/CCCD:</label>
                            <input name="identity" type="text" class="form-control" id="identity" placeholder="CMND/CCCD" rules="required|max:12">
                            <div class="error-message pl-2"></div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="font-weight-bold" for="inputState">Giới tính:</label>
                            <select name="gender" id="inputState" class="form-control" rules="required">
                                <option value="">---Chọn giới tính---</option>
                                <option value="1">Nam</option>
                                <option value="2">Nữ</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="font-weight-bold" for="birthday">Ngày sinh:</label>
                            <input name="birthday" type="date" class="form-control" id="birthday" max="2005-01-01" rules="required">
                            <div class="error-message pl-2"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold" for="email">Email cá nhân:</label>
                            <input name="email" type="email" class="form-control" id="email" placeholder="Email" autocomplete="off" rules="required|email">
                            <div class="error-message pl-2"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold" for="phone">Số điện thoại:</label>
                            <input name="phone" type="number" class="form-control" id="phone" placeholder="Số điện thoại" rules="required|max:10">
                            <div class="error-message pl-2"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="address">Địa chỉ:</label>
                        <input name="address" type="text" class="form-control" id="address" placeholder="Địa chỉ..." rules="required">
                        <div class="error-message pl-2"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold" for="inputState">Phòng ban: </label>
                            <select name="branch" id="inputState" class="form-control" rules="required">
                                <option value="">---Chọn phòng ban---</option>
                                <?php 
                                    require_once('../../api/branch.php');
                                    $branchs = get_all_branch();
                                    foreach($branchs as $branch) {
                                        if($branch->id != 'GD') {
                                            echo "<option value='$branch->id'>$branch->name</option>";
                                        }
                                    }
                                ?>
                            </select>
                            <div class="error-message pl-2"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold" for="inputState">Chức vụ:</label>
                            <select name="position" id="inputState" class="form-control" rules="required">
                                <option value="">---Chọn chức vụ---</option>
                                <option value="1">Nhân viên</option>
                                <option value="2">Trưởng phòng</option>
                            </select>
                            <div class="error-message pl-2"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold" for="salary">Mức lương (VNĐ):</label>
                            <input name="salary" type="number" class="form-control" id="salary" placeholder="Mức lương" autocomplete="off" rules="required">
                            <div class="error-message pl-2"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold" for="startDay">Ngày sinh vào làm:</label>
                            <input name="startday" type="date" class="form-control" id="startDay" rules="required">
                            <div class="error-message pl-2"></div>
                        </div>
                    </div>
                    <div class="form-message alert d-none"></div>
                    <div class="d-flex justify-content-end mt-3">
                        <button onclick="Validator('#create-employee','createemployee')" type="submit" class="btn btn-success">Xác nhận</button>
                    </div>
                </form>
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