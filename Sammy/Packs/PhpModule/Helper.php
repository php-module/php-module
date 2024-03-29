<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\PhpModule
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
namespace Sammy\Packs\PhpModule {
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!class_exists ('Sammy\Packs\PhpModule\Helper')) {
  /**
   * @class Helper
   * Base internal class for the
   * PhpModule module.
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
  class Helper {
    /**
     * @method boolean isPhpFile
     */
    private static function isPhpFile (string $file) {
      $fileExtension = strtolower (pathinfo ($file, PATHINFO_EXTENSION));
      # Verify if '$file' a php file
      return ( boolean )(
        file_exists ($file) &&
        in_array ($fileExtension, ['php'])
      );
    }

    /**
     * @method void autoloadFiles
     */
    public static function autoloadFiles (string $dir = null) {
      if (!(is_string ($dir) && is_dir ($dir))) {
        $dir = dirname (__FILE__);
      }

      $dir = preg_replace ('/(\\|\/)+$/', '', $dir);

      $dirPathRe = join (DIRECTORY_SEPARATOR, [
        $dir, '*'
      ]);

      $dirFileList = glob ($dirPathRe);

      foreach ($dirFileList as $dirFilePath) {
        if (is_dir ($dirFilePath)) {
          self::autoloadFiles ($dirFilePath);
        } elseif (self::isPhpFile ($dirFilePath)) {
          include_once $dirFilePath;
        }
      }
    }

    /**
     * @method array object to json
     */
    public static function json ($object = null) {
      if (!(is_object ($object))) {
        return $object;
      }

      if (function_exists ('lean_object')) {
        $object = (array)(lean_object ($object));
      }

      $newObject = [];

      foreach ($object as $key => $value) {
        $newObject [ $key ] = self::json ($value);
      }

      return is_object ($newObject) ? ((array)($newObject)) : $newObject;
    }

    /**
     * @method array file content to json
     */
    public static function jsonFile ($filePath) {
      if (!is_file ($filePath)) {
        return;
      }

      $fileContent = json_decode (file_get_contents ($filePath));

      return self::json ($fileContent);
    }
  }}
}
