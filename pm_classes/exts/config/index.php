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
	 * Config
	 */
	trait Config {
		/**
		 * [initialize_config description]
		 * @return null
		 */
		public static function initialize_config () {
			if (self::$configInitialized)
				return;

			self::init();

			$phpModuleConfigs = \php\requires (
				self::getConfig ('extensions-file-path')
			);

			self::phpModuleConfigure (
				$phpModuleConfigs
			);
			# Set the config initialized
			# property as true to avoid
			# repeating this action.
			self::$configInitialized = (
				true
			);
		}
	}
}
