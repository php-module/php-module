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
  if (!trait_exists ('Sammy\Packs\PhpModule\Extensions\ModuleImports')) {
  /**
   * @trait ModuleImports
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
  trait ModuleImports {
    /**
     * [_confextensions description]
     * @param  string $cmdArgs
     * @param  array  $extensions
     * @return null
     */
    private static function _confextensions () {
      if (!(func_num_args () >= 1)) {
        return;
      }
      # extensions
      # The extensions list to configure by the
      # php module configuration files.
      $extensions = func_get_arg ((-1) + func_num_args ());

      # Configure and Setup the
      # given extension names
      self::setExtensions ($extensions);
    }

    private static function _confextension () {
      if (!(func_num_args () >= 1)) {
        return;
      }
      # extensions
      # The extensions list to configure by the
      # php module configuration files.
      $extension = func_get_arg (0);
      # Configure and Setup the
      # given extension names
      self::setExtensions ([$extension]);
    }

    /**
     * @method string stripWindowsDiskRef
     *
     * in windows OS, strip the reference for
     * the current disk the application is running in
     */
    private static function stripWindowsDiskRef ($moduleFileAbsolutePath) {
      $osName = php_uname ('s');

      if (preg_match ('/windows/i', $osName)) {
        $moduleFileAbsolutePath = preg_replace ('/^([^:]+):/', '', $moduleFileAbsolutePath);
      }

      $moduleFileAbsolutePath = preg_replace ('/^([\\\\\/]+)/', '', $moduleFileAbsolutePath);

      return join (DIRECTORY_SEPARATOR, ['', $moduleFileAbsolutePath]);
    }

    /**
     * [shouldImport description]
     * @param  string $moduleFile [description]
     * @return string
     */
    public static function shouldImport ($moduleFile = '') {
      if (!(is_string ($moduleFile) && $moduleFile)) {
        return;
      }

      $extensionsCount = count (self::$extensions);

      for ($i = 0; $i < $extensionsCount; $i++) {
        $extension = (string) (self::$extensions [$i]);

        $moduleFileAbsolutePath = self::stripWindowsDiskRef ($moduleFile . $extension);

        if (is_file ($moduleFileAbsolutePath)) {
          return ($moduleFileAbsolutePath);
        }
      }
    }

    private static function inGlobalRootDirectory ( $directory ) {
      $directory = preg_replace ('/(\/|\\\)+$/', '', $directory);

      return 1 === count (preg_split ('/(\/|\\\)+/', $directory));
    }


    public static function getDirectoryVendorDirPath ($directory) {

      $directoryVendorPathArray = [];

      if (is_dir ($directoryPath = $directory . '/vendor/php_modules')) {
        $directoryVendorPathArray = [realpath ($directoryPath)];
      }

      if (self::inGlobalRootDirectory ($directory)) {
        return $directoryVendorPathArray;
      }

      return array_merge (
        $directoryVendorPathArray,
        self::getDirectoryVendorDirPath (dirname ($directory))
      );
    }

    public static function getVendorAlternates ($backTrace = null) {
      if (self::validTrace ($backTrace)) {

        $backTraceFileDirectory = dirname ($backTrace [0]['file']);

        return self::getDirectoryVendorDirPath ($backTraceFileDirectory);
      }
    }

    private static function path2re ($path = '') {
      $re = '/[\/\^\$\[\]\{\}\(\)\\\\.]/';
      return '/^(' . preg_replace_callback ($re, function ($match) {
        return '\\' . $match [0];
      }, (string)$path) . ')/';
    }

    /**
     * [getModuleRootDir description]
     * @param  string $absPath
     * @return string|null
     */
    public static function getModuleRootDir ($abs = '') {
      # In order making use of any rule or settings
      # in the current ilsApplication, php module'll
      # make sure that the Applicatio COnfigurations
      # are all done to use them.
      # In case of not done, it'll stop because of
      # missing informations.
      # ---
      # ConfigurationsDone
      # A boolean value indicating if the ils application
      # configurations are ready and done.

      # Stop the function flux in case of missing
      # informations about the ils application
      # configurations;
      # This is done to avoid having errors when
      # trying to use them late.

      # php module configurations
      # a constant in the Configure
      # class containing the php
      # module basic configurations
      # set inside.
      $phpModuleConf = self::getConfigDatas ();
      # Before going the script on, make sure
      # the php module configurations are already
      # set; and the php module configurations such
      # as the php module directories are set there.
      # Return a null value otherwise.
      if (!(is_array ($phpModuleConf) &&
        isset ($phpModuleConf ['php-modules-directories']) &&
        is_array ($phpModuleConf ['php-modules-directories']))) {
        # Stop the function flux on
        # condition that the php module
        # configurations are not set yet.
        return;
      }

      #$dirs = ;

      $dirs = array_merge (
        $phpModuleConf ['php-modules-directories'],
        self::getDirectoryVendorDirPath ($abs)
      );

      $moduleDatas = [];

      foreach ( $dirs as $dir ) {

        $absDir = self::path2re (preg_replace ('/\/+/',
          DIRECTORY_SEPARATOR, self::readPath ($dir)
        ));

        if (@preg_match ($absDir, $abs)) {
          $modulePath = preg_replace ($absDir, '', $abs);

          $splModulePath = preg_split ('/(\/|\\\)+/',
            preg_replace ('/^(\/|\\\)+/', '', $modulePath)
          );

          /**
           * [$moduleDatas description]
           * @var array
           */
          $moduleDatas = array (
            /* 'moduleBaseDir' */ self::readPath ($dir),
            /* 'moduleRootDir' */ $splModulePath [0]
          );

          break;
        }
      }

      return realpath (join (DIRECTORY_SEPARATOR, $moduleDatas));
    }

    public static function validTrace ($backTrace) {
      return ( boolean ) (
        is_array ($backTrace) &&
        isset ($backTrace [ 0 ]) &&
        is_array ($backTrace [ 0 ]) &&
        isset ($backTrace [ 0 ]['file']) &&
        is_string ($f = $backTrace [ 0 ][ 'file' ])
      );
    }

    public static function root_dir ($backTrace = []) {
      if (!self::validTrace ($backTrace)) {
        return;
      }

      $dir = dirname ($backTrace [0]['file']);

      return forward_static_call_array ([self::class, 'getModuleRootDir'], [$dir]);
    }


  }}
}
