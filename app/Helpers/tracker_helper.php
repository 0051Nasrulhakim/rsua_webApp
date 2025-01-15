<?php

    if(!function_exists("getLastQuery")) {
        
        function getLastQuery() {
            $db = \Config\Database::connect();
            return $db->getLastQuery();
        }
        
    }
?>