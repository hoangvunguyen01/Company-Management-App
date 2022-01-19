<?php 
    
    require_once("db.php");
    require_once("method.php");

    function add_file($file,$data) {
        $types = 'ssb'.string_types($data);
        $sql = 'INSERT INTO file(name,type,file,submit,date,task_id) 
                VALUES (?,?,?,?,?,?)';
        
        $data = array_merge($file,$data);
        $conn = get_connection();
        $stm = $conn->prepare($sql);
        $stm->bind_param($types,...$data);
        $stm->send_long_data(2,$data[2]);
        $stm->execute();
        
        return $stm->affected_rows == 1;
    }

    function get_files($columns_conf,$datas_conf) {
        $sql = "SELECT * FROM file WHERE ";

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
                'type' => $row['type'],
                'file' => $row['file'],
                'submit' => $row['submit'],
                'date' => date("d-m-Y H:i:s", strtotime($row['date']))
            ]);
        }
        return $array_result;
    }
?>