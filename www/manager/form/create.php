<?php require_once("../../checked.php") ?>
<?php require_once("../check.php") ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo đơn</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="/style.css">
</head>

<body>
    <?php 
        require_once('../../components/header.php'); 
        require_once('../../api/employee.php');
        $dayoff = get_employee(['days_off'],['account_id'],[$account['account_id']],[],[],0)[0]->days_off;
    ?>
    <div class="container dayoffs-create pt-2 pb-4 px-4">
        <h2 class="dayoffs-create__title">Tạo đơn xin nghỉ phép</h2>
        <div class="row px-3">
            <div class="col-12 col-lg-8 offset-lg-2 mt-3 accounts-create__wrapper dayoffs-create__wrapper">
                <form id="create-form">
                    <div class="accounts-detail__group mt-4">
                            <h5>Thông tin: </h5>
                            <div class="row px-4">
                                <div class="col-12 col-sm-4 mb-4">
                                    <p><strong>Số ngày nghỉ phép/năm:</strong></p>
                                    <input type="text" value="15" disabled>
                                </div>
                                <div class="col-12 col-sm-4 mb-4">
                                    <p><strong>Số ngày đã nghỉ:</strong></p>
                                    <input type="text" value="<?= $dayoff ?>" disabled>
                                </div>
                                <div class="col-12 col-sm-4 mb-4">
                                    <p><strong>Số ngày nghỉ còn lại:</strong></p>
                                    <input type="text" value="<?= 15 - $dayoff ?>" disabled>
                                </div>
                            </div>
                    </div>
                    <div class="form-row mt-4">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold" for="dayoffs-start">Ngày bắt đầu nghỉ:</label>
                            <input name="start_day" type="date" class="form-control" id="dayoffs-start" rules="required|minDate:3">
                            <div class="error-message pl-2"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold" for="dayoffs-num">Số ngày xin nghỉ:</label>
                            <select name="number" id="dayoffs-num" class="form-control" rules="required">
                                <option value="1" selected>1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="dayoffs-reason">Lý do nghỉ:</label>
                        <textarea type="text" class="form-control" rows="4" id="dayoffs-reason" placeholder="Lý do..."
                        rules="required" name="reason"></textarea>
                        <div class="error-message pl-2"></div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" for="dayoffs-create-file">File đính kèm (nếu có):</label>
                        <div class="custom-file">
                            <input onchange="handleChangeFile('#dayoffs-create-file','.custom-file-label')" type="file" class="custom-file-input" id="dayoffs-create-file">
                            <label class="custom-file-label" for="dayoffs-create-file">Choose file...</label>
                        </div>
                    </div>
                    <div class="form-message alert d-none"></div>
                    <div class="d-flex justify-content-end mt-3">
                        <button onclick="Validator('#create-form','createform')" type="submit" class="btn btn-success px-4">Nộp</button>
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