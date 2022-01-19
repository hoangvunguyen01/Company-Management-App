<?php 
    if($account['account_type'] != 2) {
        unset($_SESSION['account']);
        header("Location: /error.php");
        exit();
    }
?>