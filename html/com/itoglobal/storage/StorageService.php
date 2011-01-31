<?php

class StorageService {
	const PATH_SEPARATOR = '/';
	
	const TYPE_ALL = - 1;
	
	const TYPE_DIR = 0;
	
	const TYPE_FILE = 1;
	/**
	 * @var string - context directory name 
	 */
	const CONTEXT = 'context';
	/**
	 * @var string - images directory name 
	 */
	const IMAGES = 'images';
	/**
	 * @var string - user directory name 
	 */
	const USERS_FOLDER = 'storage/uploads/users/';
	/**
	 * @var string - bargains directory name 
	 */
	const BARGAINS_FOLDER = 'storage/uploads/bargains/';
	/**
	 * @var string - bargains directory name 
	 */
	const ORDERS_FOLDER = 'storage/uploads/orders/';
	/**
	 * @var string - user avatar directory name 
	 */
	const USER_AVATAR = 'avatar.jpg';
	/**
	 * @var string - profile directory name 
	 */
	const USER_PROFILE = '/profile/';
	/**
	 * @var string - default user avatar directory name 
	 */
	const DEF_USER_AVATAR = 'storage/uploads/default-avatar.jpg';
	/**
	 * @var string - default user avatar directory name 
	 */
	const DEF_ORDER_AVATAR = 'storage/uploads/default-order-avatar.jpg';
	/**
	 * @var string - templates directory name 
	 */
	const TEMPLATES = 'templates';
	/**
	 * @var string - style directory name
	 */
	const STYLES = 'styles';
	/**
	 * @var string - inc directory name
	 */
	const INC = 'inc';
	
	private static $exps = array ('\*', '\?', '\(', '\)', '\|' );
	
	private static $wild = array ('.*', '.', '(', ')', '|' );
	
	private static $mtypes = array ('JPG' => 'image/jpeg', 'PNG' => 'image/png', 'GIF' => 'image/gif', 'XML' => 'text/xml' );
	
	private static function getForceStoragePath($sid = null) {
		$path = $sid != null ? SESSION_PATH . $sid . self::PATH_SEPARATOR : SESSION_PATH;
		return (file_exists ( $path ) || mkdir ( $path, 0755, true )) ? $path : false;
	}
	
	public static function readDir($dir, $types = self::TYPE_ALL, $wldcrd = null) {
		$res = is_dir ( $dir ) ? glob ( $dir . '*' ) : null;
		if (is_array ( $res ) && count ( $res ) > 0) {
			switch ($types) {
				// select directories only
				case self::TYPE_DIR :
					$res = array_filter ( $res, 'is_dir' );
					break;
				// select files only
				case self::TYPE_FILE :
					$res = array_filter ( $res, 'is_file' );
					break;
				// select all ( self::TYPE_ALL )
				default :
					$res = $res;
			}
			if ($wldcrd != null) {
				// generate regular expression pattern
				$exp = '/^' . str_replace ( self::$exps, self::$wild, preg_quote ( $wldcrd ) ) . '$/is';
				// create ananonymus function
				$clb = create_function ( '$var = null, $p = "' . $exp . '"', ' return preg_match($p,$var); ' );
				// apply pattern to search results
				$res = array_filter ( $res, $clb );
			}
		} else {
			$res = $dir;
		}
		return $res;
	}
	
	public static function readImgsDir($dir) {
		return self::readDir ( $dir, self::TYPE_FILE, '*.(jpg|gif|png)' );
	}
	
	public static function initSessionStorage($sid) {
		$result = self::getForceStoragePath ( $sid );
		if ($result == false) {
			error_log ( "Error: Session data storage directory do not exists and coudn't be created." );
		}
		return $result;
	}
	
	public static function getStoragePath($sid) {
		return self::getForceStoragePath ( $sid );
	}
	
	public static function removeDir($dir, $recursive = true) {
		$list = self::readDir ( $dir );
		if (is_array ( $list )) {
			foreach ( $list as $next ) {
				if (is_dir ( $next )) {
					if ($recursive) {
						self::removeDir ( $next, $recursive );
					}
					rmdir ( $next );
				} elseif (is_file ( $next )) {
					unlink ( $next );
				} else {
					error_log ( 'Warning: Unknown directory content. RemomeDir failed at ' . $next );
					return false;
				}
			}
		} else {
			rmdir ( $list );
		}
		return true;
	}
	
	public static function clearSessionStorage($sid) {
		$path = self::getStoragePath ( $sid );
		return self::removeDir ( $path, true );
	}
	
	public static function getResourceType($file, $sid = null) {
		$file = ($sid != null) ? self::getStoragePath ( $sid ) . $file : $file;
		if (is_file ( $file )) {
			$info = pathinfo ( $file );
			$ext = strtoupper ( $info ['extension'] );
			foreach ( self::$mtypes as $key => $type ) {
				if ($ext == $key) {
					return $type;
				}
			}
		}
		return null;
	}
	
	public static function removeResource($sid, $file) {
		$absPass = self::getStoragePath ( $sid ) . $file;
		return file_exists ( $absPass ) ? unlink ( $absPass ) : false;
	}
	
	public static function createDirectory($path) {
		file_exists ( $path ) ? '' : mkdir ( $path, 0755, true );
	}
	
	public static function createFile($path, $content = null) {
		if (! file_exists ( $path )) {
			$path = fopen ( $path, 'w' ) or die ( "can't open file" );
			isset ( $content ) ? fwrite ( $path, $content ) : fwrite ( $path, $content );
			fclose ( $path );
		}
	}
	
	public static function deleteDirectory($dir) {
		if (! file_exists ( $dir ))
			return true;
		if (! is_dir ( $dir ))
			return unlink ( $dir );
		foreach ( scandir ( $dir ) as $item ) {
			if ($item == '.' || $item == '..')
				continue;
			if (! self::deleteDirectory ( $dir . self::PATH_SEPARATOR . $item ))
				return false;
		}
		return rmdir ( $dir );
	}
	
	public static function getFileContent($filename) {
		$file = file_get_contents ( $filename, true );
		return $file;
	}
	
	public static function uploadFile($path, $file) {
		if (is_uploaded_file ( $file ['tmp_name'] )) {
			$file_size = FILE_SIZE;
			if ($file ['size'] != 0 and $file ['size'] <= $file_size) {
				move_uploaded_file ( $file ['tmp_name'], $path ) ? 
					null :
						error_log ( 'Invalid mooving file to' . $path );
			}
		}
	}
}

?>