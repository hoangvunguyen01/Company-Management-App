<?php 
    if($account['account_type'] != 1) {
        unset($_SESSION['account']);
        header("Location: /error.php");
        exit();
    }
?>