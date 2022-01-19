<?php 
    //form_id = F + Mã phòng ban + số đơn
    require_once("db.php");
    require_once("method.php");

    function add_form($data,$file) {
        if(count($file) == 0) {
            $sql = 'INSERT INTO form(form_id,submit_day,start_day,number_day,
                reason,branch_id,emp_id,manager_id) VALUES (?,?,?,?,?,?,?,?)';
            $types_file = ''; 
            
        } else {
            $sql = 'INSERT INTO form(form_id,submit_day,start_day,number_day,
                reason,branch_id,emp_id,manager_id,file_name,file_type,file) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?)';
            $file[2] = file_get_contents($file[2]);
            $types_file = 'ssb'; 
        }

        $id = auto_form_id($data[4]);
        if($id) {
            $types = "s".string_types($data).$types_file;
            
            $data = array_merge($data,$file);
            array_unshift($data,$id);
            $conn = get_connection();
            $stm = $conn->prepare($sql);
            $stm->bind_param($types,...$data);
    
            if(count($file) != 0)
                $stm->send_long_data(10,$data[10]);
            $stm->execute();
    
            return $stm->affected_rows == 1;
        } else {
            return false;
        }
    }

    function get_info_form($column_id,$id) {
        $sql = "SELECT * FROM form WHERE $column_id = ?";

        $conn = get_connection();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$id);
        $stm->execute();
        $result = $stm->get_result();

        if($result->num_rows < 1) {
            return false;
        } else {
            $result->data_seek(0);
            $row = $result->fetch_assoc();
            return $row;
        }
    }

    function get_form($columns_info,$columns_conf,$datas_conf,$columns2,$data2,$limit) {
        $sql = "SELECT ";
        foreach($columns_info as $col) {
            $sql .= "$col, ";
        }
        $sql = substr($sql,0,-2);
        $sql .= " FROM form WHERE ";
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
        $sql = $sql." ORDER BY submit_day desc LIMIT $limit,15";

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
            $array_day = array('submit_day','start_day','result_day');
            foreach($columns_info as $col) {
                $obj[$col] =  !in_array($col,$array_day) ? $row[$col] : date("d-m-Y", strtotime($row[$col]));
            }
            array_push($array_result, (object) $obj);
        }
        return $array_result;
    }

    function count_form($column,$data) {
        $sql = "SELECT COUNT(form_id) FROM form WHERE $column = ?";
        $conn = get_connection();
        $stm = $conn->prepare($sql);
        $type = string_types([$data]);
        $stm->bind_param($type,$data);
        $stm->execute();
        $result = $stm->get_result();
        $count = $result->fetch_assoc()['COUNT(form_id)'];
        return $count;
    }

    function auto_form_id($branchID) {
        $count = count_form("branch_id",$branchID);
        $id = "F".$branchID;
        $stt = $count + 1;
        if($stt < 10) {
            return $id."000$stt";
        } 
        elseif($stt < 100) {
            return $id."00$stt";
        }
        elseif($stt < 1000) {
            return $id."0$stt";
        }
        elseif($stt < 10000) {
            return $id.$stt;
        }
        else {
            return false;
        }
    }

    function update_form($columns_update,$datas_update,$column_conf,$data_conf) {
        if(count($columns_update) == count($datas_update)) {
            $sql = 'UPDATE form SET ';
            foreach($columns_update as $col) {
                $sql = $sql."$col = ?, ";
            }
            $sql = substr($sql,0,-2);
            $sql = $sql." WHERE $column_conf = ?";
            $types = string_types($datas_update).string_types(array($data_conf));
            $params = array_merge($datas_update,array($data_conf));

            $conn = get_connection();
            $stm = $conn->prepare($sql);
            $stm->bind_param($types,...$params);
            $stm->execute();

            return $stm->affected_rows == 1;
        }

        return false;
    }

    function format_form($status) {
        switch ($status) {
            case 3:
                $text_color = 'text-white';
                $bg = 'bg-danger';
                $text = 'Từ chối';
                return [$text_color,$bg,$text];
            case 2: 
                $text_color = 'text-white';
                $bg = 'bg-success';
                $text = 'Đồng ý';
                return [$text_color,$bg,$text];
            default:
                $text_color = '';
                $bg = '';
                $text = 'Đang chờ duyệt';
                return [$text_color,$bg,$text];
        }
    }

    function update_form_id($old,$new,$columns_conf,$datas_conf) {
        $sql = "UPDATE form SET form_id = REPLACE(form_id,?,?) WHERE ";
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
?>