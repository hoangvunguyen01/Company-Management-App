<?php
//CRUD
    require_once('db.php');
    require_once('method.php');

    function add_account($username,$type) {
        $password = password_hash($username,PASSWORD_BCRYPT);

        $sql = 'INSERT INTO account(username,password,account_type) VALUES (?,?,?)';

        $conn = get_connection();
        $stm = $conn->prepare($sql);
        $stm->bind_param('ssi',$username,$password,$type);
        $stm->execute();

        return $stm->affected_rows == 1;
    }

    function get_account($columns_conf,$datas_conf) {
        $sql = "SELECT * FROM account WHERE ";

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
        else {
            $result->data_seek(0);
            $row = $result->fetch_assoc();
            return $row;
        }
    }

    function update_account($columns_update,$datas_update,$column_conf,$data_conf) {
        if(count($columns_update) == count($datas_update)) {
            $sql = 'UPDATE account SET ';
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

    
?>