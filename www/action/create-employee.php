<?php 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    require_once('../api/account.php');
    require_once('../api/employee.php');
    require_once('response.php');

    if(!empty($_POST['username']) && !empty($_POST['firstname'])
    && !empty($_POST['lastname']) && !empty($_POST['identity'])
    && !empty($_POST['gender']) && !empty($_POST['birthday'])
    && !empty($_POST['email']) && !empty($_POST['phone'])
    && !empty($_POST['address']) && !empty($_POST['branch'])
    && !empty($_POST['position']) && !empty($_POST['salary']) && !empty($_POST['startday'])) {
        $username = $_POST['username'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $identity = $_POST['identity'];
        $gender = $_POST['gender'];
        $birthday = $_POST['birthday'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $branch_id = $_POST['branch'];
        $position = $_POST['position'];
        $salary = $_POST['salary'];
        $startday = $_POST['startday'];
        $email_company = substr($email,0,strpos($email,"@"))."@company.com";

        if(add_account($username,$position)) {
            $account = get_account(['username'],[$username]);
            $account_id = $account['account_id'];

            if($gender === '1') {
                $avatar = glob('../images/male-avatar.png')[0];
            } else {
                $avatar = glob('../images/female-avatar.png')[0];
            }
            
            $employee = array($firstname,$lastname,$birthday,$gender,$identity,
                    $address,$position,$phone,$email,$email_company,$branch_id,
                    $account_id,$salary,$startday);
            
            if(add_employee($employee,$avatar)) {
                success_response($employee,"Tạo account thành công");
            } else {
                error_response(1,"Tạo employee không thành công!");
            }
        } else {
            error_response(1,"Tạo account không thành công!");
        }
    } else {
        error_response(1,"Vui lòng xem lại thông tin!");
    }

?>