<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="/style.css">
    </head>

    <body>
        
        <div class="login">
            <div class="container h-100">
                <div class="row h-100">
                    <div class="col-12 col-sm-8 offset-sm-2 col-lg-6 offset-lg-3 d-flex flex-column justify-content-center">
                        <div class="login-content">
                            <h3 class="login-title">Đăng nhập</h3>
                            <form class="login-account mt-4">
                                <div class="form-group mb-4">
                                    <input type="text" class="form-control" name="username" id="login-username" placeholder="Nhập tên tài khoản" rules="required">
                                    <div class="error-message ml-2">
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <input type="password" class="form-control" name="password" id="login-password" placeholder="Nhập mật khẩu" rules="required|min:6">
                                    <div class="error-message ml-2">
                                    </div>
                                </div>
                                <div class="form-message alert d-none"></div>
                                <div class="d-flex justify-content-center">
                                    <button onclick="Validator('.login-account','login')" class="btn btn-success" type="submit">Đăng nhập</button>
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