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

			if (!(is_string ($module) && is_string ($module))) {
				return;
			}

			$re = '/([\\\\\/]+)$/';
			$ds = DIRECTORY_SEPARATOR;
			$module = preg_replace ( $re, '',
				preg_replace ('/^([\\\\\/]+)/', '', $module)
			);
			$moduleName = pathinfo ($module,
				PATHINFO_FILENAME
			);

			$moduleDirectory = (preg_replace ($re, '', $moduleDirectoryBase).
				$ds . $module
			);

			return [
				($moduleDirectory . $ds .'index'),
				($moduleDirectory . $ds . $moduleName),
				($moduleDirectory),
				($module),
				($module . $ds . $moduleName),
				($module . $ds .'index')
			];
		}
	}
}
