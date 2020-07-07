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
	 * AlternateDirectories
	 */
	trait AlternateDirectories {
		/**
		 * [init description]
		 * @return null
		 */
		public static function ImportAlternateDirectories () {
			list ($moduleDirectoryBase, $module) = func_get_args();

			if (!(is_string($module) && is_string($module)))
				return;

			$moduleName = pathinfo($module, 
				PATHINFO_FILENAME
			);

			$moduleDirectory = ($moduleDirectoryBase . DIRECTORY_SEPARATOR . 
				$module 
			);
			
			return [
				($moduleDirectory . '/index'),
				($moduleDirectory . '/' . $moduleName . ''),
				($moduleDirectory . ''),
				($module . ''),
				($module . '/' . $moduleName . ''),
				($module . '/index')
			];
		}
	}
}
