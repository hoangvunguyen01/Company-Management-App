<?php 
    require_once("db.php");
    require_once("method.php");

    function add_branch($id,$name,$desc,$room,$date) {
        $sql = 'INSERT INTO branch VALUES (?,?,?,?,?)';

        $conn = get_connection();
        $stm = $conn->prepare($sql);
        $stm->bind_param('sssss',$id,$name,$desc,$room,$date);
        $stm->execute();

        return $stm->affected_rows == 1;
    }

    function get_all_branch() {
        $sql = "SELECT * FROM branch";

        $conn = get_connection();
        $result = $conn->query($sql);
        if($result->num_rows < 1) {
            return false;
        }

        $array_result = array();
        for($row_no = 0; $row_no < $result->num_rows; $row_no++) {
            $result->data_seek($row_no);
            $row = $result->fetch_assoc();
            array_push($array_result, (object) [
                'id' => $row['branch_id'],
                'name' => $row['name'],
                'desc' => $row['description'],
                'room' => $row['room'],
                'date' => date("d-m-Y", strtotime($row['date']))
            ]);
        }
        return $array_result;
    }

    function get_info_branch($column_id,$id) {
        $sql = "SELECT * FROM branch WHERE $column_id = ?";
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
                'id' => $row['branch_id'],
                'name' => $row['name'],
                'desc' => $row['description'],
                'room' => $row['room'],
                'date' => date("d-m-Y", strtotime($row['date']))
            );
        }
        return $info;
    }

    function get_branch($columns_info,$columns_conf,$datas_conf,$columns2,$data2,$limit) {
        $sql = "SELECT ";
        foreach($columns_info as $col) {
            $sql .= "$col, ";
        }
        $sql = substr($sql,0,-2);
        $sql .= " FROM branch WHERE ";
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
        $sql = $sql." ORDER BY date desc LIMIT $limit,15";
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
                $obj[$col] = strcmp($col,"date") ? $row[$col] : date("d-m-Y", strtotime($row[$col]));
            }
            array_push($array_result, (object) $obj);
        }
        return $array_result;
    }

    function convert_branch($branchs,$branch_id) {
        foreach($branchs as $branch) {
            if($branch->id == $branch_id) {
                return $branch->name;
            }
        }
    }

    function update_branch($columns_update,$datas_update,$column_conf,$data_conf) {
        $sql = 'UPDATE branch SET ';
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
?>