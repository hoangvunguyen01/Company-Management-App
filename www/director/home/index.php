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
    <title>Trang chủ</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <?php 
        require_once('../../components/header.php'); 
        require_once('../../api/employee.php');
        require_once('../../api/branch.php');
        $employees = get_employee(['emp_id','branch_id','first_name','last_name','position','avatar'],[],[],['position'],[3],0);
        $branchs = get_all_branch();
    ?>

    <div class="container mt-4 container-height">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                <h2 class="mb-3 heading-tasks" onclick="handleReload()">Nhân viên</h2>
                <div class="list-group list-group-task d-none d-lg-block">
                <?php 
                    $tmp = 'employee';
                    foreach($branchs as $branch) {
                        if($branch->id != 'GD')
                            echo "<div id='$branch->id' 
                                    class='list-group-item list-group-item-action'
                                    onclick='handleRender(this)'
                            >$branch->name</div>";
                    }
                ?>
                </div>

                <div class="dropdown d-lg-none">
                    <button class="btn btn-primary btn-block d-flex justify-content-between"
                            type="button" id="dropdownMenu1" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        Tất cả
                        <i class="fas fa-chevron-down icon-dropdown mt-2"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <?php 
                        foreach($branchs as $branch) {
                            if($branch->id != 'GD') 
                                echo "<div id='$branch->id' 
                                    class='dropdown-item dropdown-item-subnav'
                                    onclick='handleRender(this)'>$branch->name
                                    </div>";
                        }        
                    ?>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
                <div class="row mt-4 row-employee">
            <?php 
                foreach($employees as $employee) {
                    $image = 'data:image/jpg;charset=utf8;base64,'.base64_encode($employee->avatar);
                    $branch_name = convert_branch($branchs,$employee->branch_id);
                    $position = convert_position($employee->position);
                    echo "<div class='col-6 col-sm-6 col-md-4 col-lg-4 pb-4'>\n";
                    echo "<div id='$employee->emp_id' class='card' onclick='handleDetail(this)'>\n";
                    echo "<img class='card-img-top' src='$image' alt='Avatar'>\n";
                    echo "<div class='card-body card-shadow'>\n";
                    echo "<h5 class='card-title card-text-nowrap'>$employee->last_name $employee->first_name</h5>\n";
                    echo "<p class='card-text card-text1-nowrap'>Phòng: $branch_name</p>\n";
                    echo "<p class='card-text card-text1-nowrap'>$position</p>\n";
                    echo "</div>\n";
                    echo "</div>\n";
                    echo "</div>\n";
                }
            ?>
                </div>
                <?php 
                    if($employees) {
                        if(count($employees) == 15) {
                            $table = 'employee';
                            $selector = '.card';
                            $column = 'branch_id';
                            require_once('../../components/more-btn.php');
                        }
                    }
                ?>
            </div>
        </div>

        <?php 
            require_once('../../components/plus-btn.php');
        ?>
    </div>
    <?php 
        
        require_once('../../components/footer.php'); 
    ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    <script src="/main.js"></script>
</body>
</html>