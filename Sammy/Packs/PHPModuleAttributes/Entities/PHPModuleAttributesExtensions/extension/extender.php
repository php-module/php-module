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
	 * Extender
	 */
	trait Extender {
		/**
		 * [_confextends description]
		 * @param  string $args [description]
		 * @param  array  $libs [description]
		 * @return null
		 */
		private static final function _confextends ($args) {
			# libs
			# The libraries list to configure by the
			# php module configuration files.
			$libs = func_get_arg ((-1) + func_num_args());

			if (!(is_array($libs) && $libs))
				return;

			$libsCount = count (
				$libs
			);

			for ($i = 0; $i < $libsCount; $i++) {
				$libraryCore = \php\requires ($libs [ $i ]);
				# make sure the 'libraryCore'
				# contains an array and the 
				# array is not a null or empty
				# array
				if (is_array($libraryCore) && $libraryCore) {
					self::phpModuleConfigure ($libraryCore);
				}
			}
		}
	}
}
