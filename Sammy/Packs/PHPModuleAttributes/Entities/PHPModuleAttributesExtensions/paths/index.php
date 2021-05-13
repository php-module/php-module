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
     * Paths
     */
    trait Paths {
        /**
         * [_confpaths description]
         * @param  string $args
         * @param  array  $paths
         * @return null
         */
        private static final function _confpaths () {
            if (!(func_num_args() >= 1))
                return;
            # paths
            # The paths list to configure by the
            # php module configuration file.
            $paths = func_get_arg ((-1) + func_num_args());

            if (!(is_array($paths) && $paths))
                return;

            foreach ($paths as $path => $source) {
                if (!(is_string($path) && $path))
                    continue;

                self::definePath ($path,
                    self::readPath ($source)
                );
            }
        }

        /**
         * [_confpath description]
         * @param  string $path
         * @param  string $source
         * @return null
         */
        private static final function _confpath ($path = '', $source = '') {
            return self::_confpaths ([\str($path) => $source]);
        }
    }
}
