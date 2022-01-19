<?php 
    function error_response($code,$message) {
        $res = array();
        $res['code'] = $code;
        $res['message'] = $message;

        die(json_encode($res));
    }

    function success_response($data,$message) {
        $res = array();
        $res['code'] = 0;
        $res['message'] = $message;
        $res['data'] = $data;

        die(json_encode($res));
    }
?>