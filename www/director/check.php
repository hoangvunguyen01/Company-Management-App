<?php 
    if($account['account_type'] != 3) {
        unset($_SESSION['account']);
        header("Location: /error.php");
        exit();
    }
?>