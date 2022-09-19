<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\PhpModule\Extensions
 * - Autoload, application dependencies
 *
 * MIT License
 *
 * Copyright (c) 2020 Ysare
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
namespace Sammy\Packs\PhpModule\Extensions {
  /**
   * Make sure the module base internal trait is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists ('Sammy\Packs\PhpModule\Extensions\ModulePaths')) {
  /**
   * @trait ModulePaths
   * Base internal trait for the
   * PhpModule\Extensions module.
   * -
   * This is (in the ils environment)
   * an instance of the php module,
   * wich should contain the module
   * core functionalities that should
   * be extended.
   * -
   * For extending the module, just create
   * an 'exts' directory in the module directory
   * and boot it by using the ils directory boot.
   * -
   */
  trait ModulePaths {
    use ModulePaths\ModulePathsReader;

    /**
     * [paths Get PHP Module Paths]
     * @return array
     */
    public static function paths () {
      $pathsList = self::getConfig ('phpModulePaths');

      if (!is_array ($pathsList)) {
        return self::$module_default_paths;
      }

      /**
       * Merge the '$pathsList' array to the 'module_default_paths'
       * array to have them by default as defined php module paths
       */
      return array_merge ($pathsList, self::$module_default_paths);
    }

    /**
     * [definePath define a new path shortcut]
     * @param  string $prefix [Path Prefix (The shortcut)]
     * @param  string $sufix  [Path Sufix (The target directory path)]
     * @return null
     */
    public static function definePath (string $prefix = '', $sufix = '') {
      self::config ('phpModulePaths', [$prefix => $sufix]);
    }

    /**
     * [definedPath description]
     * @param  string $pathPrefix
     * @return boolean
     */
    public static function definedPath (string $pathPrefix = '') {
      $pathsList = self::paths ();
      # Validate existing path
      return  (boolean)(isset ($pathsList [$pathPrefix]));
    }

    /**
     * [_confpaths description]
     * @param  string $args
     * @param  array  $paths
     * @return null
     */
    private static function _confpaths () {
      if (!(func_num_args () >= 1)) {
        return;
      }
      # paths
      # The paths list to configure by the
      # php module configuration file.
      $paths = func_get_arg ((-1) + func_num_args ());

      if (!(is_array ($paths) && $paths)) {
        return;
      }

      foreach ($paths as $path => $source) {
        if (!(is_string ($path) && $path)) {
          continue;
        }

        self::definePath ($path, self::readPath ($source));
      }
    }

    /**
     * [_confpath description]
     * @param  string $path
     * @param  string $source
     * @return null
     */
    private static function _confpath (string $path = '', $source = '') {
      return self::_confpaths ([$path => $source]);
    }

  }}
}
