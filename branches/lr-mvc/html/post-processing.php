<?php
//TODO: Refactor this! File contains functions used at post-processing MVC tire and must be
//      replaces by the correspondig OOP implementation instead of using the procedural approach.

    function _i18n($mssg) {
        function callback($key){
            if(count($key) > 1){            	
                $result = MessageService::getInstance()->getMessage($key[1]);
            }
            return $result;
        }
        $pattern = '/_i18n\((.*?)\)/i';
        return preg_replace_callback($pattern, 'callback', $mssg);
    }
?>