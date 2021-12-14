<?php



/**
 * Class Cookie
 */
class Cookie {


    /**
     * Test existing of cookie of user
     * @param $name: The username for testing
     * @return bool
     */
	public static function exists($name) {
		return(isset($_COOKIE[$name])) ? true : false;
	}


    /**
     * Get cookie information of user
     * @param $name: The username of cookie
     * @return mixed
     */
	public static function get($name) {
		return $_COOKIE[$name];
	}


    /**
     *
     * Put user information at cookie
     * @param $name: username to put at cookie
     * @param $value: value of user
     * @param $expiry: expiry of user
     * @return bool
     */
	public static function put($name, $value, $expiry) {
		if(setcookie($name, $value, time() + $expiry, '/')) {
			return true;
		}
		return false;
	}


    /**
     * Delete user at cookie
     * @param $name: username to Delete at cookie
     */
    public static function delete($name) {
		self::put($name, '', time() -1);
	}
}