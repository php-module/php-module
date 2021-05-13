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
		public static final function shouldImport ($moduleFile = '') {
			if (!(is_string($moduleFile) && $moduleFile)) {
				return;
      }

			$extensionsCount = count ( self::$extensions );

			for ($i = 0; $i < $extensionsCount; $i++) {
				$extension = (string) ( self::$extensions [$i] );

				if (is_file ($moduleFile . $extension)) {
					return ($moduleFile . $extension);
				}
			}
		}

    private static final function inGlobalRootDirectory ( $directory ) {
      $directory = preg_replace ('/(\/|\\\)+$/', '', $directory);

      return 1 === count (preg_split('/(\/|\\\)+/', $directory));
    }


    private static final function getDirectoryVendorDirPath ($directory) {

      $directoryVendorPathArray = [
        $directory . '/vendor/php_modules'
      ];

      if (!is_dir ($directoryVendorPathArray[0])) {
        $directoryVendorPathArray = [];
      }

      if ( self::inGlobalRootDirectory($directory) ) {
        return $directoryVendorPathArray;
      }

      return array_merge (
        $directoryVendorPathArray,
        self::getDirectoryVendorDirPath (
          dirname($directory)
        )
      );
    }

    public static final function getVendorAlternates ($backTrace = null) {
      if ( self::validTrace ($backTrace) ) {

        $backTraceFileDirectory = dirname (
          $backTrace[ 0 ][ 'file' ]
        );

        return self::getDirectoryVendorDirPath (
          $backTraceFileDirectory
        );
      }
    }

	}
}
