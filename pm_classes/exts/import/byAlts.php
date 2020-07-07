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
	 * ImportByAlternates
	 */
	trait ImportByAlternates {
		/**
		 * [_confextensions description]
		 * @param  string $cmdArgs
		 * @param  array  $extensions
		 * @return null
		 */
		private static final function _confextensions () {
			if (!(func_num_args() >= 1))
				return;
			# extensions
			# The extensions list to configure by the
			# php module configuration files.
			$extensions = func_get_arg ((-1) + func_num_args());

			# Configure and Setup the
			# given extension names
			self::setExtensions (
				$extensions
			);
		}

		private static final function _confextension () {
			if (!(func_num_args() >= 1))
				return;
			# extensions
			# The extensions list to configure by the
			# php module configuration files.
			$extension = func_get_arg (0);
			# Configure and Setup the
			# given extension names
			self::setExtensions (
				[$extension]
			);
		}

		/**
		 * [shouldImport description]
		 * @param  string $moduleFile [description]
		 * @return string
		 */
		static final function shouldImport ($moduleFile = '') {
			if (!(is_string($moduleFile) && $moduleFile))
				return;

			$extensionsCount = count (
				self::$extensions
			);

			for ($i = 0; $i < $extensionsCount; $i++) {
				$extension = (string) (
					self::$extensions[$i]
				);
				if (is_file ($moduleFile . $extension)) {
					return $extension;
				}
			}
		}
	}
}
