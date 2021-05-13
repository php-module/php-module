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
  $pm_autoload_file = dirname (dirname(dirname(dirname(__DIR__))));
  $pm_autoload_file .= DIRECTORY_SEPARATOR . 'autoload.php';

  if (is_file ($pm_autoload_file)) {
    include_once $pm_autoload_file;
  }
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
    use module\Parser;
    use module\Define;
    use module\Config;
    use module\InitCl;
    use module\Setter;
    use module\Getter;
    use module\clBase;
    use module\Paths;

    # Exts
    use module\AlternateDirectories;
    use module\InstanceFragmentBase;
    use module\PHPModuleConfigure;
    use module\ImportByAlternates;
    use module\PHPModulePathsConf;
    use module\SaniModulePathName;
    use module\PHPModulePathsRead;
    use module\RootImportConfigs;
    use module\ExtensionSetters;
    use module\CommandExecuter;
    use module\Extender;

    private static $autoloaded = false;

    public static final function setModuleCache ($module, $module_abs) {
    }
    /**
     * @method exports
     * - Method used to export any object
     * - for the  current module
     */
    public final function exports () {
      if (!$this->moduleInterrop) {
        $this->moduleInterrop = ( true );
        $this->props ['#default'] = ( $this->exports );
      }

      return call_user_func_array ([$this, 'setProp'],
        func_get_args ()
      );
    }

    public final static function autoload () {}
  }}
}
