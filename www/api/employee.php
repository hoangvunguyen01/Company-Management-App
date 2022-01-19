<?php 
    require_once('db.php');
    require_once('method.php');

    function add_employee($data,$avatar) {
        $id = auto_emp_id($data[10],$data[6]);

        $sql = 'INSERT INTO employee(emp_id,first_name,last_name,birth_day,gender,
                id_card,address,position,phone,email,email_company,branch_id,
                account_id,salary,start_date,avatar) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        
        $types = "s".string_types($data)."b";
        $avatar = file_get_contents($avatar);
        array_unshift($data,$id);
        array_push($data,$avatar);
        
        $conn = get_connection();
        $stm = $conn->prepare($sql);
        $stm->bind_param($types,...$data);
        $stm->send_long_data(15,$avatar);
        $stm->execute();

        return $stm->affected_rows == 1;
    }

    function count_employee($column,$data) {
        $sql = "SELECT COUNT(emp_id) FROM employee WHERE $column = ?";
        $conn = get_connection();
        $stm = $conn->prepare($sql);
        $type = string_types([$data]);
        $stm->bind_param($type,$data);
        $stm->execute();
        $result = $stm->get_result();
        $count = $result->fetch_assoc()['COUNT(emp_id)'];
        return $count;
    }

    function auto_emp_id($branchID,$position) {
        $count = count_employee("branch_id",$branchID);

        switch ($position) {
            case 3:
                $id = $branchID.'D';
                break;
            case 2:
                $id = $branchID.'M';
                break;
            default:
                $id = $branchID.'E';
                break;
        }

        $stt = $count + 1;
        if($stt < 10) {
            return $id."00$stt";
        } 
        elseif($stt < 100) {
            return $id."0$stt";
        }
        elseif($stt < 1000) {
            return $id.$stt;
        }
        else {
            error_response(1,"Vượt quá sô lượng lưu trữ, vui lòng yêu cầu gọi admin hỗ trợ.");
        }
    }

    function get_employee($columns_info,$columns_conf,$datas_conf,$columns2,$data2,$limit) {
        $sql = "SELECT ";
        foreach($columns_info as $col) {
            $sql .= "$col, ";
        }
        $sql = substr($sql,0,-2);
        $sql .= " FROM employee WHERE ";
        foreach($columns_conf as $col) {
            $sql .= "$col = ? and ";
        }

        if(count($columns2) > 0) {
            foreach($columns2 as $col) {
                $sql .= "$col != ? and ";
            }
            $datas_conf = array_merge($datas_conf,$data2);
        }
        $sql = substr($sql,0,-5);
        $sql = $sql." ORDER BY emp_id desc LIMIT $limit,15";

        $types = string_types($datas_conf);
        $conn = get_connection();
        $stm = $conn->prepare($sql);
        $stm->bind_param($types,...$datas_conf);
        $stm->execute();
        $result = $stm->get_result();
        if($result->num_rows < 1) {
            return false;
        }

        $array_result = array();
        for($row_no = 0; $row_no < $result->num_rows; $row_no++) {
            $result->data_seek($row_no);
            $row = $result->fetch_assoc();
            $obj =  array();
            foreach($columns_info as $col) {
                $obj[$col] = strcmp($col,"birth_day") || strcmp($col,"start_date")  ? $row[$col] : date("d-m-Y", strtotime($row[$col]));
            }
            array_push($array_result, (object) $obj);
        }
        return $array_result;
    }

    function get_info_employee($column_id,$id) {
        $sql = "SELECT * FROM employee WHERE $column_id = ?";
        $types = string_types([$id]);
        $conn = get_connection();
        $stm = $conn->prepare($sql);
        $stm->bind_param($types,$id);
        $stm->execute();
        $result = $stm->get_result();
        if($result->num_rows < 1) {
            $info = false;
        } else {
            $result->data_seek(0);
            $row = $result->fetch_assoc();
            $info = array(
                "name" => $row['last_name']." ".$row['first_name'],
                "emp_id" => $row['emp_id'],
                "start_date" => date("d-m-Y", strtotime($row['start_date'])),
                "branch_id" => $row['branch_id'],
                "position" =>  convert_position($row['position']),
                "phone" => $row['phone'],
                "email" => $row['email'],
                "email_company" => $row['email_company'],
                "id" => $row['id_card'],
                "gender" => $row['gender'] == 1 ? "Nam" : "Nữ",
                "birth_day" => date("d-m-Y", strtotime($row['birth_day'])),
                "address" => $row['address'],
                "salary" => $row['salary'],
                "days_off" => $row['days_off'],
                "avatar" => $row['avatar'],
                "account_id" => $row['account_id']
            );
        }

        return $info;
    }

    function convert_position($position) {
        switch ($position) {
            case 3:
                $str = 'Giám đốc';
                break;
            case 2:
                $str = 'Trưởng phòng';
                break;
            default:
                $str = 'Nhân viên';
                break;
        }
        
        return $str;
    }

    function update_employee($columns_update,$datas_update,$columns_conf,$datas_conf) {
        $sql = 'UPDATE employee SET ';
        foreach($columns_update as $col) {
            $sql = $sql."$col = ?, ";
        }
        $sql = substr($sql,0,-2);
        $sql .= " WHERE ";
        foreach($columns_conf as $col) {
            $sql .= "$col = ? and ";
        }
        $sql = substr($sql,0,-5);
        
        $types = string_types($datas_update).string_types($datas_conf);
        $params = array_merge($datas_update,$datas_conf);

        $conn = get_connection();
        $stm = $conn->prepare($sql);
        $stm->bind_param($types,...$params);
        $stm->execute();

        return $stm->affected_rows > 0;
    }

    function update_avatar($avatar,$id) {
        $sql = 'UPDATE employee SET avatar = ? WHERE account_id = ?';
        $conn = get_connection();
        $stm = $conn->prepare($sql);
        $stm->bind_param('bi',$avatar,$id);
        $stm->send_long_data(0,$avatar);
        $stm->execute();

        return $stm->affected_rows == 1;
    }

    function update_id($old,$new,$columns_conf,$datas_conf) {
        $sql = "UPDATE employee SET emp_id = REPLACE(emp_id,?,?) WHERE ";
        foreach($columns_conf as $col) {
            $sql .= "$col = ? and ";
        }
        $sql = substr($sql,0,-5);

        $types = "ss".string_types($datas_conf);
        array_unshift($datas_conf,$old,$new);

        $conn = get_connection();
        $stm = $conn->prepare($sql);
        $stm->bind_param($types,...$datas_conf);
        $stm->execute();

        return $stm->affected_rows > 0;
    }

    function update_dayoff($number,$columns_conf,$datas_conf) {
        $sql = "UPDATE employee SET days_off = days_off + ? WHERE ";
        foreach($columns_conf as $col) {
            $sql .= "$col = ? and ";
        }
        $sql = substr($sql,0,-5);

        $types = "i".string_types($datas_conf);
        array_unshift($datas_conf,$number);

        $conn = get_connection();
        $stm = $conn->prepare($sql);
        $stm->bind_param($types,...$datas_conf);
        $stm->execute();

        return $stm->affected_rows > 0;
    }
?>
