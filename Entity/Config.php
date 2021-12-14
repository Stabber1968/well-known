<?php

    /**
     * Class for Global value
     */

class Config {

    /**
     * Get from Global variable.
     * @param null $path: The path of value in Global variable
     * @return bool|mixed: Return parameter of value following path in Global variable
     */
    public static function get($path = null) {
        if($path) {
            $config = $GLOBALS['config'];
            $path = explode('/', $path);
            
            foreach($path as $bit) {
                if(isset($config[$bit])) {
                    $config = $config[$bit];
                }
            }
           
            return $config;
        }
        return false;
    }   
}