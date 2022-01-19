<?php require_once("../../checked.php") ?>
<?php require_once("../check.php") ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tài khoản của nhân viên</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="/style.css">
    </head>
    <body>
        <?php 
            require_once('../../components/header.php');
            require_once('../../api/employee.php');
            require_once('../../api/branch.php');
            require_once('../../api/account.php');
            if(isset($_GET['id'])) {   
                $info = get_info_employee("emp_id",$_GET['id']);
                $image = 'data:image/jpeg;base64,'.base64_encode($info['avatar']);
                $branch_info = get_branch(["name"],["branch_id"],[$info['branch_id']],[],[],0);
                $branch_name = $branch_info[0]->name;
                $account_id = $info['account_id'];
                $account = get_account(['account_id'],[$account_id]);
                $username = $account['username'];
                $days_off = $info['position'] === "Nhân viên" ? 12 : 15;
            }
        ?>

        <div class="container accounts-detail pt-2 pb-5 px-4">
            <h2 class="accounts-detail__title">Thông tin tài khoản</h2>
            <div class="row">
                <div class="col-12 col-lg-8 offset-lg-2">
                    <form id="form-profile">
                        <div class="accounts-detail__img">
                            <img 
                                id="image"
                                src="<?=$image?>" 
                                alt="Avatar"
                            >
                        </div>

                        <h3 class="text-center font-weight-bold mt-3"><?php echo $info['name'] ?></h3>
                        <div class="accounts-detail__group mt-4">
                            <h5>Thông tin cơ bản: </h5>
                            <div class="row px-4">
                                <div class="col-12 col-sm-4 mb-4">
                                    <p><strong>Mã nhân viên:</strong></p>
                                    <input type="text" value="<?php echo $info['emp_id'] ?>" disabled>
                                </div>
                                <div class="col-12 col-sm-4 mb-4">
                                    <p><strong>Tên tài khoản:</strong></p>
                                    <input type="text" value="<?php echo $username ?>" disabled>
                                </div>
                                <div class="col-12 col-sm-4 mb-4">
                                    <p><strong>Ngày vào làm:</strong></p>
                                    <input type="text" value="<?php echo $info['start_date'] ?>" disabled>
                                </div>
                                <div class="col-12 col-sm-4 mb-4">
                                    <p><strong>Mã phòng ban:</strong></p>
                                    <input type="text" value="<?php echo $info['branch_id'] ?>" disabled>
                                </div>
                                <div class="col-12 col-sm-4 mb-4">
                                    <p><strong>Tên phòng ban:</strong></p>
                                    <input type="text" value="<?php echo $branch_name ?>" disabled>
                                </div>
                                <div class="col-12 col-sm-4 mb-4">
                                    <p><strong>Chức vụ:</strong></p>
                                    <input type="text" value="<?php echo $info['position'] ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="accounts-detail__group mt-4">
                            <h5>Thông tin liên hệ: </h5>
                            <div class="row px-4">
                                <div class="col-12 col-sm-4 mb-4">
                                    <p><strong>Số điện thoại:</strong></p>
                                    <input type="text" value="<?php echo $info['phone'] ?>" disabled>
                                </div>
                                <div class="col-12 col-sm-4 mb-4">
                                    <p><strong>Email cá nhân:</strong></p>
                                    <input type="text" value="<?php echo $info['email'] ?>" disabled>
                                </div>
                                <div class="col-12 col-sm-4 mb-4">
                                    <p><strong>Email công ty:</strong></p>
                                    <input type="text" value="<?php echo $info['email_company'] ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="accounts-detail__group mt-4">
                            <h5>Lý lịch: </h5>
                            <div class="row px-4">
                                <div class="col-12 col-sm-4 mb-4">
                                    <p><strong>CMND/CCCD:</strong></p>
                                    <input type="text" value="<?php echo $info['id'] ?>" disabled>
                                </div>
                                <div class="col-12 col-sm-4 mb-4">
                                    <p><strong>Giới tính:</strong></p>
                                    <input type="text" value="<?php echo $info['gender'] ?>" disabled>
                                </div>
                                <div class="col-12 col-sm-4 mb-4">
                                    <p><strong>Ngày sinh:</strong></p>
                                    <input type="text" value="<?php echo $info['birth_day'] ?>" disabled>
                                </div>
                                <div class="col-12 col-sm-12 mb-4">
                                    <p><strong>Địa chỉ liên lạc:</strong></p>
                                    <input type="text" value="<?php echo $info['address'] ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="accounts-detail__group mt-4">
                            <h5>Thông tin khác: </h5>
                            <div class="row px-4">
                                <div class="col-12 col-sm-4 mb-4">
                                    <p><strong>Mức lương (VNĐ):</strong></p>
                                    <input type="text" value="<?php echo $info['salary'] ?>" disabled>
                                </div>
                                <div class="col-12 col-sm-4 mb-4">
                                    <p><strong>Số ngày nghỉ phép/năm:</strong></p>
                                    <input type="text" value="<?= $days_off ?>" disabled>
                                </div>
                                <div class="col-12 col-sm-4 mb-4">
                                    <p><strong>Số ngày nghỉ còn lại:</strong></p>
                                    <input type="text" value="<?php echo $days_off - $info['days_off'] ?>" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="form-message alert d-none"></div>
                        <div class="d-flex justify-content-end">
                            <button  data-toggle="modal" data-target="#resetPasswordModal"
                                type="button" class="btn btn-success mt-4 align-seft-center px-4">Reset password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="resetPasswordModalLabel">Reset mật khẩu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Bạn có chắc chắn muốn reset mật khẩu cho nhân viên <?= $info['name'] ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                        <button onclick="handleResetPassword2('<?= $account_id ?>')" type="button" class="btn btn-primary" data-dismiss="modal">Reset</button>
                    </div>
                </div>
            </div>
        </div>

        <?php require_once('../../components/footer.php') ?>
        <script src="/main.js"></script>
    </body>
</html>