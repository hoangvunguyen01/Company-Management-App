<?php require_once("../../checked.php") ?> 
<?php require_once("../check.php") ?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chi tiết đơn nghỉ phép</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <link rel="stylesheet" href="/style.css">
</head>
<body>
    <?php 
        require_once('../../components/header.php'); 
        require_once('../../api/employee.php');
        require_once('../../api/form.php');
        if(isset($_GET['id'])) {   
            $form = get_info_form("form_id",$_GET['id']);
            $info = get_info_employee("emp_id",$form['emp_id']);
            $image = 'data:image/jpeg;base64,'.base64_encode($info['avatar']);
            $file = "data:".$form['file_type'].";base64,".base64_encode($form['file']);
            $name = $info['name']; 
            switch ($form['status']) {
                case 3:
                    $status = "Từ chối";
                    $d = 'd-none';
                    break;
                case 2:
                    $status = "Đồng ý";
                    $d = 'd-none';
                    break;
                default:
                    $status = "Đang chờ duyệt";
                    $d = 'd-flex';
                    break;
            }
            if($account['account_id'] == $info['account_id']) {
                $d = 'd-none';
            }
        }    
    ?>  

    <div class="container dayoffs-detail pt-2 px-4">
        <h2 class="dayoffs-detail__title">Đơn xin nghỉ phép</h2>
        <div class="row">
            <div class="col offset-sm-1 col-sm-10 offset-lg-2 col-lg-8">
                <div class="row">
                    <div class="col-12 col-sm-12 d-flex flex-column align-items-center">
                        <img 
                            class="dayoffs-detail__avatar"
                            src="<?= $image ?>" 
                            alt="Avatar">
                        <h3 class="dayoffs-detail__name"><?= $name ?></h3>
                    </div>
                    <div class="col-12 col-sm-12">
                        <div class="dayoffs-detail__group">
                            <div class="row">
                                <div class="col-12 col-sm-4">
                                        <label>Ngày nộp đơn: </label>
                                        <span><?= date("d-m-Y", strtotime($form['submit_day'])) ?></span>
                                </div>
                                <div class="col-12 col-sm-4">
                                        <label>Ngày nghỉ: </label>
                                        <span><?= date("d-m-Y", strtotime($form['start_day'])) ?></span>
                                </div>
                                <div class="col-12 col-sm-4">
                                        <label>Số ngày xin nghỉ: </label>
                                        <span><?= $form['number_day'] ?> ngày</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="dayoffs-detail__group dayoffs-detail__group--row">
                            <label>Trạng thái:</label>
                            <span><?= $status ?></span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="dayoffs-detail__group dayoffs-detail__group--row">
                            <label>Ngày duyệt:</label>
                            <span><?php if($form['result_day']) { echo date("d-m-Y", strtotime($form['result_day'])); } else echo "Đang chờ duyệt"; ?></span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12">
                        <div class="dayoffs-detail__group">
                            <label>Lý do xin nghỉ: </label>
                            <textarea id="" cols="30" rows="10" disabled><?= $form['reason'] ?></textarea>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12">
                        <div class="dayoffs-detail__group">
                            <label>File đính kèm: </label>
                            <a 
                                download="<?= $form['file_name'] ?>" 
                                href="<?= $file ?>">
                            <?= $form['file_name'] ?></a>
                        </div>
                    </div>
                    <div class="form-message alert d-none"></div>
                    <div class="btn-reply col-12 col-sm-12 my-3 justify-content-end <?= $d ?>">
                        <button onclick="handleRejectForm('<?=$form['form_id']?>')" type="button" class="btn btn-danger">Từ chối</button>
                        <button onclick="handleApproveForm('<?=$form['form_id']?>')" type="button" class="btn btn-success ml-3">Đồng ý</button>
                    </div>
                </div>
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