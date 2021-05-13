<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @namespace php\module
 * - Autoload, application dependencies
 */
namespace php\module {
	/**
	 * InitCl
	 */
	trait InitCl {
		/**
		 * [init description]
		 * @return null
		 */
		private static function init () {
			self::$parsers = [
				'.txt' => function ($file) {
					return file_get_contents (
						$file
					);
				}
			];
		}
	}
}
