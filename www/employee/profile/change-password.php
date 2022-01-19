<?php require_once("../../checked.php") ?>
<?php require_once("../check.php") ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="/style.css">
</head>

<body>
    <?php require_once('../../components/header.php'); ?>

    <div class="container accounts-change-password pt-2 pb-5">
        <h2 class="accounts-change-password__title">Đổi mật khẩu</h2>
        <div class="row">
            <div class="col-10 offset-1 col-sm-6 offset-sm-3 accounts-change-password__wrapper">
                <form id="form-password" novalidate>
                    <!-- Old password -->
                    <div class="form-group">
                        <label class="font-weight-bold" for="oldPassword">Mật khẩu cũ:</label>
                        <div class="input-group">
                            <input name="oldPassword" type="password" class="input form-control" id="oldPassword"
                                placeholder="Nhập mật khẩu cũ" rules='required|min:6' aria-label="oldPassword" autocomplete="off"
                                aria-describedby="basic-addon1"/>
                            <div class="input-group-append">
                                <span class="input-group-text accounts-change-password__eye" onclick="handleShowHide(this);">
                                    <i class="fas fa-eye" id="show_eye"></i>
                                    <i class="fas fa-eye-slash d-none" id="hide_eye"></i>
                                </span>
                            </div>
                        </div>
                        <div class="error-message pl-1"></div>
                    </div>

                    <!-- New password -->
                    <div class="form-group">
                        <label class="font-weight-bold" for="newPassword">Mật khẩu mới:</label>
                        <div class="input-group">
                            <input name="newPassword" type="password" value="" class="input form-control" id="newPassword"
                                placeholder="Nhập mật khẩu mới" rules='required|min:6' aria-label="newPassword" autocomplete="off"
                                aria-describedby="basic-addon1" />
                            <div class="input-group-append">
                                <span class="input-group-text accounts-change-password__eye" onclick="handleShowHide(this);">
                                    <i class="fas fa-eye" id="show_eye"></i>
                                    <i class="fas fa-eye-slash d-none" id="hide_eye"></i>
                                </span>
                            </div>
                        </div>
                        <div class="error-message pl-1"></div>
                    </div>

                    <!-- Retype password -->
                    <div class="form-group">
                        <label class="font-weight-bold" for="retypePassword">Xác nhận mật khẩu mới:</label>
                        <div class="input-group">
                            <input name="retypePassword" type="password" value="" class="input form-control" id="retypePassword"
                                placeholder="Nhập lại mật khẩu mới" rules='required|min:6' aria-label="oldPassword" autocomplete="off"
                                aria-describedby="basic-addon1" />
                            <div class="input-group-append">
                                <span class="input-group-text accounts-change-password__eye" onclick="handleShowHide(this);">
                                    <i class="fas fa-eye" id="show_eye"></i>
                                    <i class="fas fa-eye-slash d-none" id="hide_eye"></i>
                                </span>
                            </div>
                        </div>
                        <div class="error-message pl-1"></div>
                    </div>

                    <div>Để bảo mật, mật khẩu nên có:</div>
                    <ul class="accounts-change-password__advice">
                        <li>
                            <i class="fas fa-check text-success"></i>
                            Ít nhất 8-32 ký tự
                        </li>
                        <li>
                            <i class="fas fa-check text-success"></i>
                            Bao gồm ký tự viết hoa và viết thường
                        </li>
                        <li>
                            <i class="fas fa-check text-success"></i>
                            Bao gồm ký tự số
                        </li>
                    </ul>
                    
                    <!-- Error Message -->
                    <div class="form-message alert d-none"></div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="reset" class="btn btn-primary mr-2">Nhập lại</button>
                        <button onclick="Validator('#form-password','changepassword')" type="submit" class="btn btn-success">Xác nhận</button>
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