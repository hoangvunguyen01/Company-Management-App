<?php 
    function get_connection() {
        $host = 'mysql-server';
        $user = 'root';
        $pass = 'root';
        $db = 'company';
        
        $conn = new mysqli($host,$user,$pass,$db);
        
        if($conn->connect_error) {
            die('Không thể kết nối database: '. $conn->connect_error);
        }
        
        return $conn;
    }
?>