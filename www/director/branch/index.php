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
    <title>Phòng ban</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <?php require_once('../../components/header.php'); ?>

    <div class="container mt-4">
        <div class="row row-branch">
        <?php 
            require_once('../../api/branch.php');
            require_once('../../api/employee.php');
            $branchs = get_all_branch();
            $tps = array();
            for($no = 0; $no < count($branchs); $no++) {
                $branch = $branchs[$no];
                if($branch->id != "GD") {
                    $tmp = !get_employee(['emp_id'],['branch_id','position'],[$branch->id,2],[],[],0);
                    if($tmp) {
                        array_push($tps,(object) [
                            'no' => $no,
                            'bool' => false
                        ]);
                    } else {
                        array_push($tps,(object) [
                            'no' => $no,
                            'bool' => true
                        ]);
                    }
                    echo "<div class='col-12 col-sm-6 col-md-4 col-lg-4 mb-4'>\n";
                    echo "<div id='$branch->id' class='card' onclick='handleDetail(this)'>\n";
                    echo "<div class='card-body'>\n";
                    echo "<h4 class='card-title card-title-nowrap'>$branch->name</h4>\n";
                    echo "<h6 class='card-subtitle mb-2 text-muted'>Phòng: $branch->room</h6>\n";
                    echo "<p class='card-text card-text-nowrap'>$branch->desc</p>\n";
                    echo "</div>\n";
                    echo "</div>\n";
                    echo "</div>\n";
                }
            }
        ?>
        </div>
        <?php 
            foreach($tps as $tp) {
                if(!$tp->bool) {
                    $branch = $branchs[$tp->no];
                    echo "<div class='alert alert-danger' role='alert'>\n";
                    echo "Vui lòng chọn trưởng phòng hoặc tạo một trưởng phòng cho phòng ban <strong>$branch->name</strong> .";
                    echo "</div>";
                }
            }
        ?>
    </div>
    <?php require_once('../../components/plus-btn.php'); ?>
    <?php require_once('../../components/footer.php') ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    <script src="/main.js"></script>
</body>
</html>