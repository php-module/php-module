<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @namespace Sammy\Packs\php\module
 * - Autoload, application dependencies
 *
 * MIT License
 *
 * Copyright (c) 2020 Samil
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:

 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
namespace Sammy\Packs\php\module {
	/**
	 * Root
	 * Absoluete path to the current directory
	 * whish phpmodule will considere as its
	 * root directory
	 */
	const PM_ROOT_DIR = __DIR__;
  const PM_NAMESPACE = 'Sammy\\Packs\\php\\module';

	if (!function_exists (PM_NAMESPACE . '\\pm_is_php_file')) {
	function pm_is_php_file ($file = null){
		# Verify if '$file' a php file
		return ( boolean )( is_string($file) &&
			!empty($file) &&
			in_array (strtolower (pathinfo ($file, PATHINFO_EXTENSION)),
				preg_split ('/\s+/', 'php')
			)
		);
	}}

	if (!function_exists (PM_NAMESPACE . '\\pm_autoload_directory_files')) {
	function pm_autoload_directory_files ($dir = null) {
		if (!(is_string($dir) && !empty($dir) && is_dir($dir)))
			$dir = dirname(__FILE__);

		$dir = preg_replace('/(\\|\/)+$/', '', $dir) . (
			'/*'
		);
		$dir_files = glob($dir);

		foreach ($dir_files as $key => $value) {
			if ( is_dir ($value) ) {
				pm_autoload_directory_files ( $value );
			} else {
				if (pm_is_php_file ($value)) {
					include_once $value;
				}
			}
		}
	}}

	pm_autoload_directory_files ( PM_ROOT_DIR . '/Sammy' );
  #
  # import the index file containg the php-module
  # core global function to be used when importing
  # a module.
  #
	include_once (__DIR__ . '/index.php');
}
