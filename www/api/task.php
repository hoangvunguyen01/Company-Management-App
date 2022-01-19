<?php 
    //task_id = T + Mã phòng ban + số đơn
    require_once("db.php");
    require_once("method.php");

    function add_task($data) {
        $sql = 'INSERT INTO task(task_id,name,description,start_day,end_day,
                branch_id,executant_id,creator_id) VALUES (?,?,?,?,?,?,?,?)';

        $types = string_types($data);
        $conn = get_connection();
        $stm = $conn->prepare($sql);
        $stm->bind_param($types,...$data);
        $stm->execute();

        return $stm->affected_rows == 1;
    }

    function get_info_task($column_id,$id) {
        $sql = "SELECT * FROM task WHERE $column_id = ?";

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

    function get_task($columns_info,$columns_conf,$datas_conf,$columns2,$data2,$limit) {
        $sql = "SELECT ";
        foreach($columns_info as $col) {
            $sql .= "$col, ";
        }
        $sql = substr($sql,0,-2);
        $sql .= " FROM task WHERE ";
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
        $sql = $sql." ORDER BY task_id desc LIMIT $limit,15";
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
            $array_day = array('end_day','start_day');
            foreach($columns_info as $col) {
                $obj[$col] =  !in_array($col,$array_day) ? $row[$col] : date("d-m-Y", strtotime($row[$col]));
            }
            array_push($array_result, (object) $obj);
        }
        return $array_result;
    }

    function count_task($column,$data) {
        $sql = "SELECT COUNT(task_id) FROM task WHERE $column = ?";
        $conn = get_connection();
        $stm = $conn->prepare($sql);
        $type = string_types([$data]);
        $stm->bind_param($type,$data);
        $stm->execute();
        $result = $stm->get_result();
        $count = $result->fetch_assoc()['COUNT(task_id)'];
        return $count;
    }

    function auto_task_id($branchID) {
        $count = count_task("branch_id",$branchID);
        $id = "T".$branchID;
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

    function update_task($columns_update,$datas_update,$column_conf,$data_conf) {
        $sql = 'UPDATE task SET ';
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

    function format_task($status) {
        switch ($status) {
            case 6:
                $text_color = 'text-white';
                $bg = 'bg-success';
                $text = 'Hoàn thành';
                $d = 'd-flex';
                break;
            case 5:
                $text_color = 'text-white';
                $bg = 'bg-danger';
                $text = 'Từ chối';
                $d = 'd-none';
                break;
            case 4: 
                $text_color = 'text-white';
                $bg = 'bg-secondary';
                $text = 'Hủy';
                $d = 'd-none';
                break;
            case 3:
                $text_color = 'text-white';
                $bg = 'bg-warning';
                $text = 'Đang chờ duyệt';
                $d = 'd-none';
                break;
            case 2: 
                $text_color = 'text-white';
                $bg = 'bg-info';
                $text = 'Đang thực hiện';
                $d = 'd-none';
                break;
            default:
                $text_color = '';
                $bg = '';
                $text = 'Mới';
                $d = 'd-none';
                break;
        }
        return [$text_color,$bg,$text,$d];
    }

    function update_task_id($old,$new,$columns_conf,$datas_conf) {
        $sql = "UPDATE task SET task_id = REPLACE(task_id,?,?) WHERE ";
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