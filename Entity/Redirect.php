<?php

/**
 * Class Redirect
 */
class Redirect {

    /**
     * Redirect to location
     * @param null $location: location for redirect
     */
	public static function to($location = null) {
		if($location) {
			if(is_numeric($location)) {
				switch($location) {
					case 404:
						header('HTTP/1.0 404 Not Found');
						include '../Controllers/errors/404.php';
						exit();
					break;
                    case 400:
                        header('HTTP/1.0 400 Bad Request');
                        include '../Controllers/errors/400.php';
                        exit();
				}
			}
			header('Location: ' . $location);
			exit();
		}
	}
}