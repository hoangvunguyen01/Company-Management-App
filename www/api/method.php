<?php 
    function string_types($array) {
        $result = "";
        foreach($array as $element) {
            if(is_string($element)) {
                $result .= "s";
            }
            if(is_int($element)) {
                $result .= "i";
            }
        }
        return $result;
    }
?>