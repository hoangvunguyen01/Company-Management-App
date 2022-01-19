<?php 
    require_once("db.php");
    require_once("method.php");

    function add_message($data) {
        $sql = "INSERT INTO message(name,message,executant,date,task_id)
                VALUES (?,?,?,?,?)";
        
        $types = string_types($data);
        $conn = get_connection();
        $stm = $conn->prepare($sql);
        $stm->bind_param($types,...$data);
        $stm->execute();

        return $stm->affected_rows == 1;
    }

    function get_message($columns_conf,$datas_conf) {
        $sql = "SELECT * FROM message WHERE ";

        foreach($columns_conf as $col) {
            $sql .= "$col = ? and ";
        }
        $sql = substr($sql,0,-5);
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
            array_push($array_result, (object) [
                'id' => $row['id'],
                'name' => $row['name'],
                'message' => $row['message'],
                'executant' => $row['executant'],
                'datetime' => $row['date']
            ]);
        }
        return $array_result;
    }
?>