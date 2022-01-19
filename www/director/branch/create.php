<?php require_once("../../checked.php") ?> 
<?php require_once("../check.php") ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo phòng ban mới</title>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="/style.css">
</head>

<body>
    <?php require_once('../../components/header.php'); ?>

    <div class="container departments-create pt-2 pb-5">
        <h2 class="departments-create__title">Tạo phòng ban</h2>
        <div class="row px-3">
            <div class="col-12 col-lg-8 offset-lg-2 mt-3 departments-create__wrapper">
                <form id="create-branch" >
                    <div class="form-group">
                        <label class="font-weight-bold" for="departmentName">Tên phòng ban:</label>
                        <input type="text" class="form-control" name="name" rules="required" id="departmentName" placeholder="Tên phòng ban" autocomplete="off">
                        <div class="error-message pl-1"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold" for="departmentID">Mã phòng ban:</label>
                            <input type="text" class="form-control" name="id" rules="required|max:2" id="departmentID" placeholder="Mã phòng ban" maxlength="2">
                            <div class="error-message pl-1"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold" for="departmentNum">Số phòng:</label>
                            <input type="text" class="form-control" name="room" rules="required" id="departmentNum" placeholder="Số phòng">
                            <div class="error-message pl-1"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="departmentDesc">Mô tả (Chức năng):</label>
                        <textarea type="text" class="form-control" name="desc" rules="required" id="departmentDesc" rows="6" placeholder="Mô tả..."></textarea>
                        <div class="error-message pl-1"></div>
                    </div>
                    <div class="form-message alert d-none"></div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="reset" class="btn btn-primary mr-2">Nhập lại</button>
                        <button onclick="Validator('#create-branch','createbranch')" type="submit" class="btn btn-success">Xác nhận</button>
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