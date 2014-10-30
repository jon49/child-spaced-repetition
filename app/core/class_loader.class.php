<?php

/**
 * Class Loader
 */
class ClassLoader {

	/**
	 * Constructor
	 */
	public static function setup() {

		// Setup Include Paths
		$paths = self::get_sub_paths(ROOT . '/app');
		set_include_path(implode(PATH_SEPARATOR, $paths));

		// SPL Autoloader
		spl_autoload_register('self::load');

	}

	/**
	 * Load Class
	 */
	public static function load($class) {
		if ($file = self::class_file_exists($class)) { include($file); }
	}

	/**
	 * Class File Exists
	 */
	private static function class_file_exists($class) {
		$paths = explode(PATH_SEPARATOR, get_include_path());
		foreach ($paths AS $path) {
			$class = self::camelcase_to_underscore($class);
			$file = $path . '/' . $class . '.class.php';
			if (is_file($file)) return $file;
			
		}
		return FALSE;
	}

	/**
	 * Get Sub Paths
	 */
	private static function get_sub_paths($base_path) {
		
		try {

			// Recursive Iterator Iterator
			$rii = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator($base_path, FilesystemIterator::SKIP_DOTS), 
				RecursiveIteratorIterator::SELF_FIRST);

		} catch (Exception $e) {
			die($e->getMessage());
		}

		// Start Collecting Sub Paths
		$paths = [];
		$paths[] = $base_path;
		foreach($rii as $name => $path) {
			if ($path->isDir()) { $paths[] = $path->getPathName(); }  
		}
		return $paths;

	}

	/**
	 * Convert CamelCase to underscore_case
	 */
	private static function camelcase_to_underscore($text) {
		$text[0] = strtolower($text[0]);
		$func = create_function('$c', 'return "_" . strtolower($c[1]);');
		return preg_replace_callback('/([A-Z])/', $func, $text);
	}

}