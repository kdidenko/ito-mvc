<?php

    function _i18n($mssg) {
        function callback($key){
            if(count($key) > 1){
                $result = MessageService::getInstance()->getMessage($key[1]);
            }
            return $result;
        }
        $pattern = '/_i18n\{(.*?)\}/i';
        return preg_replace_callback($pattern, 'callback', $mssg);
    }

?>