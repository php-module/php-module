<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\PhpModule\Extensions\ModulePaths
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
namespace Sammy\Packs\PhpModule\Extensions\ModulePaths {
  /**
   * Make sure the module base internal trait is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists ('Sammy\Packs\PhpModule\Extensions\ModulePaths\ModulePathsReader')) {
  /**
   * @trait ModulePathsReader
   * Base internal trait for the
   * PhpModule\Extensions\ModulePaths module.
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
  trait ModulePathsReader {
    /**
     * [readPath Read a PHP Module Path inside a given absolute file path]
     * @param  string $absPath [Absolute Path]
     * @return string
     */
    public static function readPath (string $absPath = '', $args = []) {
      /**
       * [$backTrace BackTrace]
       * @var arrau
       */
      $backTrace = func_get_arg (-1 + func_num_args ());

      if (!self::validTrace ($backTrace)) {
        $backTrace = debug_backtrace ();
      }

      if (!(is_string ($absPath) && $absPath)) {
        return;
      }

      $re = '([\\\\\/]+)';
      $ds = DIRECTORY_SEPARATOR;

      $absPath = preg_replace ($re, $ds, $absPath);

      $absPathSlices = preg_split ($re, $absPath);

      $pathListTail = array_slice ($absPathSlices, 1, count ($absPathSlices));

      if (self::definedPath ($absPathSlices [0])) {
        /**
         * [$path]
         * @var string
         */
        $path = self::readPathValue ($absPathSlices [0], $args, $backTrace);

        $path = preg_replace ('/' . $re . '$/', '', $path);

        $absPath = join ($ds, array_merge ([$path], $pathListTail));
      } else {
        /**
         * [$path]
         * @var string
         */
        $path = self::readPathValueFromProp ($absPathSlices [0], $backTrace);

        $path = preg_replace ('/' . $re . '$/', '', $path);

        $absPath = join ($ds, array_merge ([$path], $pathListTail));
      }

      return preg_replace ($re, $ds, $absPath);
    }

    /**
     * [readPathValue description]
     * @param  string $pathPrefix
     * @return string
     */
    private static function readPathValue ($pathPrefix = '', $args = []) {
      /**
       * [$paths description]
       * @var array
       */
      $paths = self::paths ();
      $re = '/((\\\|\/)+)$/';

      $backTrace = func_get_arg (-1 + func_num_args ());

      if (!self::validTrace ($backTrace)) {
        $backTrace = debug_backtrace ();
      }

      $args = !is_array ($args) ? [] : $args;

      if (is_string ($paths [$pathPrefix])) {
        /**
         * [$pathValue final path value]
         * @var string
         */
        $pathValue = preg_replace ($re, '', $paths [$pathPrefix]);
      } elseif ($paths [$pathPrefix] instanceof \Closure) {
        $val = preg_replace ($re, '', $paths [$pathPrefix]);

        $arguments = array_merge ([$val], $args);
        /**
         * [$pathValue final path value]
         * @var string
         */
        $pathValue = call_user_func_array ($paths [$pathPrefix], $arguments);
      }

      return self::readPath ($pathValue, $args, $backTrace);
    }

    private static function isRegEx ($str) {
      return ( boolean ) (
        preg_match ('/^\//', $str) &&
        preg_match ('/(\/([i])?)$/', $str)
      );
    }

    /**
     * [readPathValueFromProp description]
     * @param  string $pathPrefix
     * @return string
     */
    private static function readPathValueFromProp ($pathPrefix = '') {
      $re = '/^(<([a-zA-Z0-9_\-]+)\s*\/?>)$/';
      $re1 = '/^:module_(.+)/i';
      /**
       * [$backTrace BackTrace]
       * @var arrau
       */
      $backTrace = func_get_arg (-1 + func_num_args ());

      if (!self::validTrace ($backTrace)) {
        $backTrace = debug_backtrace ();
      }
      /**
       * Verify matching of '$re' inside the
       * '$pathPrefix' string and save the match
       * results in the '$match' variable
       */
      if (preg_match ($re, $pathPrefix, $match)) {
        $property = trim ($match [2]);

        $propertyValue = self::getConfig ($property);

        if ( is_string ($propertyValue) ) {
          return self::readPath ($propertyValue, [], $backTrace);
        } else {
          $paths = self::paths ();

          foreach ($paths as $path => $sufix) {
            if (!self::isRegEx ($path)) {
              continue;
            }

            /**
             * Try Matching
             */
            if (@preg_match ($path, $property, $match)) {
              return self::readPath ($sufix, [$match], $backTrace);
            }
          }
        }
      } elseif (preg_match ($re1, $pathPrefix, $match)) {
        /**
         *
         */
        if (method_exists (self::class, $match [1])) {
          $arguments = [$backTrace, $pathPrefix, $match];

          return forward_static_call_array ([self::class, $match [1]], $arguments);
        }
      } elseif (preg_match ('/^<+$/', trim ($pathPrefix), $match)) {
        $dir = dirname ($backTrace [0]['file']);

        $pathPrefixLen = strlen ($match [0]);

        for ($i = 0; $i < $pathPrefixLen; $i++) {
          $dir = dirname ($dir);
        }

        return $dir;
      }

      return $pathPrefix;
    }

  }}
}
