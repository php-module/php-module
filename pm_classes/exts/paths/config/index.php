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
     * Make sure the module base internal class is not
     * declared in the php global scope defore creating
     * it.
     */
    if (!trait_exists('php\module\PHPModulePathsConf')){
    /**
     * @trait PHPModulePathsConf
     * Base internal trait for the
     * module module.
     * -
     * This is (in the ils environment)
     * an instance of the php module,
     * wich should contain the module
     * core functionalities that should
     * be extended.
     */
    trait PHPModulePathsConf {
        /**
         * [paths Get PHP Module Paths]
         * @return array
         */
        public static final function paths () {
            $pathsList = self::getConfig ('phpModulePaths');
            /**
             * Merge the '$pathsList' array to the 'module_default_paths'
             * array to have them by default as defined php module paths
             */
            return array_merge ((is_array ($pathsList) ? $pathsList : []),
                self::$module_default_paths
            );
        }
        /**
         * [definePath define a new path shortcut]
         * @param  string $prefix [Path Prefix (The shortcut)]
         * @param  string $sufix  [Path Sufix (The target directory path)]
         * @return null
         */
        public static final function definePath ($prefix = '', $sufix = '') {
            self::config ('phpModulePaths', [ (string)$prefix => $sufix ] );
        }
        /**
         * [definedPath description]
         * @param  string $pathPrefix
         * @return boolean
         */
        public static final function definedPath ($pathPrefix = '') {
            $pathsList = self::paths ();
            # Validate
            return  ( boolean ) (is_string ($pathPrefix) &&
                isset ($pathsList [ $pathPrefix ])
            );
        }
    }}
}
