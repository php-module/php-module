<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package php\module
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
namespace php {
  use Sammy\Packs\PhpModule\Base as PhpModuleBase;
  use Sammy\Packs\PhpModule\Extensions\ModulePaths;
  use Sammy\Packs\PhpModule\Extensions\ModuleParser;
  use Sammy\Packs\PhpModule\Extensions\ModuleConfig;
  use Sammy\Packs\PhpModule\Extensions\ModuleSetter;
  use Sammy\Packs\PhpModule\Extensions\ModuleGetter;
  use Sammy\Packs\PhpModule\Extensions\ModuleImports;
  use Sammy\Packs\PhpModule\Core\ModuleDataObject;
  use Sammy\Packs\PhpModule\ImportAlternateDirectories;
  use Sammy\Packs\PhpModule\Extensions\ModuleCmd;

  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!class_exists ('php\module')){
  /**
   * @class module
   * Base internal class for the
   * php module.
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
  class module {
    use ModuleCmd;
    use ModulePaths;
    use ModuleParser;
    use ModuleConfig;
    use ModuleSetter;
    use ModuleGetter;
    use ModuleImports;
    use PhpModuleBase;
    use ModuleDataObject;
    use ImportAlternateDirectories;

    private static $autoloaded = false;

    public static function setModuleCache ($module, $module_abs) {
    }

    /**
     * @method exports
     * - Method used to export any object
     * - for the  current module
     */
    public function exports () {
      if (!$this->moduleInterrop) {
        $this->moduleInterrop = !false;
        $this->props ['#default'] = $this->exports;
      }

      return call_user_func_array ([$this, 'setProp'], func_get_args ());
    }

    public static function autoload () {}

    /**
     * [init description]
     * @return null
     */
    private static function init () {
      self::$parsers = [
        '.txt' => function ($file) {
          return file_get_contents ($file);
        }
      ];
    }

    /**
     * [setExtensions description]
     * @param array $extensions
     */
    public static function setExtensions ($extensions = []) {
      if (!(is_array ($extensions) && $extensions)) {
        return;
      }

      $extensionsCount = count ($extensions);

      for ($i = 0; $i < $extensionsCount; $i++) {
        if (!(is_string ($extensions [$i]) && $extensions [$i])) {
          continue;
        }

        $extension = strtolower ($extensions [$i]);

        if (!in_array ($extension, self::$extensions)) {
          array_push (self::$extensions, $extension);
        }
      }
    }

    /**
     * [setExtension description]
     * @param string $extension
     */
    public static function setExtension ($extension = '') {
      return self::setExtensions ([$extension]);
    }

    /**
     * [_confextends description]
     * @param  string $args [description]
     * @param  array  $libs [description]
     * @return null
     */
    private static function _confextends ($args) {
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
  }}
}
