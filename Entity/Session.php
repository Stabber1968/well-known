<?php


/**
 * Class Session
 */
class Session {

    /**
     * Test existing session of user
     * @param $name: Username
     * @return bool
     */
    public static function exists($name) {
        return (isset($_SESSION[$name])) ? true : false;
    }

    /**
     * Put session of user
     * @param $name: username
     * @param $value: value of user
     * @return mixed
     */
    public static function put($name, $value) {
        return $_SESSION[$name] = $value;
    }

    /**
     * Get username of user at session
     * @param $name
     * @return mixed
     */
    public static function get($name) {
        return $_SESSION[$name];
    }

    /**
     * Delete user at session
     * @param $name: Username
     */
    public static function delete($name) {
        if(self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    public static function flash($name, $string = '') {
        if(self::exists($name)) {
            $session - self::get($name);
            self::delete($name);
            return $session;
        } else {
            self::put($name, $string);
        }
    }
        
}