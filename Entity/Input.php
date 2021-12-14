<?php

/**
 * Class Input
 * For GET and POST request
 */

class Input {

    /**
     * Test existing Request
     * @param string $type: type of request
     * @return bool
     */
    public static function exists($type = 'post') {
        switch($type) {
            case 'post':
                return (!empty($_POST)) ? true : false;
            break;
            case 'get':
                return (!empty($_GET)) ? true : false;
            break;
            default:
                return false;
            break;
        }
    }

    /**
     * Get request value
     * @param $item: request name at Request array
     * @return mixed|string
     */
    public static function get($item) {
        
        if(isset($_POST[$item])) {
            return $_POST[$item] ;
        } else if(isset($_GET[$item])) {
            return $_GET[$item];
        }
        return '';
    }
}