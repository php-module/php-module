<?php
/**
 * @version 2.0
 * @author Sammy
 * 
 * @keywords Samils, ils, php framework
 * -----------------
 * @namespace php
 * - Autoload, application dependencies
 */
namespace php {
	/**
	 * Root
	 * Absoluete path to the current directory
	 * whish phpmodule will considere as its
	 * root directory
	 */
	const Root = __DIR__;

	if (!function_exists ('php\\pm_is_php_file')) {
	function pm_is_php_file ($file = null){
		# Verify if '$file' a php file 
		return ( boolean )( is_string($file) && 
			!empty($file) && 
			in_array (strtolower (pathinfo ($file, PATHINFO_EXTENSION)), 
				preg_split ('/\s+/', 'php php5 php7')
			)
		);
	}}

	if (!function_exists ('php\\pm_autoload_directory_files')) {
	function pm_autoload_directory_files ($dir = null) {
		if (!(is_string($dir) && !empty($dir) && is_dir($dir)))
			$dir = dirname(__FILE__);

		$dir = preg_replace('/(\\|\/)+$/', '', $dir) . (
			'/*'
		);
		$dir_files = glob($dir);

		foreach ($dir_files as $key => $value) {
			if (is_dir($value)) {
				pm_autoload_directory_files ($value);
			}else{
				if (pm_is_php_file($value)) {
					include_once $value;
				}
			}
		}
	}}

	pm_autoload_directory_files ( Root . 
		'/pm_classes'
	);

	pm_autoload_directory_files ( Root . 
		'/pm_functions'
	);

	include (dirname(__FILE__) . '/index.php');
}
