<?php 
    session_start();
    if(!isset($_SESSION["account"])) {
        header("Location: /");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="/style.css">
</head>
<body>

    <div class="reset-password">
        <div class="container h-100">
            <div class="row h-100">
                <div class="col-12 col-sm-8 offset-sm-2 col-lg-6 offset-lg-3 d-flex flex-column justify-content-center">
                    <div class="reset-password-content">
                        <h3 class="reset-password-title">Đổi mật khẩu</h3>
                        <form class="reset-pass mt-4">
                            <div class="form-group mb-4">
                                <input type="password" class="form-control" name="username" id="reset-password-new" placeholder="Nhập mật khẩu mới" rules="required|min:6">
                                <div class="error-message ml-2"></div>
                            </div>
                            <div class="form-group mb-4">
                                <input type="password" class="form-control" name="password" id="reset-password-repeat" placeholder="Xác nhận mật khẩu mới" rules="required|min:6">
                                <div class="error-message ml-2"></div>
                            </div>
                            <div class="form-message alert d-none"></div>
                            <div class="d-flex justify-content-center">
                                <button onclick="Validator('.reset-pass','resetpassword')" class="btn btn-success mx-2" type="submit">Xác nhận</button>
                                <button onclick="handleLogOutForm('.reset-pass')" class="btn btn-danger mx-2" type="submit">Đăng xuất</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script src="/main.js"></script>
</body>
</html>