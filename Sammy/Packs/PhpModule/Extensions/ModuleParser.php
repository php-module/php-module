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
  if (!trait_exists ('Sammy\Packs\PhpModule\Extensions\ModuleParser')) {
  /**
   * @trait ModuleParser
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
  trait ModuleParser {

    /**
     * [parser description]
     * @param  string $extension
     * @return Closure
     */
    public static function parser ($extension = '') {
      if (!is_string ($extension)) {
        return;
      }

      # Turn extension value into
      # lower case
      $extension = '.' . preg_replace ('/^\./', '', strtolower ((string)$extension));

      if (isset (self::$parsers [$extension])) {
        $parser = self::$parsers [$extension];

        if ($parser instanceof \Closure) {
          return $parser;
        } elseif (is_string ($parser)) {

          $parser = strtolower ((string)($parser));

          if (isset (self::$parsers [$parser])) {
            return self::parser ($parser);
          }
        }
      }
    }

    /**
     * [DefineParser0 description]
     * @param string $prop [description]
     * @param [type] $val  [description]
     */
    public static function DefineParser ($parser = '', $val = null) {
      if (!(is_string ($parser) && $val instanceof \Closure)) {
        return;
      }

      $moduleParseRe = '/^(module:parse\s+)/i';
      $parserList = preg_split ('/\s+/', preg_replace ($moduleParseRe, '', trim ($parser)));

      $parserListCount = count ($parserList);

      for ($i = 0; $i < $parserListCount; $i++) {
        $currentParserKey = strtolower ((string)$parserList [$i]);

        if ($i === 0) {
          self::$parsers [$currentParserKey] = $val;
        } else {
          self::$parsers [$currentParserKey] = strtolower ((string)$parserList [0]);
        }
      }
    }

    /**
     * [SaniModulePathName]
     * @param string $name
     * -
     * Sanitize module path name
     * to have a clean name, without
     * any special chars matching
     * to: /[\\\\\/\.]/
     */
    public static function SaniModulePathName (string $name = '') {
      # C:\usres\Sammy\.\foo\bar\baz.php
      # C:\users\Sammy\..\..\foo\bar.php
      $re = '/\.*(\/|\\\)+/';

      return preg_replace ($re, '', $name);
    }
  }}
}
