<?php
    require_once('db.php');

    function get_all_header($type) {
        $sql = 'SELECT * FROM header WHERE account_type <= ?';
        $conn = get_connection();
        $stm = $conn->prepare($sql);
        $stm->bind_param('i',$type);
        $stm->execute();
        $result = $stm->get_result();

        $array_result = array();
        for($row_no = 0; $row_no < $result->num_rows; $row_no++) {
            $result->data_seek($row_no);
            $row = $result->fetch_assoc();
            array_push($array_result, (object) [
                'name' => $row["nav_name"],
                'icon' => $row['nav_icon'],
                'href' => $row['nav_href']
            ]);
        }
        return $array_result;
    }
?>